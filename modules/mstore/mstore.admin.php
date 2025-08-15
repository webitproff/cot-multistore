<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=admin
[END_COT_EXT]
==================== */

/**
 * Mstore manager & Queue of store items
 *
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */

use cot\modules\mstore\inc\MstoreDictionary;
use cot\modules\mstore\inc\MstoreControlService;

(defined('COT_CODE') && defined('COT_ADMIN')) or die('Wrong URL.');

list(Cot::$usr['auth_read'], Cot::$usr['auth_write'], Cot::$usr['isadmin']) = cot_auth('mstore', 'any');
cot_block(Cot::$usr['isadmin']);

$t = new XTemplate(cot_tplfile('mstore.admin', 'module', true));

require_once cot_incfile('mstore', 'module');

$adminPath[] = [cot_url('admin', 'm=extensions'), Cot::$L['Extensions']];
$adminPath[] = [cot_url('admin', 'm=extensions&a=details&mod='.$m), $cot_modules[$m]['title']];
$adminPath[] = [cot_url('admin', 'm='.$m), Cot::$L['Administration']];
$adminHelp = Cot::$L['adm_help_mstore'];
$adminTitle = Cot::$L['mstore_Mstore'];

$id = cot_import('id', 'G', 'INT');

list($pg, $d, $durl) = cot_import_pagenav('d', Cot::$cfg['mstore']['mstoremaxlistsperpage']);

$sorttype = cot_import('sorttype', 'R', 'ALP');
$sorttype = empty($sorttype) ? 'id' : $sorttype;
if ($sorttype != 'id' && !Cot::$db->fieldExists(Cot::$db->mstore, "msitem_$sorttype")) {
	$sorttype = 'id';
}
$sqlsorttype = 'msitem_'.$sorttype;

$sort_type = cot_mstore_config_order(true);

$sortway = cot_import('sortway', 'R', 'ALP');
$sortway = empty($sortway) ? 'desc' : $sortway;
$sort_way = [
	'asc' => Cot::$L['Ascending'],
	'desc' => Cot::$L['Descending'],
];
$sqlsortway = $sortway;

$filter = cot_import('filter', 'R', 'ALP');
$filter = empty($filter) ? 'valqueue' : $filter;
$filter_type = [
	'all' => Cot::$L['All'],
	'valqueue' => Cot::$L['adm_valqueue'],
	'validated' => Cot::$L['adm_validated'],
	'expired' => Cot::$L['adm_expired'],
	'drafts' => Cot::$L['mstore_drafts'],
];

$urlParams = ['m' => 'mstore'];
if ($sorttype != 'id') {
    $urlParams['sorttype'] = $sorttype;
}
if ($sortway != 'desc') {
    $urlParams['sortway'] = $sortway;
}
if ($filter != 'valqueue') {
    $urlParams['filter'] = $filter;
}

/**
 * Common UrlParams without pagination
 * @deprecated
 */
$common_params = http_build_query($urlParams, '', '&');

if ($pg > 1) {
    $urlParams['d'] = $durl;
}

if ($filter == 'all') {
    $sqlwhere = "msitem_title IS NOT NULL AND msitem_title != ''";
} elseif ($filter == 'valqueue') {
    $sqlwhere = 'msitem_state = ' . MstoreDictionary::STATE_PENDING . " AND msitem_title IS NOT NULL AND msitem_title != ''";
} elseif ($filter == 'validated') {
    $sqlwhere = 'msitem_state = ' . MstoreDictionary::STATE_PUBLISHED . " AND msitem_title IS NOT NULL AND msitem_title != ''";
} elseif ($filter == 'drafts') {
    $sqlwhere = 'msitem_state = ' . MstoreDictionary::STATE_DRAFT . " AND msitem_title IS NOT NULL AND msitem_title != ''";
}


$catsub = cot_structure_children('mstore', '');
if (count($catsub) < count(Cot::$structure['mstore'])) {
    $sqlwhere .= " AND msitem_cat IN ('" . implode("','", $catsub) . "')";
}

// Загружаем структуру категорий перед циклом
//Cot::$structure['mstore'] = cot_build_structure_mstore_tree('mstore');
cot::$structure['mstore'] = (!empty(cot::$structure['mstore']) && is_array(cot::$structure['mstore'])) ?
    cot::$structure['mstore'] : [];
$backUrl = cot_import('back', 'G', 'HTM');
$backUrl = !empty($backUrl) ? base64_decode($backUrl) : cot_url('admin', $urlParams, '', true);

/* === Hook  === */
foreach (cot_getextplugins('mstore.admin.first') as $pl) {
	include $pl;
}
/* ===== */

if ($a == 'validate') {
	cot_check_xg();

	/* === Hook  === */
	foreach (cot_getextplugins('mstore.admin.validate') as $pl) {
		include $pl;
	}
	/* ===== */

    $row = Cot::$db->query(
        'SELECT msitem_id, msitem_alias, msitem_cat, msitem_state FROM ' . Cot::$db->mstore
        . ' WHERE msitem_id = ?',
        $id
    )->fetch();
	if ($row) {
        if ($row['msitem_state'] == MstoreDictionary::STATE_PUBLISHED) {
            cot_message('#' . $id . ' - ' . Cot::$L['adm_already_updated']);
            cot_redirect($backUrl);
        }

		$usr['isadmin_local'] = cot_auth('mstore', $row['msitem_cat'], 'A');
		cot_block($usr['isadmin_local']);
        $data = ['msitem_state' => MstoreDictionary::STATE_PUBLISHED];

		$sql_mstore = Cot::$db->update(Cot::$db->mstore, $data, "msitem_id = $id");

		/* === Hook  === */
		foreach (cot_getextplugins('mstore.admin.validate.done') as $pl) {
			include $pl;
		}
		/* ===== */

		cot_log(
            Cot::$L['Mstore'] . ' #' . $id . ' - ' . Cot::$L['adm_queue_validated'],
            'mstore',
            'validate',
            'done'
        );

		if (Cot::$cache) {
            Cot::$cache->db->remove('structure', 'system');
			if (Cot::$cfg['cache_mstore']) {
                Cot::$cache->static->clearByUri(cot_mstore_url($row));
                Cot::$cache->static->clearByUri(cot_url('mstore', ['c' => $row['msitem_cat']]));
			}
			if (Cot::$cfg['cache_index']) {
                Cot::$cache->static->clear('index');
			}
		}
		cot_message('#' . $id . ' - ' . Cot::$L['adm_queue_validated']);

	} else {
        cot_error('#' . $id . ' - ' . Cot::$L['nf']);
	}

    cot_redirect($backUrl);
} elseif ($a == 'unvalidate') {
	cot_check_xg();

	/* === Hook  === */
	foreach (cot_getextplugins('mstore.admin.unvalidate') as $pl) {
		include $pl;
	}
	/* ===== */

    $row = Cot::$db->query(
        'SELECT msitem_id, msitem_alias, msitem_cat, msitem_state FROM ' . Cot::$db->mstore . ' WHERE msitem_id = ?',
        $id
    )->fetch();
    if ($row) {
        if ($row['msitem_state'] == MstoreDictionary::STATE_PENDING) {
            cot_message('#' . $id . ' - ' . Cot::$L['adm_already_updated']);
            cot_redirect($backUrl);
        }

		Cot::$usr['isadmin_local'] = cot_auth('mstore', $row['msitem_cat'], 'A');
		cot_block($usr['isadmin_local']);

		$sql_mstore = Cot::$db->update(
            Cot::$db->mstore,
            ['msitem_state' => MstoreDictionary::STATE_PENDING],
            'msitem_id = ?',
            $id
        );

		cot_log(Cot::$L['Mstore'] . ' #' . $id . ' - ' . Cot::$L['adm_queue_unvalidated'], 'mstore', 'unvalidated', 'done');

		if (Cot::$cache) {
            Cot::$cache->db->remove('structure', 'system');
			if (Cot::$cfg['cache_mstore']) {
                Cot::$cache->static->clearByUri(cot_mstore_url($row));
                Cot::$cache->static->clearByUri(cot_url('mstore', ['c' => $row['msitem_cat']]));
			}
			if (Cot::$cfg['cache_index']) {
                Cot::$cache->static->clear('index');
			}
		}

		cot_message('#' . $id . ' - ' . Cot::$L['adm_queue_unvalidated']);

    } else {
        cot_error('#' . $id . ' - ' . Cot::$L['nf']);
	}

    cot_redirect($backUrl);
} elseif ($a == 'delete') {
	cot_check_xg();

	/* === Hook  === */
	foreach (cot_getextplugins('mstore.admin.delete') as $pl) {
		include $pl;
	}
	/* ===== */

    $resultOrMessage = MstoreControlService::getInstance()->delete($id);
    if ($resultOrMessage !== false) {
        /* === Hook === */
		foreach (cot_getextplugins('mstore.admin.delete.done') as $pl) {
			include $pl;
		}
		/* ===== */

        cot_message('#' . $id . ' - ' . $resultOrMessage);
    } else {
        cot_error('#' . $id . ' - ' . Cot::$L['adm_failed']);
    }

    cot_redirect(cot_url('admin', $urlParams, '', true));
} elseif ($a == 'update_checked') {
	$paction = cot_import('paction', 'P', 'TXT');
	$s = cot_import('s', 'P', 'ARR');

	if ($paction == 'validate' && is_array($s)) {
		cot_check_xp();

		$perelik = '';
		$notfoundet = '';
		foreach ($s as $i => $k) {
			if ($s[$i] == '1' || $s[$i] == 'on') {
				/* === Hook  === */
				foreach (cot_getextplugins('mstore.admin.checked_validate') as $pl) {
					include $pl;
				}
				/* ===== */

				$sql_mstore = Cot::$db->query('SELECT * FROM ' . Cot::$db->mstore . ' WHERE msitem_id = ?', $i);
				if ($row = $sql_mstore->fetch()) {
					$id = $row['msitem_id'];
					$usr['isadmin_local'] = cot_auth('mstore', $row['msitem_cat'], 'A');
					cot_block($usr['isadmin_local']);

					$sql_mstore = Cot::$db->update(
                        Cot::$db->mstore,
                        ['msitem_state' => MstoreDictionary::STATE_PUBLISHED],
                        'msitem_id= ?',
                        $id
                    );

					cot_log(
                        Cot::$L['Mstore'] . ' #' . $id . ' - ' . Cot::$L['adm_queue_validated'],
                        'mstore',
                        'validate',
                        'done'
                    );

					if (Cot::$cache && Cot::$cfg['cache_mstore']) {
                        Cot::$cache->static->clearByUri(cot_mstore_url($row));
                        Cot::$cache->static->clearByUri(cot_url('mstore', ['c' => $row['msitem_cat']]));
					}

					$perelik .= '#' . $id . ', ';
				} else {
					$notfoundet .= '#' . $id . ' - ' . Cot::$L['Error'] . '<br  />';
				}
			}
		}

        if (Cot::$cache) {
            Cot::$cache->db->remove('structure', 'system');
            if (Cot::$cfg['cache_index']) {
                Cot::$cache->static->clear('index');
            }
        }

        if (!empty($notfoundet)) {
            cot_error($notfoundet);
        }

		if (!empty($perelik)) {
			cot_message($perelik . ' - ' . Cot::$L['adm_queue_validated']);
		}

        cot_redirect(cot_url('admin', $urlParams, '', true));
	} elseif ($paction == 'delete' && is_array($s)) {
		cot_check_xp();

		$perelik = '';
		$notfoundet = '';
        $mstoreService = MstoreControlService::getInstance();
		foreach ($s as $id => $k) {
			if ($s[$id] == '1' || $s[$id] == 'on') {

				/* === Hook  === */
				foreach (cot_getextplugins('mstore.admin.checked_delete') as $pl) {
					include $pl;
				}
				/* ===== */

                $resultOrMessage = $mstoreService->delete((int) $id);
                if ($resultOrMessage !== false) {
                    /* === Hook === */
                    foreach (cot_getextplugins('mstore.admin.delete.done') as $pl) {
                        include $pl;
                    }
                    /* ===== */
                    if ($perelik !== '') {
                        $perelik .= ', ';
                    }
                    $perelik .= '#' . $id;
                } else {
                    $notfoundet .= '#'. $id . ' - ' . Cot::$L['Error'] . '<br  />';
                }
			}
		}

        if (!empty($notfoundet)) {
            cot_error($notfoundet);
        }

        if (!empty($perelik)) {
            cot_message($perelik . ' - ' . Cot::$L['mstore_deleted']);
        }

        cot_redirect(cot_url('admin', $urlParams, '', true));
	}
}

$totalitems = Cot::$db->query('SELECT COUNT(*) FROM ' . Cot::$db->mstore . ' WHERE ' . $sqlwhere)->fetchColumn();
$pagenav = cot_pagenav(
	'admin',
	$common_params,
	$d,
	$totalitems,
	Cot::$cfg['mstore']['mstoremaxlistsperpage'],
	'd',
	'',
	Cot::$cfg['jquery'] && Cot::$cfg['turnajax']
);

$sql_mstore = Cot::$db->query("SELECT p.*, u.user_name
	FROM $db_mstore as p
	LEFT JOIN $db_users AS u ON u.user_id = p.msitem_ownerid
	WHERE $sqlwhere
		ORDER BY $sqlsorttype $sqlsortway
		LIMIT $d, ".Cot::$cfg['mstore']['mstoremaxlistsperpage']);

$ii = 0;


/* === Hook - Part1 : Set === */
$extp = cot_getextplugins('mstore.admin.loop');
/* ===== */

foreach ($sql_mstore->fetchAll() as $row) {
    $sub_count = 0;
    if (isset(Cot::$structure['mstore'][$row["msitem_cat"]])) {
        $sql_mstore_subcount = Cot::$db->query("SELECT SUM(structure_count) FROM $db_structure WHERE structure_path LIKE '" . Cot::$db->prep(Cot::$structure['mstore'][$row["msitem_cat"]]['rpath']) . "%'");
        $sub_count = $sql_mstore_subcount->fetchColumn();
    }

    $categoryPath = isset(Cot::$structure['mstore'][$row['msitem_cat']]['path']) ? Cot::$structure['mstore'][$row['msitem_cat']]['path'] : (isset(Cot::$structure['mstore'][$row['msitem_cat']]['code']) ? Cot::$structure['mstore'][$row['msitem_cat']]['code'] : $row['msitem_cat']);
    $urlParams = ['c' => $categoryPath];
    if (!empty($row['msitem_alias'])) {
        $urlParams['al'] = $row['msitem_alias'];
    } else {
        $urlParams['id'] = $row['msitem_id'];
    }
    $row['item_pageurl'] = cot_url('mstore', $urlParams);
    $t->assign(cot_generate_mstoretags($row, 'ADMIN_MSTORE_', 200));
    $t->assign([
        'ADMIN_MSTORE_ID_URL' => $row['item_pageurl'],
        'ADMIN_MSTORE_OWNER' => cot_build_user($row['msitem_ownerid'], $row['user_name']),

        'ADMIN_MSTORE_URL_FOR_VALIDATED' => cot_confirm_url(cot_url('admin', $common_params . '&a=validate&id=' . $row['msitem_id'] . '&d=' . $durl . '&' . cot_xg()), 'mstore', 'mstore_confirm_validate'),
        'ADMIN_MSTORE_URL_FOR_UNVALIDATE' => cot_confirm_url(cot_url('admin', $common_params . '&a=unvalidate&id=' . $row['msitem_id'] . '&d=' . $durl . '&' . cot_xg()), 'mstore', 'mstore_confirm_unvalidate'),
        'ADMIN_MSTORE_URL_FOR_DELETED' => cot_confirm_url(cot_url('admin', $common_params . '&a=delete&id=' . $row['msitem_id'] . '&d=' . $durl . '&' . cot_xg()), 'mstore', 'mstore_confirm_delete'),
        'ADMIN_MSTORE_URL_FOR_EDIT' => cot_url('mstore', 'm=edit&id=' . $row['msitem_id']),
        'ADMIN_MSTORE_ODDEVEN' => cot_build_oddeven($ii),
        'ADMIN_MSTORE_CAT_COUNT' => $sub_count,
    ]);
    $t->assign(cot_generate_usertags($row['msitem_ownerid'], 'ADMIN_MSTORE_OWNER_'), htmlspecialchars($row['user_name'] ?? ''));
    foreach ($extp as $pl) {
        include $pl;
    }
    $t->parse('MAIN.MSTORE_ROW');
    $ii++;
}

$totaldbitems = Cot::$db->countRows($db_mstore);
$sql_mstore_queued = Cot::$db->query(
    'SELECT COUNT(*) FROM ' . Cot::$db->mstore . ' WHERE msitem_state = ' . MstoreDictionary::STATE_PENDING
);
$sys['mstorequeued'] = $sql_mstore_queued->fetchColumn();

$t->assign([
	'ADMIN_MSTORE_URL_CONFIG' => cot_url('admin', 'm=config&n=edit&o=module&p=mstore'),
	'ADMIN_MSTORE_URL_ADD' => cot_url('mstore', 'm=add'),
	'ADMIN_MSTORE_URL_EXTRAFIELDS' => cot_url('admin', 'm=extrafields&n=' . $db_mstore),
	'ADMIN_MSTORE_URL_STRUCTURE' => cot_url('admin', 'm=structure&n=mstore'),
	'ADMIN_MSTORE_FORM_URL' => cot_url('admin', $common_params.'&a=update_checked&d=' . $durl),
	'ADMIN_MSTORE_ORDER' => cot_selectbox($sorttype, 'sorttype', array_keys($sort_type), array_values($sort_type), false),
	'ADMIN_MSTORE_WAY' => cot_selectbox($sortway, 'sortway', array_keys($sort_way), array_values($sort_way), false),
	'ADMIN_MSTORE_FILTER' => cot_selectbox($filter, 'filter', array_keys($filter_type), array_values($filter_type), false),
	'ADMIN_MSTORE_TOTALDBITEMS' => $totaldbitems,
    'ADMIN_MSTORE_ON_PAGE' => $ii,
]);

$t->assign(cot_generatePaginationTags($pagenav));

cot_display_messages($t);

/* === Hook  === */
foreach (cot_getextplugins('mstore.admin.tags') as $pl) {
	include $pl;
}
/* ===== */

$t->parse('MAIN');
$adminMain = $t->text('MAIN');