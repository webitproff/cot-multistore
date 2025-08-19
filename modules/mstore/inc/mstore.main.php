<?php
/**
 * Store item display on CMF Cotonti Siena v.0.9.26 PHP 8.4.
 * filename mstore.main.php
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */

use cot\modules\mstore\inc\MstoreDictionary;

defined('COT_CODE') or die('Wrong URL');

list(Cot::$usr['auth_read'], Cot::$usr['auth_write'], Cot::$usr['isadmin']) = cot_auth('mstore', 'any');
cot_block(Cot::$usr['auth_read']);

$id = cot_import('id', 'G', 'INT');
$al = Cot::$db->prep(cot_import('al', 'G', 'TXT'));
$c = cot_import('c', 'G', 'TXT');
$pg = cot_import('pg', 'G', 'INT');

$join_columns = isset($join_columns) ? $join_columns : '';
$join_condition = isset($join_condition) ? $join_condition : '';

/* === Hook === */
foreach (cot_getextplugins('mstore.first') as $pl) {
	include $pl;
}
/* ===== */

if ($id > 0 || !empty($al)) {
	$where = (!empty($al)) ? "p.msitem_alias='".$al."'" : 'p.msitem_id='.$id;
	if (!empty($c)) {
        $where .= " AND p.msitem_cat = " . Cot::$db->quote($c);
    }
	$sql_item = Cot::$db->query("SELECT p.*, u.* $join_columns
		FROM $db_mstore AS p $join_condition
		LEFT JOIN $db_users AS u ON u.user_id=p.msitem_ownerid
		WHERE $where LIMIT 1");
}

if (!$id && empty($al) || !$sql_item || $sql_item->rowCount() == 0) {
	cot_die_message(404);
}
$item = $sql_item->fetch();

list(Cot::$usr['auth_read'], Cot::$usr['auth_write'], Cot::$usr['isadmin']) = cot_auth('mstore', $item['msitem_cat'], 'RWA1');
cot_block(Cot::$usr['auth_read']);

$al = empty($item['msitem_alias']) ? '' : $item['msitem_alias'];
$id = (int) $item['msitem_id'];
$cat = Cot::$structure['mstore'][$item['msitem_cat']];

$sys['sublocation'] = $item['msitem_title'];


$item['msitem_tab'] = empty($pg) ? 0 : $pg;

$urlParams = ['c' => $item['msitem_cat']];
if (!empty($al)) {
    $urlParams['al'] = $al;
} else {
    $urlParams['id'] = $id;
}
$item['msitem_pageurl'] = cot_url('mstore', $urlParams, '', true);

if (
    (
        $item['msitem_state'] == MstoreDictionary::STATE_PENDING
        || $item['msitem_state'] == MstoreDictionary::STATE_DRAFT
    )
    && (!Cot::$usr['isadmin'] && Cot::$usr['id'] != $item['msitem_ownerid'])
) {
    cot_log("Attempt to directly access an un-validated store item", 'sec', 'mstore', 'error');
    cot_die_message(403, TRUE);
}




$itemHasMessages = cot_check_messages();

$itemStaticCacheEnabled = Cot::$cache
    && Cot::$usr['id'] === 0
    && Cot::$cfg['cache_mstore']
    && !$itemHasMessages
    && (!isset(Cot::$cfg['cache_mstore_blacklist']) || !in_array($item['msitem_cat'], Cot::$cfg['cache_mstore_blacklist']));

// Store item views counter
if (!Cot::$usr['isadmin'] || Cot::$cfg['mstore']['mstorecount_admin']) {
    if (!$itemStaticCacheEnabled) {
        $item['msitem_count']++;
        Cot::$db->update(
            Cot::$db->mstore,
            ['msitem_count' => $item['msitem_count']],
            'msitem_id = ?',
            $item['msitem_id']
        );
    } else {
        Resources::embedFooter(
            'fetch("' . cot_url(
                'mstore',
                ['e' => 'mstore', 'm' => 'counter', 'a' => 'views', 'id' => $item['msitem_id']],
                '',
                true
            ) . '")'
        );
    }
}

if ($item['msitem_cat'] == 'system') {
    Cot::$out['subtitle'] = empty($item['msitem_metatitle']) ? $item['msitem_title'] : $item['msitem_metatitle'];
} else {
	$title_params = array(
		'TITLE' => empty($item['msitem_metatitle']) ? $item['msitem_title'] : $item['msitem_metatitle'],
		'CATEGORY' => $cat['title']
	);
    Cot::$out['subtitle'] = cot_title(Cot::$cfg['mstore']['mstoretitle_page'], $title_params);
}
Cot::$out['desc'] = empty($item['msitem_metadesc']) ? strip_tags($item['msitem_desc']) : strip_tags($item['msitem_metadesc']);
Cot::$out['keywords'] = !empty($item['msitem_keywords']) ? strip_tags($item['msitem_keywords']) : '';

// Building the canonical URL
$itemurl_params = array('c' => $item['msitem_cat']);
empty($al) ? $itemurl_params['id'] = $id : $itemurl_params['al'] = $al;
if ($pg > 0) {
	$itemurl_params['pg'] = $pg;
}
Cot::$out['canonical_uri'] = cot_url('mstore', $itemurl_params);

$mskin = cot_tplfile(array('mstore', $cat['tpl']));

Cot::$env['last_modified'] = $item['msitem_updated'];
Cot::$sys['noindex'] = false;
Cot::$R['code_noindex'] = '';
/* === Hook === */
foreach (cot_getextplugins('mstore.main') as $pl) {
	include $pl;
}
/* ============ */



require_once cot_incfile('users', 'module');
$t = new XTemplate($mskin);

$t->assign(
    cot_generate_mstoretags(
        $item,
        'MSTORE_',
        0,
        Cot::$usr['isadmin'],
        Cot::$cfg['homebreadcrumb'],
        '',
        $item['msitem_pageurl']
    )
);
$t->assign('MSTORE_OWNER', cot_build_user($item['msitem_ownerid'], $item['user_name']));
$t->assign(cot_generate_usertags($item, 'MSTORE_OWNER_'));


// Multi tabs
$item['msitem_tabs'] = explode('[newpage]', $t->vars['MSTORE_TEXT'], 99);
$item['msitem_totaltabs'] = count($item['msitem_tabs']);

if ($item['msitem_totaltabs'] > 1) {
	if (empty($item['msitem_tabs'][0])) {
		$remove = array_shift($item['msitem_tabs']);
		$item['msitem_totaltabs']--;
	}
	$max_tab = $item['msitem_totaltabs'] - 1;
	$item['msitem_tab'] = ($item['msitem_tab'] > $max_tab) ? 0 : $item['msitem_tab'];
	$item['msitem_tabtitles'] = array();

	for ($i = 0; $i < $item['msitem_totaltabs']; $i++) {
		if (mb_strpos($item['msitem_tabs'][$i], '<br />') === 0) {
			$item['msitem_tabs'][$i] = mb_substr($item['msitem_tabs'][$i], 6);
		}

		$p1 = mb_strpos($item['msitem_tabs'][$i], '[title]');
		$p2 = mb_strpos($item['msitem_tabs'][$i], '[/title]');

		if ($p2 > $p1 && $p1 < 4) {
			$item['msitem_tabtitle'][$i] = mb_substr($item['msitem_tabs'][$i], $p1 + 7, ($p2 - $p1) - 7);
			if ($i == $item['msitem_tab']) {
				$item['msitem_tabs'][$i] = trim(str_replace('[title]'.$item['msitem_tabtitle'][$i].'[/title]', '', $item['msitem_tabs'][$i]));
			}
		} else {
			$item['msitem_tabtitle'][$i] = $i == 0 ? $item['msitem_title'] : Cot::$L['Mstore'] . ' ' . ($i + 1);
		}
		$tab_url = empty($al)
            ? cot_url('mstore', 'c='.$item['msitem_cat'].'&id='.$id.'&pg='.$i)
            : cot_url('mstore', 'c='.$item['msitem_cat'].'&al='.$al.'&pg='.$i);
		$item['msitem_tabtitles'][] .= cot_rc_link($tab_url, ($i+1).'. '.$item['msitem_tabtitle'][$i],
			array('class' => 'mstore_tabtitle'));
		$item['msitem_tabs'][$i] = str_replace('[newpage]', '', $item['msitem_tabs'][$i]);
		$item['msitem_tabs'][$i] = preg_replace('#^(<br />)+#', '', $item['msitem_tabs'][$i]);
		$item['msitem_tabs'][$i] = trim($item['msitem_tabs'][$i]);
	}

	$item['msitem_tabtitles'] = implode('<br />', $item['msitem_tabtitles']);
	$item['msitem_text'] = $item['msitem_tabs'][$item['msitem_tab']];

	// Temporarily disable easypagenav to allow 0-based numbers
	$tmp = Cot::$cfg['easypagenav'];
	Cot::$cfg['easypagenav'] = false;
	$pn = cot_pagenav('mstore', (empty($al) ? 'id='.$id : 'al='.$al), $item['msitem_tab'], $item['msitem_totaltabs'], 1, 'pg');
	$item['msitem_tabnav'] = $pn['main'];
	Cot::$cfg['easypagenav'] = $tmp;

	$t->assign([
		'MSTORE_MULTI_TABNAV' => $item['msitem_tabnav'],
		'MSTORE_MULTI_TABTITLES' => $item['msitem_tabtitles'],
		'MSTORE_MULTI_CURTAB' => $item['msitem_tab'] + 1,
		'MSTORE_MULTI_MAXTAB' => $item['msitem_totaltabs'],
		'MSTORE_TEXT' => $item['msitem_text'],
	]);
	$t->parse('MAIN.MSTORE_MULTI');
}

// Error and message handling
cot_display_messages($t);

/* === Hook === */
foreach (cot_getextplugins('mstore.tags') as $pl) {
	include $pl;
}
/* ===== */
if (Cot::$usr['isadmin'] || Cot::$usr['id'] == $item['msitem_ownerid']) {
	$t->parse('MAIN.MSTORE_ADMIN');
}


$t->parse('MAIN');
$moduleBody = $t->text('MAIN');

if ($itemStaticCacheEnabled) {
	Cot::$cache->static->write();
}