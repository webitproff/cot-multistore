<?php
/**
 * Store item list
 *
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */

use cot\modules\mstore\inc\MstoreDictionary;

defined('COT_CODE') or die('Wrong URL');

// Environment setup
const COT_LIST = true;
Cot::$env['location'] = 'list';

$s = cot_import('s', 'G', 'ALP'); // order field name without 'msitem_'
$w = cot_import('w', 'G', 'ALP', 4); // order way (asc, desc)
$c = cot_import('c', 'G', 'TXT'); // cat code
$o = cot_import('ord', 'G', 'ARR'); // filter field names without 'msitem_'
$p = cot_import('p', 'G', 'ARR'); // filter values

$maxItemRowsPerPage = (int) Cot::$cfg['mstore']['cat___default']['mstoremaxlistsperpage'];
if ($maxItemRowsPerPage <= 0) {
    $maxItemRowsPerPage = Cot::$cfg['mstoremaxlistsperpage'];
}
if (
    !empty($c)
    && !empty(Cot::$cfg['mstore']['cat_' . $c])
    && !empty(Cot::$cfg['mstore']['cat_' . $c]['mstoremaxlistsperpage'])
) {
    $maxItemRowsPerPage = (int) Cot::$cfg['mstore']['cat_' . $c]['mstoremaxlistsperpage'];
}

// item number for items list
list($pg, $d, $durl) = cot_import_pagenav('d', $maxItemRowsPerPage);

// item number for cats list
list($pgc, $dc, $dcurl) = cot_import_pagenav('dc', Cot::$cfg['mstore']['mstoremaxlistsperpage']);

// Проверяем права доступа
if ($c === 'all' || $c === 'system') {
    list(Cot::$usr['auth_read'], Cot::$usr['auth_write'], Cot::$usr['isadmin']) = cot_auth('admin', 'a');
    cot_block(Cot::$usr['isadmin']);
} elseif ($c === 'unvalidated' || $c === 'saved_drafts') {
    list(Cot::$usr['auth_read'], Cot::$usr['auth_write'], Cot::$usr['isadmin']) = cot_auth('mstore', 'any');
    cot_block(Cot::$usr['auth_write']);
} elseif (!empty($c) && isset(Cot::$structure['mstore'][$c])) {
    list(Cot::$usr['auth_read'], Cot::$usr['auth_write'], Cot::$usr['isadmin']) = cot_auth('mstore', $c);
    cot_block(Cot::$usr['auth_read']);
} else {
    // Если категория не выбрана или не существует, устанавливаем права для общего доступа
    list(Cot::$usr['auth_read'], Cot::$usr['auth_write'], Cot::$usr['isadmin']) = cot_auth('mstore', 'any');
    cot_block(Cot::$usr['auth_read']);
}

/* === Hook === */
foreach (cot_getextplugins('mstore.list.first') as $pl) {
    include $pl;
}
/* ===== */

$cat = [];
if (!empty($c) && isset(Cot::$structure['mstore'][$c])) {
    $cat = &Cot::$structure['mstore'][$c];
} else {
    $cat['title'] = Cot::$L['mstore_all_items'] ?: 'All Items';
    $cat['desc'] = Cot::$L['mstore_all_items_desc'] ?: 'All available store items';
    $cat['tpl'] = 'all';
}

$defaultOrder = !empty($c) && !empty(Cot::$cfg['mstore']['cat_' . $c]['mstoreorder'])
    ? Cot::$cfg['mstore']['cat_' . $c]['mstoreorder']
    : Cot::$cfg['mstore']['cat___default']['mstoreorder'];
if (empty($s)) {
    $s = $defaultOrder;
}

$defaultOrderWay = !empty($c) && !empty(Cot::$cfg['mstore']['cat_' . $c]['mstoreway'])
    ? Cot::$cfg['mstore']['cat_' . $c]['mstoreway']
    : Cot::$cfg['mstore']['cat___default']['mstoreway'];
if (empty($w) || !in_array($w, ['asc', 'desc'])) {
    $w = $defaultOrderWay;
}

$itemListTruncateText = (int) Cot::$cfg['mstore']['cat___default']['mstoretruncatetext'];
if (
    !empty($c)
    && !empty(Cot::$cfg['mstore']['cat_' . $c])
    && isset(Cot::$cfg['mstore']['cat_' . $c]['mstoretruncatetext'])
    && ((string) Cot::$cfg['mstore']['cat_' . $c]['mstoretruncatetext'] !== '')
) {
    $itemListTruncateText = (int) Cot::$cfg['mstore']['cat_' . $c]['mstoretruncatetext'];
}

$where = [];
$params = [];

$where_state = Cot::$usr['isadmin'] ? '1' : 'msitem_ownerid = ' . Cot::$usr['id'];
if ($c === 'unvalidated') {
    $cat['tpl'] = 'unvalidated';
    $where['state'] = 'msitem_state = ' . MstoreDictionary::STATE_PENDING;
    $where['ownerid'] = Cot::$usr['isadmin'] ? '1' : 'msitem_ownerid = ' . Cot::$usr['id'];
    $cat['title'] = Cot::$L['mstore_validation'];
    $cat['desc'] = Cot::$L['mstore_validation_desc'];
    $s = 'date';
    $w = 'desc';
} elseif ($c === 'saved_drafts') {
    $cat['tpl'] = 'unvalidated';
    $where['state'] = 'msitem_state = ' . MstoreDictionary::STATE_DRAFT;
    $where['ownerid'] = Cot::$usr['isadmin'] ? '1' : 'msitem_ownerid = ' . Cot::$usr['id'];
    $cat['title'] = Cot::$L['mstore_drafts'];
    $cat['desc'] = Cot::$L['mstore_drafts_desc'];
    $s = 'date';
    $w = 'desc';
} elseif ($c === 'all' || empty($c)) {
    $where['state'] = 'msitem_state = ' . MstoreDictionary::STATE_PUBLISHED;
} else {
    $where['cat'] = 'msitem_cat = ' . Cot::$db->quote($c);
    $where['state'] = 'msitem_state = ' . MstoreDictionary::STATE_PUBLISHED;
}

if (!Cot::$usr['isadmin'] && $c !== 'unvalidated' && $c !== 'saved_drafts') {
    $where['date'] = "msitem_begin <= {$sys['now']} AND (msitem_expire = 0 OR msitem_expire > {$sys['now']})";
}

if (!Cot::$db->fieldExists(Cot::$db->mstore, "msitem_$s")) {
    $s = 'title';
}
$orderby = "msitem_$s $w";

$list_url_path = [];
if (!empty($c)) {
    $list_url_path['c'] = $c;
}
if (!empty($o)) {
    $list_url_path['ord'] = $o;
}
if (!empty($p)) {
    $list_url_path['p'] = $p;
}
if ($s !== $defaultOrder) {
    $list_url_path['s'] = $s;
}
if ($w !== $defaultOrderWay) {
    $list_url_path['w'] = $w;
}

$list_url = cot_url('mstore', $list_url_path);

// For the canonical URL
$itemurl_params = $list_url_path;
if ($durl > 1) {
    $itemurl_params['d'] = $durl;
}
if ($dcurl > 1) {
    $itemurl_params['dc'] = $dcurl;
}

$catpatharray = !empty($c) ? cot_structure_buildpath('mstore', $c) : [];
$catpath = in_array($c, ['all', 'system', 'unvalidated', 'saved_drafts'], true) || empty($c)
    ? $cat['title']
    : cot_breadcrumbs($catpatharray, Cot::$cfg['homebreadcrumb'], true);

$shortpath = $catpatharray;
array_pop($shortpath);
$catpath_short = in_array($c, ['all', 'system', 'unvalidated', 'saved_drafts'], true) || empty($c)
    ? ''
    : cot_breadcrumbs($shortpath, Cot::$cfg['homebreadcrumb'], false);

$join_columns = isset($join_columns) ? $join_columns : '';
$join_condition = isset($join_condition) ? $join_condition : '';

/* === Hook === */
foreach (cot_getextplugins('mstore.list.query') as $pl) {
    include $pl;
}
/* ===== */

if ($o && $p) {
    if (!is_array($o)) {
        $o = [$o];
    }
    if (!is_array($p)) {
        $p = [$p];
    }
    $filters = array_combine($o, $p);
    foreach ($filters as $key => $val) {
        $key = cot_import($key, 'D', 'ALP', 16);
        $val = cot_import($val, 'D', 'TXT', 16);
        if ($key && $val && Cot::$db->fieldExists(Cot::$db->mstore, "msitem_$key")) {
            $params[$key] = $val;
            $where['filter'][] = "msitem_$key = :$key";
        }
    }
    empty($where['filter']) || $where['filter'] = implode(' AND ', $where['filter']);
}

if (empty($sql_item_string)) {
    $where = array_filter($where);
    $where_sql = ($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    // Подсчёт без дублей товаров
    $sql_item_count = "SELECT COUNT(DISTINCT p.msitem_id)
        FROM $db_mstore AS p
        $join_condition
        LEFT JOIN $db_users AS u ON u.user_id = p.msitem_ownerid
        $where_sql";

    // Основной запрос без дублей товаров
    $sql_item_string = "SELECT p.*, u.* $join_columns
        FROM $db_mstore AS p
        $join_condition
        LEFT JOIN $db_users AS u ON u.user_id = p.msitem_ownerid
        $where_sql
        GROUP BY p.msitem_id
        ORDER BY $orderby
        LIMIT $d, $maxItemRowsPerPage";
}


try {
    $totallines = $db->query($sql_item_count, $params)->fetchColumn();
    $sqllist = $db->query($sql_item_string, $params);
} catch (Exception $e) {
    cot_log("SQL error in mstore list: " . $e->getMessage(), 'error', 'mstore', 'query');
    cot_die_message(500);
}

if (
    (
        !Cot::$cfg['easypagenav']
        && $durl > 0
        && $maxItemRowsPerPage > 0
        && $durl % $maxItemRowsPerPage > 0
    )
    || ($d > 0 && $d >= $totallines)
) {
    cot_redirect(cot_url('mstore', $list_url_path + ['dc' => $dcurl]));
}

$pagenav = cot_pagenav(
    'mstore',
    $list_url_path + ['dc' => $dcurl],
    $d,
    $totallines,
    $maxItemRowsPerPage
);

$catTitle = htmlspecialchars(strip_tags($cat['title']));
Cot::$out['desc'] = htmlspecialchars(strip_tags($cat['desc']));
Cot::$out['subtitle'] = $catTitle;
if (!empty($c) && !empty(Cot::$cfg['mstore']['cat_' . $c]['keywords'])) {
    Cot::$out['keywords'] = Cot::$cfg['mstore']['cat_' . $c]['keywords'];
} elseif (!empty(Cot::$cfg['mstore']['cat___default']['keywords'])) {
    Cot::$out['keywords'] = Cot::$cfg['mstore']['cat___default']['keywords'];
}

if (!empty($c) && !empty(Cot::$cfg['mstore']['cat_' . $c]['metadesc'])) {
    Cot::$out['desc'] = Cot::$cfg['mstore']['cat_' . $c]['metadesc'];
}
if (empty(Cot::$out['desc']) && !empty(Cot::$cfg['mstore']['cat___default']['metadesc'])) {
    Cot::$out['desc'] = Cot::$cfg['mstore']['cat___default']['metadesc'] . ' - ' . $catTitle;
}

// Building the canonical URL
Cot::$out['canonical_uri'] = cot_url('mstore', $itemurl_params);

$_SESSION['cat'] = $c;

$mskin = cot_tplfile(['mstore', 'list', $cat['tpl']]);

if (!empty($pgc) && $pgc > 1) {
    Cot::$out['subtitle'] .= ' (' . $pgc . ')';
}

/* === Hook === */
foreach (cot_getextplugins('mstore.list.main') as $pl) {
    include $pl;
}
/* ===== */

require_once Cot::$cfg['system_dir'] . '/header.php';

$t = new XTemplate($mskin);

$categoryIcon = !empty($cat['icon'])
    ? cot_rc(
        'img_structure_cat',
        [
            'icon' => $cat['icon'],
            'title' => htmlspecialchars($cat['title']),
            'desc' => htmlspecialchars($cat['desc']),
        ]
    )
    : '';
$t->assign([
    'LIST_CAT_CODE' => $c,
    'LIST_CAT_TITLE' => htmlspecialchars($cat['title']),
    'LIST_CAT_RSS' => cot_url('rss', ['c' => $c]),
    'LIST_CAT_PATH' => $catpath,
    'LIST_CAT_PATH_SHORT' => $catpath_short,
    'LIST_CAT_URL' => cot_url('mstore', $list_url_path),
    'LIST_CAT_DESCRIPTION' => $cat['desc'],
    'LIST_CAT_ICON' => $categoryIcon,
    'LIST_CAT_ICON_SRC' => !empty($cat['icon']) ? $cat['icon'] : '',
    'LIST_BREADCRUMBS' => $catpath,
    'LIST_BREADCRUMBS_SHORT' => $catpath_short,
]);

$t->assign(cot_generatePaginationTags($pagenav));

if (Cot::$usr['auth_write'] && $c != 'all' && $c != 'unvalidated' && $c != 'saved_drafts') {
    $submitNewItemUrl = cot_url('mstore', ['c' => $c, 'm' => 'add']);
    $t->assign([
        'LIST_SUBMIT_NEW_ITEM' => cot_rc('mstore_submitnewitem', ['sub_url' => $submitNewItemUrl]),
        'LIST_SUBMIT_NEW_ITEM_URL' => $submitNewItemUrl,
    ]);
}

// Extra fields for structure
if (isset(Cot::$extrafields[Cot::$db->structure])) {
    foreach (Cot::$extrafields[Cot::$db->structure] as $exfld) {
        $uname = strtoupper($exfld['field_name']);
        $exfld_title = cot_extrafield_title($exfld, 'structure_');
        $t->assign([
            'LIST_CAT_' . $uname . '_TITLE' => $exfld_title,
            'LIST_CAT_' . $uname => cot_build_extrafields_data('structure', $exfld, $cat[$exfld['field_name']] ?? ''),
            'LIST_CAT_' . $uname . '_VALUE' => $cat[$exfld['field_name']] ?? '',
        ]);
    }
}

$arrows = [];
foreach (Cot::$extrafields[Cot::$db->mstore] + ['title' => 'title', 'key' => 'key', 'date' => 'date', 'author' => 'author', 'owner' => 'owner', 'count' => 'count', 'filecount' => 'filecount'] as $row_k => $row_p) {
    $uname = strtoupper($row_k);
    $url_asc = cot_url('mstore', ['s' => $row_k, 'w' => 'asc'] + $list_url_path);
    $url_desc = cot_url('mstore', ['s' => $row_k, 'w' => 'desc'] + $list_url_path);
    $arrows[$row_k]['asc'] = Cot::$R['icon_down'];
    $arrows[$row_k]['desc'] = Cot::$R['icon_up'];
    if ($s == $row_k) {
        $arrows[$s][$w] = Cot::$R['icon_vert_active'][$w];
    }
    if (in_array($row_k, ['title', 'key', 'date', 'author', 'owner', 'count', 'filecount'])) {
        $t->assign([
            'LIST_TOP_' . $uname => cot_rc("list_link_$row_k", [
                'cot_img_down' => $arrows[$row_k]['asc'],
                'cot_img_up' => $arrows[$row_k]['desc'],
                'list_link_url_down' => $url_asc,
                'list_link_url_up' => $url_desc
            ])
        ]);
    } else {
        $extratitle = isset($L['mstore_' . $row_k . '_title']) ? $L['mstore_' . $row_k . '_title'] : $row_p['field_description'];
        $t->assign([
            'LIST_TOP_' . $uname => cot_rc('list_link_field_name', [
                'cot_img_down' => $arrows[$row_k]['asc'],
                'cot_img_up' => $arrows[$row_k]['desc'],
                'list_link_url_down' => $url_asc,
                'list_link_url_up' => $url_desc
            ])
        ]);
    }
    $t->assign([
        'LIST_TOP_' . $uname . '_URL_ASC' => $url_asc,
        'LIST_TOP_' . $uname . '_URL_DESC' => $url_desc
    ]);
}

$kk = 0;
$allsub = cot_structure_children('mstore', $c ?: '', false, false, true, false);
$subcat = array_slice($allsub, $dc, Cot::$cfg['mstore']['mstoremaxlistsperpage']);

/* === Hook === */
foreach (cot_getextplugins('mstore.list.rowcat.first') as $pl) {
    include $pl;
}
/* ===== */

/* === Hook - Part1 : Set === */
$extp = cot_getextplugins('mstore.list.rowcat.loop');
/* ===== */
foreach ($subcat as $x) {
    $kk++;
    $cat_childs = cot_structure_children('mstore', $x);
    $subCategoriesCount = 0;
    foreach ($cat_childs as $cat_child) {
        $subCategoriesCount += (int) ($structure['mstore'][$cat_child]['count'] ?? 0);
    }

    $sub_url_path = $list_url_path;
    $sub_url_path['c'] = $x;
    $t->assign([
        'LIST_CAT_ROW_ID' => $structure['mstore'][$x]['id'] ?? 0,
        'LIST_CAT_ROW_URL' => cot_url('mstore', $sub_url_path),
        'LIST_CAT_ROW_TITLE' => htmlspecialchars($structure['mstore'][$x]['title'] ?? ''),
        'LIST_CAT_ROW_DESCRIPTION' => $structure['mstore'][$x]['desc'] ?? '',
        'LIST_CAT_ROW_ICON' => !empty($structure['mstore'][$x]['icon'])
            ? cot_rc(
                'img_structure_cat',
                [
                    'icon' => $structure['mstore'][$x]['icon'],
                    'title' => htmlspecialchars($structure['mstore'][$x]['title'] ?? ''),
                    'desc' => htmlspecialchars($structure['mstore'][$x]['desc'] ?? ''),
                ]
            )
            : '',
        'LIST_CAT_ROW_ICON_SRC' => !empty($structure['mstore'][$x]['icon']) ? $structure['mstore'][$x]['icon'] : '',
        'LIST_CAT_ROW_COUNT' => $subCategoriesCount,
        'LIST_CAT_ROW_NUM' => $kk,
    ]);

    // Extra fields for structure
    if (!empty(Cot::$extrafields[Cot::$db->structure])) {
        foreach (Cot::$extrafields[Cot::$db->structure] as $exfld) {
            $uname = strtoupper($exfld['field_name']);
            $exfld_title = cot_extrafield_title($exfld, 'structure_');
            $t->assign([
                'LIST_CAT_ROW_' . $uname . '_TITLE' => $exfld_title,
                'LIST_CAT_ROW_' . $uname => cot_build_extrafields_data('structure', $exfld,
                    Cot::$structure['mstore'][$x][$exfld['field_name']] ?? ''),
                'LIST_CAT_ROW_' . $uname . '_VALUE' => Cot::$structure['mstore'][$x][$exfld['field_name']] ?? '',
            ]);
        }
    }

    /* === Hook - Part2 : Include === */
    foreach ($extp as $pl) {
        include $pl;
    }
    /* ===== */

    $t->parse('MAIN.LIST_CAT_ROW');
}

$pagenav_cat = cot_pagenav(
    'mstore',
    $list_url_path + ['d' => $durl],
    $dc,
    count($allsub),
    Cot::$cfg['mstore']['mstoremaxlistsperpage'],
    'dc'
);

$t->assign(cot_generatePaginationTags($pagenav_cat, 'LIST_CAT_'));

$jj = 0;
/* === Hook - Part1 : Set === */
$extp = cot_getextplugins('mstore.list.loop');
/* ===== */
$sqllist_rowset = $sqllist->fetchAll();

$sqllist_rowset_other = false;
/* === Hook === */
foreach (cot_getextplugins('mstore.list.before_loop') as $pl) {
    include $pl;
}
/* ===== */

if (!$sqllist_rowset_other) {
    // Validate/Unvalidate item actions are in admin controller. We need to redirect back.
    $urlParams = $list_url_path;
    if ($durl > 1) {
        $urlParams['d'] = $durl;
    }
    if ($dcurl > 1) {
        $urlParams['dc'] = $dcurl;
    }
    $backUrl = cot_url('mstore', $urlParams, '', true);

    foreach ($sqllist_rowset as $item) {
        $jj++;
        $t->assign(
            cot_generate_mstoretags(
                $item,
                'LIST_ROW_',
                $itemListTruncateText,
                Cot::$usr['isadmin'],
                false,
                '',
                $backUrl
            )
        );
        $t->assign([
            'LIST_ROW_OWNER' => cot_build_user($item['msitem_ownerid'], $item['user_name']),
            'LIST_ROW_ODDEVEN' => cot_build_oddeven($jj),
            'LIST_ROW_NUM' => $jj,
        ]);
        $t->assign(cot_generate_usertags($item, 'LIST_ROW_OWNER_'));

        /* === Hook - Part2 : Include === */
        foreach ($extp as $pl) {
            include $pl;
        }
        /* ===== */

        $t->parse('MAIN.LIST_ROW');
    }
}

// Error and message handling
cot_display_messages($t);

/* === Hook === */
foreach (cot_getextplugins('mstore.list.tags') as $pl) {
    include $pl;
}
/* ===== */

$t->parse('MAIN');
$moduleBody = $t->text('MAIN');

if (Cot::$cache && $usr['id'] === 0 && Cot::$cfg['cache_mstore']) {
    Cot::$cache->static->write();
}
