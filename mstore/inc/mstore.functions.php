<?php
/**
 * Mstore API
 *
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */

use cot\extensions\ExtensionsDictionary;
use cot\extensions\ExtensionsService;
use cot\modules\mstore\inc\MstoreDictionary;
use cot\plugins\comments\inc\CommentsService;

defined('COT_CODE') or die('Wrong URL.');

// Requirements
require_once cot_langfile('mstore', ExtensionsDictionary::TYPE_MODULE);
require_once cot_incfile('mstore', ExtensionsDictionary::TYPE_MODULE, 'resources');
require_once cot_incfile('forms');
require_once cot_incfile('extrafields');


// Tables and extras
Cot::$db->registerTable('mstore');

cot_extrafields_register_table('mstore');

if (empty(Cot::$structure['mstore'])) {
    Cot::$structure['mstore'] = [];
}
/**
 * Формирует иерархическую структуру дерева категорий для модуля mstore
 *
 * @param string $parent Код родительской категории, пустой для корневого уровня
 * @param string|array $selected Код(ы) выбранной категории для подсветки (строка или массив)
 * @param int $level Текущий уровень в иерархии категорий
 * @param string $template Файл шаблона для использования (зарезервировано)
 * @return string|bool Отрендеренный HTML для дерева категорий или false, если нет дочерних элементов
 */
function cot_build_structure_mstore_tree($parent = '', $selected = '', $level = 0, $template = '')
{
    global $structure, $cfg, $db, $sys, $cot_extrafields, $db_structure, $db_mstore;
    global $i18n_notmain, $i18n_locale, $i18n_write, $i18n_admin, $i18n_read, $db_i18n_pages;

    $blacklist_cfg = $cfg['mstore']['mstoreblacktreecatspage'] ?? '';
	
    $blacklist = array_map('trim', explode(',', $blacklist_cfg));

    $urlparams = [];

    /* === Hook === */
    foreach (cot_getextplugins('mstore.tree.first') as $pl)
    {
        include $pl;
    }
    /* ===== */

    if (empty($parent))
    {
        $i18n_enabled = $i18n_read;
        $children = [];
        $allcat = cot_structure_children('mstore', '');
        foreach ($allcat as $x)
        {
            if (
                mb_substr_count($structure['mstore'][$x]['path'], ".") == 0 &&
                !in_array($x, $blacklist)
            ) {
                $children[] = $x;
            }
        }
    }
    else
    {
        $i18n_enabled = $i18n_read && cot_i18n_enabled($parent);
        $children = array_filter($structure['mstore'][$parent]['subcats'] ?? [], function($cat) use ($blacklist) {
            return !in_array($cat, $blacklist);
        });
    }

    $mskin = cot_tplfile(['mstore', 'tree', $template], 'module');
    $t1 = new XTemplate($mskin);

    /* === Hook === */
    foreach (cot_getextplugins('mstore.tree.main') as $pl)
    {
        include $pl;
    }
    /* ===== */

    if (count($children) == 0)
    {
        return false;
    }

    $total_count = 0;
    if ($db->tableExists($db_mstore)) {
        $result = $db->query("SELECT COUNT(*) AS total FROM $db_mstore WHERE msitem_state = 0")->fetch();
        $total_count = $result['total'] ?? 0;
    }

    $title = '';
    $desc = '';
    $count = 0;
    $icon = '';
    if (!empty($parent) && isset($structure['mstore'][$parent])) {
        $title = $structure['mstore'][$parent]['title'];
        $desc  = $structure['mstore'][$parent]['desc'];
        $count = $structure['mstore'][$parent]['count'];
        $icon  = $structure['mstore'][$parent]['icon'];
    }

    $t1->assign([
        "TITLE" => htmlspecialchars($title),
        "DESC" => $desc,
        "COUNT" => $count,
        "ICON" => $icon,
        "HREF" => cot_url("mstore", $urlparams + ['c' => $parent]),
        "LEVEL" => $level,
        "TOTAL_COUNT" => $total_count,
    ]);

    $jj = 0;

    /* === Hook - Part1 : Set === */
    $extp = cot_getextplugins('mstore.tree.loop');
    /* ===== */

    foreach ($children as $row)
    {
        if (in_array($row, $blacklist)) {
            continue;
        }

        $jj++;
        $urlparams['c'] = $row;
        $subcats = !empty($structure['mstore'][$row]['subcats']) ? array_filter($structure['mstore'][$row]['subcats'], function($cat) use ($blacklist) {
            return !in_array($cat, $blacklist);
        }) : [];

        $t1->assign([
            "ROW_ID" => $row,
            "ROW_TITLE" => htmlspecialchars($structure['mstore'][$row]['title']),
            "ROW_DESC" => $structure['mstore'][$row]['desc'],
            "ROW_COUNT" => $structure['mstore'][$row]['count'],
            "ROW_ICON" => $structure['mstore'][$row]['icon'],
            "ROW_HREF" => cot_url("mstore", $urlparams),
            "ROW_SELECTED" => ((is_array($selected) && in_array($row, $selected)) || (!is_array($selected) && $row == $selected)) ? 1 : 0,
            "ROW_SUBCAT" => !empty($subcats) ? cot_build_structure_mstore_tree($row, $selected, $level + 1) : '',
            "ROW_LEVEL" => $level,
            "ROW_ODDEVEN" => cot_build_oddeven($jj),
            "ROW_JJ" => $jj
        ]);

        foreach ($cot_extrafields[$db_structure] as $exfld)
        {
            $uname = strtoupper($exfld['field_name']);
            $t1->assign([
                'ROW_'.$uname.'_TITLE' => isset($L['structure_'.$exfld['field_name'].'_title']) ? $L['structure_'.$exfld['field_name'].'_title'] : $exfld['field_description'],
                'ROW_'.$uname => cot_build_extrafields_data('structure', $exfld, $structure['mstore'][$row][$exfld['field_name']]),
                'ROW_'.$uname.'_VALUE' => $structure['mstore'][$row][$exfld['field_name']],
            ]);
        }

        if ($i18n_enabled && $i18n_notmain){
            $x_i18n = cot_i18n_get_cat($row, $i18n_locale);
            if ($x_i18n){
                if(!$cfg['plugin']['i18n']['omitmain'] || $i18n_locale != $cfg['defaultlang']){
                    $urlparams['l'] = $i18n_locale;
                }
                $t1->assign([
                    'ROW_URL' => cot_url('mstore', $urlparams),
                    'ROW_TITLE' => $x_i18n['title'],
                    'ROW_DESC' => $x_i18n['desc'],
                ]);
            }
        }

        /* === Hook - Part2 : Include === */
        foreach ($extp as $pl)
        {
            include $pl;
        }
        /* ===== */

        $t1->parse("MAIN.CATS");
    }

    if ($jj == 0)
    {
        return false;
    }

    $t1->parse("MAIN");
    return $t1->text("MAIN");
}
/**
 * Cuts the store item after 'more' tag or after the first page (if multipage)
 *
 * @param string $html Store item body
 * @return string
 */
function cot_cut_more_mstore($html)
{
	$mpos = mb_strpos($html, '<!--more-->');
	if ($mpos === false) {
		$mpos = mb_strpos($html, '[more]');
	}
	if ($mpos === false) {
        if (preg_match('#<hr *class="more" */?>#', $html, $matches, PREG_OFFSET_CAPTURE)) {
            $mpos = $matches[0][1];
        }
	}
	if ($mpos !== false) {
		$html = mb_substr($html, 0, $mpos);
	}
	$mpos = mb_strpos($html, '[newpage]');
	if ($mpos !== false) {
		$html = mb_substr($html, 0, $mpos);
	}
	if (mb_strpos($html, '[title]')) {
		$html = preg_replace('#\[title\](.*?)\[/title\][\s\r\n]*(<br />)?#i', '', $html);
	}
	return $html;
}

/**
 * Reads raw data from file
 *
 * @param string $file File path
 * @return string
 */
function cot_readraw_mstore($file)
{
	return (mb_strpos($file, '..') === false && file_exists($file)) ? file_get_contents($file) : 'File not found : '.$file; // TODO need translate
}

/**
 * Returns all store item tags for coTemplate
 *
 * @param int|array $item_data Store item Info Array or ID
 * @param string $tag_prefix Prefix for tags
 * @param int $textLength Text truncate
 * @param bool $admin_rights Store item Admin Rights
 * @param bool $pagepath_home Add home link for store item path
 * @param string $emptytitle Store item title text if item does not exist
 * @param string $backUrl BackUrl for store item validate actions
 *
 * @return array|null
 * @global CotDB $db
 */
function cot_generate_mstoretags(
    $item_data,
    $tag_prefix = '',
    $textLength = 0,
    $admin_rights = null,
    $pagepath_home = false,
    $emptytitle = '',
    $backUrl = null
) {
    // $L, $Ls, $R are needed for hook includes
    global $L, $Ls, $R, $cfg;

	global $db, $cot_extrafields, $db_mstore, $usr, $sys, $cot_yesno, $structure, $db_structure;

	static $extp_first = null, $extp_main = null;
	static $mstore_auth = [];

	if (is_null($extp_first)) {
		$extp_first = cot_getextplugins('mstoretags.first');
		$extp_main = cot_getextplugins('mstoretags.main');
	}

	/* === Hook === */
	foreach ($extp_first as $pl) {
		include $pl;
	}
	/* ===== */

	if (!empty($item_data) && !is_array($item_data)) {
        $itemID = (int) $item_data;
        $item_data = null;
        if ($itemID > 0) {
            $sql = Cot::$db->query('SELECT * FROM ' . Cot::$db->mstore . ' WHERE msitem_id = ? LIMIT 1', $itemID);
            $item_data = $sql->fetch();
        }
	}

    if (empty($item_data)) {
        return null;
    }

	if ($item_data['msitem_id'] > 0 && !empty($item_data['msitem_title'])) {
		if (is_null($admin_rights)) {
			if (!isset($mstore_auth[$item_data['msitem_cat']])) {
				$mstore_auth[$item_data['msitem_cat']] = cot_auth('mstore', $item_data['msitem_cat'], 'RWA1');
			}
			$admin_rights = (bool) $mstore_auth[$item_data['msitem_cat']][2];
		}
		$pagepath = cot_structure_buildpath('mstore', $item_data['msitem_cat']);
		$catpath = cot_breadcrumbs($pagepath, $pagepath_home, false);
        $item_data['msitem_pageurl'] = cot_mstore_url($item_data);
		$pageLink = [[$item_data['msitem_pageurl'], $item_data['msitem_title']]];
		$breadcrumbs = cot_breadcrumbs(array_merge($pagepath, $pageLink), $pagepath_home);


		$date_format = 'datetime_medium';

		$text = cot_parse($item_data['msitem_text'], $cfg['mstore']['mstoremarkup'], $item_data['msitem_parser']);
		$text_cut = cot_cut_more_mstore($text);
		if ($textLength > 0 && mb_strlen($text_cut) > $textLength) {
			$text_cut = cot_string_truncate($text_cut, $textLength);
		}
		$cutted = mb_strlen($text) > mb_strlen($text_cut);

		$cat_url = cot_url('mstore', ['c' => $item_data['msitem_cat']]);

        $urlParams = [
            'm' => 'mstore',
            'a' => 'validate',
            'id' => $item_data['msitem_id'],
            'x' => Cot::$sys['xk'],
        ];
        if (!empty($backUrl)) {
            $urlParams['back'] = base64_encode($backUrl);
        }
		$validate_url = cot_url('admin', $urlParams);

        $urlParams['a'] = 'unvalidate';
		$unvalidate_url = cot_url('admin', $urlParams);

		$edit_url = cot_url('mstore', "m=edit&id={$item_data['msitem_id']}");
		$delete_url = cot_url('mstore', "m=edit&a=update&delete=1&id={$item_data['msitem_id']}&x={$sys['xk']}");

		$item_data['msitem_status'] = cot_mstore_status(
			$item_data['msitem_state'],
		);

        $catTitle = isset($structure['mstore'][$item_data['msitem_cat']]['title'])
            ? htmlspecialchars($structure['mstore'][$item_data['msitem_cat']]['title'])
            : '';
        $catDescription = isset($structure['mstore'][$item_data['msitem_cat']]['desc'])
            ? $structure['mstore'][$item_data['msitem_cat']]['desc']
            : '';
        $itemDescription = (isset($item_data['msitem_desc']) && $item_data['msitem_desc'] !== '')
            ? htmlspecialchars($item_data['msitem_desc'])
            : '';
        $temp_array = [
			'URL' => $item_data['msitem_pageurl'],
			'ID' => $item_data['msitem_id'],
			'TITLE' => htmlspecialchars($item_data['msitem_title'], ENT_COMPAT, 'UTF-8', false),
            'BREADCRUMBS' => $breadcrumbs,
			'BREADCRUMBS_ITEM' => cot_breadcrumbs(
				array_merge(
					[[cot_url('index'), Cot::$L['Main']]],
					[[cot_url('mstore'), Cot::$L['mstore_Mstore']]],
					cot_structure_buildpath('mstore', $item_data['msitem_cat']),
					[htmlspecialchars($item_data['msitem_title'], ENT_QUOTES, 'UTF-8')]
				),
				$pagepath_home,
				false
			),
			'ALIAS' => $item_data['msitem_alias'],
			'STATE' => $item_data['msitem_state'],
			'STATUS' => $item_data['msitem_status'],
			'LOCAL_STATUS' => $L['mstore_status_' . $item_data['msitem_status']],
			'CAT' => $item_data['msitem_cat'],
			'CAT_URL' => $cat_url,
			'CAT_TITLE' => $catTitle,
			'CAT_PATH' => $catpath,
			'CAT_PATH_SHORT' => cot_rc_link($cat_url, $catTitle),
			'CAT_DESCRIPTION' => $catDescription,
			'CAT_ICON' => !empty($structure['mstore'][$item_data['msitem_cat']]['icon'])
                ? cot_rc(
                    'img_structure_cat',
                    [
                        'icon' => $structure['mstore'][$item_data['msitem_cat']]['icon'],
                        'title' => $catTitle,
                        'desc' => htmlspecialchars($catDescription),
                    ]
                )
                : '',
            'CAT_ICON_SRC' => isset($structure['mstore'][$item_data['msitem_cat']]['icon'])
                ? $structure['mstore'][$item_data['msitem_cat']]['icon']
                : '',

			'DESCRIPTION' => $itemDescription,
			'TEXT' => $text,
			'TEXT_CUT' => $text_cut,
			'TEXT_IS_CUT' => $cutted,
			'DESCRIPTION_OR_TEXT' => $itemDescription !== '' ? $itemDescription : $text,
			'DESCRIPTION_OR_TEXT_CUT' => $itemDescription !== '' ? $itemDescription : $text_cut,
			'MORE' => ($cutted) ? cot_rc('list_more', ['page_url' => $item_data['msitem_pageurl']]) : '',
			'AUTHOR' => (isset($item_data['msitem_author']) && $item_data['msitem_author'] != '')
                ? htmlspecialchars($item_data['msitem_author'])
                : '',
			'OWNER_ID' => $item_data['msitem_ownerid'],
			'OWNER_NAME' => (isset($item_data['user_name']) && $item_data['user_name'] != '')
                ? htmlspecialchars($item_data['user_name'])
                : '',
            'CREATED' => cot_date($date_format, $item_data['msitem_date']),
			'UPDATED' => cot_date($date_format, $item_data['msitem_updated']),
            'CREATED_STAMP' => $item_data['msitem_date'],
			'UPDATED_STAMP' => $item_data['msitem_updated'],
			'HITS' => $item_data['msitem_count'],
            'ADMIN' => $admin_rights
                ? cot_rc('list_row_admin', ['unvalidate_url' => $unvalidate_url, 'edit_url' => $edit_url])
                : '',
		];

		// Admin tags
		if ($admin_rights) {
			$validate_confirm_url = cot_confirm_url($validate_url, 'mstore', 'mstore_confirm_validate');
			$unvalidate_confirm_url = cot_confirm_url($unvalidate_url, 'mstore', 'mstore_confirm_unvalidate');
			$delete_confirm_url = cot_confirm_url($delete_url, 'mstore', 'mstore_confirm_delete');
			$temp_array['ADMIN_EDIT'] = cot_rc_link($edit_url, Cot::$L['Edit']);
			$temp_array['ADMIN_EDIT_URL'] = $edit_url;
			$temp_array['ADMIN_UNVALIDATE'] = $item_data['msitem_state'] == MstoreDictionary::STATE_PENDING
                ? cot_rc_link($validate_confirm_url, Cot::$L['Validate'], 'class="confirmLink"')
                : cot_rc_link($unvalidate_confirm_url, Cot::$L['Putinvalidationqueue'], 'class="confirmLink"');
			$temp_array['ADMIN_UNVALIDATE_URL'] = $item_data['msitem_state'] == 1 ?
				$validate_confirm_url : $unvalidate_confirm_url;
			$temp_array['ADMIN_DELETE'] = cot_rc_link($delete_confirm_url, $L['Delete'], 'class="confirmLink"');
			$temp_array['ADMIN_DELETE_URL'] = $delete_confirm_url;
		} elseif ($usr['id'] == $item_data['msitem_ownerid']) {
			$temp_array['ADMIN_EDIT'] = cot_rc_link($edit_url, $L['Edit']);
			$temp_array['ADMIN_EDIT_URL'] = $edit_url;
		}

		if (cot_auth('mstore', 'any', 'W')) {
			$clone_url = cot_url('mstore', "m=add&c={$item_data['msitem_cat']}&clone={$item_data['msitem_id']}");
			$temp_array['ADMIN_CLONE'] = cot_rc_link($clone_url, $L['mstore_clone']);
			$temp_array['ADMIN_CLONE_URL'] = $clone_url;
		}

		// Extrafields
        if (!empty(Cot::$extrafields[Cot::$db->mstore])) {
            foreach (Cot::$extrafields[Cot::$db->mstore] as $exfld) {
				$tag = mb_strtoupper($exfld['field_name']);
                $exfld_title = cot_extrafield_title($exfld, 'mstore_');

				$temp_array[$tag . '_TITLE'] = $exfld_title;
                $temp_value = null;
                if (isset($item_data['msitem_'.$exfld['field_name']])) {
                    $temp_value = $item_data['msitem_'.$exfld['field_name']];
                }
				$temp_array[$tag] = cot_build_extrafields_data('mstore', $exfld, $temp_value, $item_data['msitem_parser']);
				$temp_array[$tag . '_VALUE'] = $temp_value;
			}
		}

		// Extra fields for structure
		if (isset(Cot::$extrafields[Cot::$db->structure])) {
			foreach (Cot::$extrafields[Cot::$db->structure] as $exfld) {
				$tag = mb_strtoupper($exfld['field_name']);
                $exfld_title = cot_extrafield_title($exfld, 'structure_');

				$temp_array['CAT_' . $tag . '_TITLE'] = $exfld_title;
                $temp_value = null;
                if (isset(Cot::$structure['mstore'][$item_data['msitem_cat']][$exfld['field_name']])) {
                    $temp_value = Cot::$structure['mstore'][$item_data['msitem_cat']][$exfld['field_name']];
                }
				$temp_array['CAT_' . $tag] = cot_build_extrafields_data('structure', $exfld, $temp_value);
				$temp_array['CAT_' . $tag.'_VALUE'] = $temp_value;
			}
		}

		/* === Hook === */
		foreach ($extp_main as $pl) {
			include $pl;
		}
		/* ===== */

	} else {
		$temp_array = [
			'TITLE' => (!empty($emptytitle)) ? $emptytitle : Cot::$L['Deleted'],
		];
	}

	$return_array = [];
	foreach ($temp_array as $key => $val) {
		$return_array[$tag_prefix . $key] = $val;
	}

	return $return_array;
}

/**
 * Possible values for category sorting order
 * @param bool $adminpart Call from admin part
 * @return array
 */
function cot_mstore_config_order($adminpart = false)
{
	global $cot_extrafields, $L, $db_mstore;

	$options_sort = [
		'id' => $L['Id'],
		'title' => $L['Title'],
		'desc' => $L['Description'],
		'text' => $L['Body'],
		'ownerid' => $L['Owner'],
		'date' => $L['Date'],
		'count' => $L['Count'],
		'updated' => $L['Updated'],
		'cat' => $L['Category']
	];

	foreach($cot_extrafields[$db_mstore] as $exfld) {
		$options_sort[$exfld['field_name']] = isset($L['mstore_'.$exfld['field_name'].'_title']) ? $L['mstore_'.$exfld['field_name'].'_title'] : $exfld['field_description'];
	}

	if ($adminpart || version_compare('0.9.19', Cot::$cfg['version']) < 1) {
		return $options_sort;
	} else {
		// old style trick, will be removed in next versions
		$L['cfg_order_params'] = array_values($options_sort);
		return array_keys($options_sort);
	}
}
/**
 * Determines store item status
 *
 * @param int $msitem_state
 * @return string 'draft', 'pending' or 'published'
 */
function cot_mstore_status($msitem_state)
{
	if ($msitem_state == 0) {
		return 'published';
	} elseif ($msitem_state == 2) {
		return 'draft';
	}
	return 'pending';
}

/**
 * Returns store item category counter
 * Used in Admin/Structure/Resync All
 *
 * @param string $category Category code
 * @return int
 */
function cot_mstore_sync($category)
{
    if (empty($category)) {
        return 0;
    }

    return (int) Cot::$db->query(
        'SELECT COUNT(*) FROM ' . Cot::$db->quoteTableName(Cot::$db->mstore) .
        ' WHERE msitem_cat=?',
        $category
    )->fetchColumn();
}

/**
 * Recalculate and update structure counters
 * @param string $category Category code
 * @return void
 */
function cot_mstore_updateStructureCounters($category)
{
    if (empty($category) || empty(Cot::$structure['mstore'][$category])) {
        return;
    }

    $count = cot_mstore_sync($category);

    Cot::$db->query('UPDATE ' . Cot::$db->quoteTableName(Cot::$db->structure) .
        ' SET structure_count = ' . $count .
        " WHERE structure_area='mstore' AND structure_code = :category", ['category' => $category]);

    if (Cot::$cache) {
        Cot::$cache->db->remove('structure', 'system');
    }
}

/**
 * Update store item category code
 *
 * @param string $oldcat Old Cat code
 * @param string $newcat New Cat code
 * @return bool
 * @global CotDB $db
 */
function cot_mstore_updatecat($oldcat, $newcat)
{
	global $db, $db_structure, $db_mstore;
	return (bool) $db->update($db_mstore, ["msitem_cat" => $newcat], "msitem_cat='".$db->prep($oldcat)."'");
}


/**
 * Url address of the store item
 *
 * @param array $data Store item data as array
 * @param array $params Additional URL Parameters
 * @param string $tail URL postfix, e.g. anchor
 * @param bool $htmlspecialcharsBypass If TRUE, will not convert & to & and so on.
 * @param bool $ignoreAppendix If TRUE, $cot_url_appendix will be ignored for this URL
 * @return string Valid HTTP URL
 */
function cot_mstore_url($data, $params = [], $tail = '', $htmlspecialcharsBypass = false, $ignoreAppendix = false)
{
    $urlParams = ['c' => $data['msitem_cat']];
    if (!empty($data['msitem_alias'])) {
        $urlParams['al'] = $data['msitem_alias'];
    } elseif (!empty($data['msitem_id'])) {
        $id = (int) $data['msitem_id'];
        if ($id <= 0) {
            return '';
        }
        $urlParams['id'] = $id;
    } else {
        return '';
    }

    if (!empty($params)) {
        $urlParams = array_merge($urlParams, $params);
    }

    return cot_url('mstore', $urlParams, $tail, $htmlspecialcharsBypass, $ignoreAppendix);
}

/**
 * Returns permissions for a store item category.
 * @param  string $cat Category code
 * @return array       Permissions array with keys: 'auth_read', 'auth_write', 'isadmin', 'auth_download'
 */
function cot_mstore_auth($cat = null)
{
	if (empty($cat)) {
		$cat = 'any';
	}
	$auth = [];
	[$auth['auth_read'], $auth['auth_write'], $auth['isadmin'], $auth['auth_download']] = cot_auth('mstore', $cat, 'RWA1');
	return $auth;
}

/**
 * Imports store item data from request parameters.
 * @param string $source Source request method for parameters
 * @param array $ritem  Existing store item data from database
 * @param array $auth   Permissions array
 * @return array Store item data
 */
function cot_mstore_import($source = 'POST', $ritem = [], $auth = [])
{
	global $cfg, $db_mstore, $cot_extrafields, $usr, $sys;

	if (count($auth) == 0) {
		$auth = cot_mstore_auth($ritem['msitem_cat']);
	}

	if ($source == 'D' || $source == 'DIRECT') {
		// A trick so we don't have to affect every line below
		global $_PATCH;
		$_PATCH = $ritem;
		$source = 'PATCH';
	}

	$ritem['msitem_cat']      = cot_import('rmsitemcat', $source, 'TXT', 255);
	$ritem['msitem_alias']    = cot_import('rmsitemalias', $source, 'TXT', 255);
	$ritem['msitem_title']    = cot_import('rmsitemtitle', $source, 'TXT', 255);
	$ritem['msitem_desc']     = cot_import('rmsitemdesc', $source, 'TXT', 255);
	$ritem['msitem_text']     = cot_import('rmsitemtext', $source, 'HTM');
	$ritem['msitem_parser']   = cot_import('rmsitemparser', $source, 'ALP', 64);
	$ritem['msitem_author']   = cot_import('rmsitemauthor', $source, 'TXT', 100);


	$rmsitemdatenow           = cot_import('rmsitemdatenow', $source, 'BOL');
	$ritem['msitem_date']     = cot_import_date('rmsitemdate', true, false, $source);
	$ritem['msitem_date']     = ($rmsitemdatenow || is_null($ritem['msitem_date'])) ? $sys['now'] : (int) $ritem['msitem_date'];
	
	$ritem['msitem_updated']  = $sys['now'];

	$ritem['msitem_metatitle'] = cot_import('rmsitemmetatitle', $source, 'TXT', 255);
	$ritem['msitem_metadesc'] = cot_import('rmsitemmetadesc', $source, 'TXT', 255);

	$rmspublish               = cot_import('rmspublish', $source, 'ALP'); // For backwards compatibility
	$ritem['msitem_state']    = ($rmspublish == 'OK') ? 0 : cot_import('rmsitemstate', $source, 'INT');

	if ($auth['isadmin'] && isset($ritem['msitem_ownerid'])) {
		$ritem['msitem_count']     = cot_import('rmsitemcount', $source, 'INT');
		$ritem['msitem_ownerid']   = cot_import('rmsitemownerid', $source, 'INT');
	} else {
		$ritem['msitem_ownerid'] = Cot::$usr['id'];
	}

	$parser_list = cot_get_parsers();

	if (
        empty($ritem['msitem_parser'])
        || !in_array($ritem['msitem_parser'], $parser_list)
        || $ritem['msitem_parser'] != 'none'
        && !cot_auth('plug', $ritem['msitem_parser'], 'W')
    ) {
		$ritem['msitem_parser'] = isset(Cot::$sys['mstoreparser']) ? Cot::$sys['mstoreparser'] : Cot::$cfg['mstore']['mstoreparser'];
	}

	// Extra fields
    if (!empty(Cot::$extrafields[Cot::$db->mstore])) {
        foreach (Cot::$extrafields[Cot::$db->mstore] as $exfld) {
            $value = isset($ritem['msitem_' . $exfld['field_name']]) ? $ritem['msitem_' . $exfld['field_name']] : null ;
            $ritem['msitem_' . $exfld['field_name']] = cot_import_extrafields('rmsitem' . $exfld['field_name'], $exfld,
                $source, $value, 'mstore_');
        }
    }

	return $ritem;
}
/**
 * Validates store item data.
 * @param  array   $ritem Imported store item data
 * @return boolean        TRUE if validation is passed or FALSE if errors were found
 */
function cot_mstore_validate($ritem)
{
	global $structure;

	cot_check(empty($ritem['msitem_cat']), 'mstore_catmissing', 'rmsitemcat');
	if ($structure['mstore'][$ritem['msitem_cat']]['locked']) {
		global $L;
		require_once cot_langfile('message', 'core');
		cot_error('msg602_body', 'rmsitemcat');
	}
	cot_check(mb_strlen($ritem['msitem_title']) < 2, 'mstore_titletooshort', 'rmsitemtitle');

	cot_check(!empty($ritem['msitem_alias']) && preg_match('`[+/?%#&]`', $ritem['msitem_alias']), 'mstore_aliascharacters', 'rmsitemalias');

	$allowemptytext = Cot::$cfg['mstore']['cat_' . $ritem['msitem_cat']]['mstoreallowemptytext']
        ?? Cot::$cfg['mstore']['cat___default']['mstoreallowemptytext'];

	cot_check(!$allowemptytext && empty($ritem['msitem_text']), 'mstore_textmissing', 'rmsitemtext');

	return !cot_error_found();
}

/**
 * Adds a new store item to the CMS.
 * @param array $ritem Store item data
 * @param array $auth Permissions array
 * @return ?int New store item ID or NULL on error
 */
function cot_mstore_add(&$ritem, $auth = [])
{
    // $L, $Ls, $R are needed for hook includes
    global $L, $Ls, $R;

	if (cot_error_found()) {
		return false;
	}

	if (count($auth) == 0) {
		$auth = cot_mstore_auth($ritem['msitem_cat']);
	}

	if (!empty($ritem['msitem_alias'])) {
		$item_count = Cot::$db->query(
            'SELECT COUNT(*) FROM ' . Cot::$db->mstore . ' WHERE msitem_alias = ?',
            $ritem['msitem_alias']
        )->fetchColumn();
		if ($item_count > 0) {
			$ritem['msitem_alias'] = $ritem['msitem_alias'] . rand(1000, 9999);
		}
	}

	if (
        $ritem['msitem_state'] == MstoreDictionary::STATE_PUBLISHED
        && !($auth['isadmin'] && Cot::$cfg['mstore']['mstoreautovalidate'])
    ) {
        $ritem['msitem_state'] = MstoreDictionary::STATE_PENDING;
	}

	/* === Hook === */
	foreach (cot_getextplugins('mstore.add.add.query') as $pl) {
		include $pl;
	}
	/* ===== */

	if (Cot::$db->insert(Cot::$db->mstore, $ritem)) {
		$id = (int) Cot::$db->lastInsertId();
		cot_extrafield_movefiles();
        cot_mstore_updateStructureCounters($ritem['msitem_cat']);
	} else {
		$id = null;
	}

	/* === Hook === */
	foreach (cot_getextplugins('mstore.add.add.done') as $pl) {
		include $pl;
	}
	/* ===== */

	if ($ritem['msitem_state'] == MstoreDictionary::STATE_PUBLISHED && Cot::$cache) {
		if (Cot::$cfg['cache_mstore']) {
            Cot::$cache->static->clearByUri(cot_mstore_url($ritem));
            Cot::$cache->static->clearByUri(cot_url('mstore', ['c' => $ritem['msitem_cat']]));
		}
		if (Cot::$cfg['cache_index']) {
            Cot::$cache->static->clear('index');
		}
	}

	cot_shield_update(30, "r mstore");
	cot_log('Add store item #' . $id, 'mstore', 'add', 'done');

	return $id;
}

/**
 * Updates a store item in the CMS.
 * @param int $id Store item ID
 * @param array $ritem Store item data
 * @param array $auth  Permissions array
 * @return bool TRUE on success, FALSE on error
 */
function cot_mstore_update($id, &$ritem, $auth = [])
{
    // $L, $Ls, $R are needed for hook includes
    global $L, $Ls, $R;

    if (cot_error_found()) {
		return false;
	}

	if (count($auth) == 0) {
		$auth = cot_mstore_auth($ritem['msitem_cat']);
	}

	if (!empty($ritem['msitem_alias'])) {
		$item_count = Cot::$db->query('SELECT COUNT(*) FROM ' . Cot::$db->mstore .
            ' WHERE msitem_alias = ? AND msitem_id != ?', array($ritem['msitem_alias'], $id))->fetchColumn();
		if ($item_count > 0) {
			$ritem['msitem_alias'] = $ritem['msitem_alias'] . rand(1000, 9999);
		}
	}

	$row_item = Cot::$db->query('SELECT * FROM ' . Cot::$db->mstore . ' WHERE msitem_id = ?', $id)->fetch();

    if (
        $ritem['msitem_state'] == MstoreDictionary::STATE_PUBLISHED
        && !($auth['isadmin'] && Cot::$cfg['mstore']['mstoreautovalidate'])
    ) {
        $ritem['msitem_state'] = MstoreDictionary::STATE_PENDING;
    }

    Cot::$cache && Cot::$cache->db->remove('structure', 'system');

	if (!Cot::$db->update(Cot::$db->mstore, $ritem, 'msitem_id = ?', $id)) {
		return false;
	}
	cot_log("Edited store item #" . $id, 'mstore', 'edit', 'done');

	cot_extrafield_movefiles();

	/* === Hook === */
	foreach (cot_getextplugins('mstore.edit.update.done') as $pl) {
		include $pl;
	}
	/* ===== */

	if (
        ($ritem['msitem_state'] == MstoreDictionary::STATE_PUBLISHED  || $ritem['msitem_cat'] != $row_item['msitem_cat'])
        && Cot::$cache
    ) {
		if (Cot::$cfg['cache_mstore']) {
            Cot::$cache->static->clearByUri(cot_mstore_url($ritem));
            Cot::$cache->static->clearByUri(cot_url('mstore', ['c' => $ritem['msitem_cat']]));

			if ($ritem['msitem_cat'] != $row_item['msitem_cat']) {
                Cot::$cache->static->clearByUri(cot_mstore_url($row_item));
                Cot::$cache->static->clearByUri(cot_url('mstore', ['c' => $row_item['msitem_cat']]));
			}
		}
		if (Cot::$cfg['cache_index']) {
            Cot::$cache->static->clear('index');
		}
	}

	return true;
}

/**
 * Generates store item list widget
 * @param string|string[] $categories Custom parent categories code
 * @param int $count Number of items to show. 0 - all items
 * @param string $template Path for template file
 * @param string $order Sorting order (SQL)
 * @param string $condition Custom selection filter (SQL)
 * @param bool $active_only Custom parent category code
 * @param bool $use_subcat Include subcategories TRUE/FALSE
 * @param bool $exclude_current Exclude the current store item from the rowset for items.
 * @param string $blacklist Category black list, semicolon separated
 * @param string $pagination Pagination symbol
 * @param int $cache_ttl Cache lifetime in seconds, 0 disables cache
 * @return string Parsed HTML
 */
function cot_mstore_enum(
    $categories = '',
    $count = 0,
    $template = '',
    $order = '',
    $condition = '',
	$active_only = true,
    $use_subcat = true,
    $exclude_current = false,
    $blacklist = '',
    $pagination = '',
    $cache_ttl = null
) {
    // $L, $Ls, $R are needed for hook includes
    global $L, $Ls, $R;

	global $db, $db_mstore, $db_users, $structure, $cfg, $sys, $lang, $cache;

	// Compile lists
	if (!is_array($blacklist)) {
		$blacklist = str_replace(' ', '', $blacklist);
		$blacklist = (!empty($blacklist)) ? explode(',', $blacklist) : array();
	}

	// Get the cats
	if (!empty($categories)) {
		if (!is_array($categories)) {
			$categories = str_replace(' ', '', $categories);
			$categories = explode(',', $categories);
		}
		$categories = array_unique($categories);
		if ($use_subcat) {
			$total_categories = [];
			foreach ($categories as $cat) {
				$cats = cot_structure_children('mstore', $cat, $use_subcat);
				$total_categories = array_merge($total_categories, $cats);
			}
			$categories = array_unique($total_categories);
		}
		$categories = (count($blacklist) > 0 ) ? array_diff($categories, $blacklist) : $categories;
		$where['cat'] = "msitem_cat IN ('" . implode("','", $categories) . "')";
	} elseif (count($blacklist)) {
		$where['cat_black'] = "msitem_cat NOT IN ('" . implode("','", $blacklist) . "')";
	}

	$where['condition'] = $condition;

	if ($exclude_current && defined('COT_MSTORE') && !defined('COT_LIST')) {
		global $id;
        $tmp = 0;
        if (!empty($id)) {
            $tmp = (int) $id;
        }
		if (!empty($tmp)) {
            $where['msitem_id'] = "msitem_id != $tmp";
        }
	}
	if ($active_only) {
		$where['state'] = 'msitem_state = ' . MstoreDictionary::STATE_PUBLISHED;
		$where['date'] = "msitem_begin <= {$sys['now']} AND (msitem_expire = 0 OR msitem_expire > {$sys['now']})";
	}

	// Get pagination number if necessary
	if (!empty($pagination)) {
		[$pg, $d, $durl] = cot_import_pagenav($pagination, $count);
	} else {
		$d = 0;
	}

	// Display the items
	$mskin = (!empty($template) && file_exists($template)) ?
        $template : cot_tplfile(array('mstore', 'enum', $template), 'module');

    $cns_join_tables = '';
	$cns_join_columns = '';

	/* === Hook === */
	foreach (cot_getextplugins('mstore.enum.query') as $pl) {
		include $pl;
	}
	/* ===== */

    // Todo move it to comments plugin
	if (cot_plugin_active('comments')) {
		global $db_com;
		require_once cot_incfile('comments', 'plug');
		$cns_join_columns .= ", (SELECT COUNT(*) FROM `$db_com` WHERE com_area = 'mstore' AND com_code = p.msitem_id) AS com_count";
	}
	$sql_order = empty($order) ? 'ORDER BY msitem_date DESC' : "ORDER BY $order";
	$sql_limit = ($count > 0) ? "LIMIT $d, $count" : '';
	$where = array_filter($where);
	$where = ($where) ? 'WHERE ' . implode(' AND ', $where) : '';

	$sql_total = "SELECT COUNT(*) FROM $db_mstore AS p $cns_join_tables $where";
	$sql_query = "SELECT p.*, u.* $cns_join_columns FROM $db_mstore AS p LEFT JOIN $db_users AS u ON p.msitem_ownerid = u.user_id
			$cns_join_tables $where $sql_order $sql_limit";

	$t = new XTemplate($mskin);

	isset($md5hash) || $md5hash = 'mstore_enum_'.md5(str_replace($sys['now'], '_time_', $mskin.$lang.$sql_query));

	if ($cache && (int) $cache_ttl > 0) {
		$item_query_html = $cache->disk->get($md5hash, 'mstore', (int) $cache_ttl);

		if (!empty($item_query_html)) {
			return $item_query_html;
		}
	}

	$totalitems = $db->query($sql_total)->fetchColumn();
	$sql = $db->query($sql_query);

	$sql_rowset = $sql->fetchAll();
	$jj = 0;
	foreach ($sql_rowset as $item) {
		$jj++;
		$t->assign(cot_generate_mstoretags($item, 'MSTORE_ROW_', Cot::$cfg['mstore']['cat___default']['mstoretruncatetext']));

		$t->assign([
			'MSTORE_ROW_NUM' => $jj,
			'MSTORE_ROW_ODDEVEN' => cot_build_oddeven($jj),
			'MSTORE_ROW_RAW' => $item,
		]);

		$t->assign(cot_generate_usertags($item, 'MSTORE_ROW_OWNER_'));

		/* === Hook === */
		foreach (cot_getextplugins('mstore.enum.loop') as $pl) {
			include $pl;
		}
		/* ===== */

		if (cot_plugin_active('comments')) {
			$itemUrlParams = empty($item['msitem_alias'])
                ? ['c' => $item['msitem_cat'], 'id' => $item['msitem_id']]
                : ['c' => $item['msitem_cat'], 'al' => $item['msitem_alias']];
			$t->assign([
				'MSTORE_ROW_COMMENTS_LINK' => cot_commentsLink(
                    'mstore',
                    $itemUrlParams,
                    MstoreDictionary::SOURCE_MSTORE,
                    $item['msitem_id'],
                    $item['msitem_cat'],
                    $item
                ),
				'MSTORE_ROW_COMMENTS_COUNT' => CommentsService::getInstance()
                    ->getCount(MstoreDictionary::SOURCE_MSTORE, $item['msitem_id'], $item),
			]);
		}

		$t->parse("MAIN.MSTORE_ROW");
	}

	// Render pagination
	$url_params = $_GET;
    if (isset($url_params['rwr'])) {
        unset($url_params['rwr']);
    }
	$url_area = 'index';
    $extensionService = ExtensionsService::getInstance();
	$extensionCode = cot_import('e', 'G', 'ALP');
    if (!empty($extensionCode)) {
        if ($extensionService->isModuleActive($extensionCode)) {
            $url_area = $url_params['e'];
            unset($url_params['e']);
        } elseif ($extensionService->isPluginActive($extensionCode)) {
            $url_area = 'plug';
        }
    }
	unset($url_params[$pagination]);

    $pagenav = [
        'main' => null,
        'prev' => null,
        'next' => null,
        'first' => null,
        'last' => null,
        'current' => 1,
        'total' => 1,
    ];

	if (!empty($pagination)) {
		$pagenav = cot_pagenav($url_area, $url_params, $d, $totalitems, $count, $pagination);
	}

    $t->assign(cot_generatePaginationTags($pagenav));

	/* === Hook === */
	foreach (cot_getextplugins('mstore.enum.tags') as $pl) {
		include $pl;
	}
	/* ===== */

	$t->parse("MAIN");
	$item_query_html = $t->text("MAIN");

	if ($cache && (int) $cache_ttl > 0) {
		$cache->disk->store($md5hash, $item_query_html, 'mstore');
	}
	return $item_query_html;
}