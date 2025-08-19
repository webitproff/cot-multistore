<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=users.details.tags
 * [END_COT_EXT]
 */

/**
 * Mstore module
 *
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');
use cot\modules\mstore\inc\MstoreDictionary;
require_once cot_incfile('mstore', 'module');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('mstore', 'any', 'RWA');

$tab = cot_import('tab', 'G', 'ALP');
$category = ($tab == 'mstore') ? cot_import('cat', 'G', 'TXT') : '';
list($pg, $d, $durl) = cot_import_pagenav('dmstore', Cot::$cfg['mstore']['cat___default']['mstoremaxlistsperpage']);

// Вкладка товаров
$t1 = new XTemplate(cot_tplfile(['mstore', 'userdetails'], 'module'));
$t1->assign([
    'MSTORE_ADD_URL' => cot_url('mstore', 'm=add'),
    'MSTORE_ADD_SHOWBUTTON' => $usr['auth_write'] ? true : false, // Для совместимости
]);

$where = [];
$params = [];
$order = [];

if ($usr['id'] == 0 || ($usr['id'] != $urr['user_id'] && !$usr['isadmin'])) {
    $where['state'] = 'msitem_state = ' . MstoreDictionary::STATE_PUBLISHED;
}

if ($category) {
    $where['cat'] = 'msitem_cat = ' . Cot::$db->quote($category);
}

$where['owner'] = 'msitem_ownerid = ' . (int)$urr['user_id'];

$order['date'] = 'msitem_date DESC';

$wherecount = $where;
if (isset($wherecount['cat'])) {
    unset($wherecount['cat']);
}

/* === Hook === */
foreach (cot_getextplugins('mstore.userdetails.query') as $pl) {
    include $pl;
}
/* ===== */

$where = array_filter($where);
$wherecount = array_filter($wherecount);
$where_sql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$wherecount_sql = $wherecount ? 'WHERE ' . implode(' AND ', $wherecount) : '';
$order_sql = $order ? 'ORDER BY ' . implode(', ', $order) : '';

$sql_mstore_count_cat = Cot::$db->query("SELECT msitem_cat, COUNT(msitem_cat) as cat_count FROM $db_mstore $wherecount_sql GROUP BY msitem_cat")->fetchAll();

$sql_mstore_count = Cot::$db->query("SELECT COUNT(*) FROM $db_mstore $wherecount_sql");
$mstore_count_all = $mstore_count = $sql_mstore_count->fetchColumn();

$sqllist = Cot::$db->query("SELECT * FROM $db_mstore AS m
    $where_sql
    $order_sql
    LIMIT $d, " . Cot::$cfg['mstore']['cat___default']['mstoremaxlistsperpage']);

foreach ($sql_mstore_count_cat as $value) {
    $t1->assign([
        'MSTORE_CAT_ROW_TITLE' => Cot::$structure['mstore'][$value['msitem_cat']]['title'] ?? '',
        'MSTORE_CAT_ROW_ICON' => Cot::$structure['mstore'][$value['msitem_cat']]['icon'] ?? '',
        'MSTORE_CAT_ROW_URL' => cot_url('users', ['m' => 'details', 'id' => $urr['user_id'], 'u' => $urr['user_name'], 'tab' => 'mstore', 'cat' => $value['msitem_cat']]),
        'MSTORE_CAT_ROW_COUNT_MSTORE' => $value['cat_count'],
        'MSTORE_CAT_ROW_SELECT' => ($category && $category == $value['msitem_cat']) ? 1 : '',
    ]);
    $t1->parse('MAIN.CAT_ROW');
}

$opt_array = [
    'm' => 'details',
    'id' => $urr['user_id'],
    'u' => $urr['user_name'],
    'tab' => 'mstore',
];
if ($category) {
    $mstore_count = $sql_mstore_count_cat[array_search($category, array_column($sql_mstore_count_cat, 'msitem_cat'))]['cat_count'] ?? $mstore_count;
    $opt_array['cat'] = $category;
}

$pagenav = cot_pagenav('users', $opt_array, $d, $mstore_count, Cot::$cfg['mstore']['cat___default']['mstoremaxlistsperpage'], 'dmstore');

$t1->assign([
    'PAGENAV_PAGES' => $pagenav['main'],
    'PAGENAV_PREV' => $pagenav['prev'],
    'PAGENAV_NEXT' => $pagenav['next'],
    'PAGENAV_COUNT' => $mstore_count,
]);

$sqllist_rowset = $sqllist->fetchAll();
$sqllist_idset = [];

foreach ($sqllist_rowset as $item) {
    $sqllist_idset[$item['msitem_id']] = $item['msitem_alias'];
}

/* === Hook === */
$extp = cot_getextplugins('mstore.userdetails.loop');
/* ===== */

foreach ($sqllist_rowset as $item) {
    $mstoreTags = cot_generate_mstoretags(
        $item,
        'MSTORE_ROW_',
        Cot::$cfg['mstore']['mstoretruncatetext'] ?? 0,
        Cot::$usr['isadmin'],
        Cot::$cfg['homebreadcrumb']
    );

    if (!empty($mstoreTags['MSTORE_ROW_ADMIN_DELETE_URL'])) {
        $urlParams = $opt_array;
        if ($durl > 0) {
            $urlParams['dmstore'] = $durl;
        }
        $deleteUrl = cot_url(
            'mstore',
            [
                'm' => 'edit',
                'a' => 'update',
                'delete' => '1',
                'id' => $item['msitem_id'],
                'x' => Cot::$sys['xk'],
                'redirect' => base64_encode(cot_url('users', $urlParams, '', true)),
            ]
        );
        $deleteConfirmUrl = cot_confirm_url($deleteUrl, 'mstore');
        $mstoreTags['MSTORE_ROW_ADMIN_DELETE'] = cot_rc_link(
            $deleteConfirmUrl,
            Cot::$L['Delete'],
            'class="confirmLink"'
        );
        $mstoreTags['MSTORE_ROW_ADMIN_DELETE_URL'] = $deleteConfirmUrl;
    }

    $t1->assign($mstoreTags);

    /* === Hook === */
    foreach ($extp as $pl) {
        include $pl;
    }
    /* ===== */

    $t1->parse('MAIN.MSTORE_ROWS');
}

/* === Hook === */
foreach (cot_getextplugins('mstore.userdetails.tags') as $pl) {
    include $pl;
}
/* ===== */

Cot::$sys['noindex'] = false; // Убираем noindex для вкладки товаров

$t1->parse('MAIN');

$t->assign([
    'USERS_DETAILS_MSTORE_COUNT' => $mstore_count_all,
    'USERS_DETAILS_MSTORE_URL' => cot_url('users', ['m' => 'details', 'id' => $urr['user_id'], 'u' => $urr['user_name'], 'tab' => 'mstore']),
]);

$t->assign('MSTORE', $t1->text('MAIN'));
