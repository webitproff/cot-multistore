<?php
/**
 * Edit store item.
 * filename mstore.edit.php
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */

use cot\exceptions\NotFoundHttpException;
use cot\modules\mstore\inc\MstoreDictionary;
use cot\modules\mstore\inc\MstoreRepository;
use cot\modules\mstore\inc\MstoreControlService;


defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('forms');

$id = cot_import('id', 'G', 'INT');
$c = cot_import('c', 'G', 'TXT');
$item['msitem_parser'] = cot_import('rparser', 'P', 'ALP');
list(Cot::$usr['auth_read'], Cot::$usr['auth_write'], Cot::$usr['isadmin']) = cot_auth('mstore', 'any');

/* === Hook === */
foreach (cot_getextplugins('mstore.edit.first') as $pl) {
	include $pl;
}
/* ===== */

cot_block(Cot::$usr['auth_read']);

if (!$id || $id < 0) {
    throw new NotFoundHttpException();
}
$row_item = Cot::$db->query('SELECT * FROM ' . Cot::$db->mstore . ' WHERE msitem_id = ?', $id)->fetch();
if ($row_item === null) {
    throw new NotFoundHttpException();
}

list(Cot::$usr['auth_read'], Cot::$usr['auth_write'], Cot::$usr['isadmin']) = cot_auth('mstore', $row_item['msitem_cat']);

// Устанавливаем парсер по умолчанию, если он пустой
if (empty($item['msitem_parser'])) {
    $item['msitem_parser'] = $cfg['mstore']['mstoreparser'] ?: 'html';
}
$parser_list = cot_get_parsers();
Cot::$sys['mstoreparser'] = $row_item['msitem_parser'];

if ($a == 'update') {
	/* === Hook === */
	foreach (cot_getextplugins('mstore.edit.update.first') as $pl) {
		include $pl;
	}
	/* ===== */

	cot_block(Cot::$usr['isadmin'] || Cot::$usr['auth_write'] && Cot::$usr['id'] == $row_item['msitem_ownerid']);

	$ritem = cot_mstore_import('POST', $row_item, Cot::$usr);

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$ritemdelete = cot_import('rmsitemdelete', 'P', 'BOL');
	} else {
		$ritemdelete = cot_import('delete', 'G', 'BOL');
		cot_check_xg();
	}

	if ($ritemdelete) {
		$resultOrMessage = MstoreControlService::getInstance()->delete($id, $row_item);
		if ($resultOrMessage !== false) {
			cot_message($resultOrMessage);
			cot_redirect(cot_url('mstore', ['c' => $row_item['msitem_cat']], '', true));
		}
	}


	/* === Hook === */
	foreach (cot_getextplugins('mstore.edit.update.import') as $pl) {
		include $pl;
	}
	/* ===== */

	cot_mstore_validate($ritem);

	/* === Hook === */
	foreach (cot_getextplugins('mstore.edit.update.error') as $pl) {
		include $pl;
	}
	/* ===== */

	if (!cot_error_found()) {
		cot_mstore_update($id, $ritem);

		switch ($ritem['msitem_state']) {
			case MstoreDictionary::STATE_PUBLISHED:
                $r_url = cot_mstore_url($ritem, [], '', true);
				break;

			case MstoreDictionary::STATE_PENDING:
				$r_url = cot_url('message', 'msg=300', '', true);
				break;

			case MstoreDictionary::STATE_DRAFT:
				cot_message(Cot::$L['mstore_savedasdraft']);
				$r_url = cot_url('mstore', 'm=edit&id=' . $id, '', true);
				break;
		}
		cot_redirect($r_url);
	} else {
		cot_redirect(cot_url('mstore', "m=edit&id=$id", '', true));
	}
}

$item = $row_item;

$item['msitem_status'] = cot_mstore_status($item['msitem_state']);

cot_block(Cot::$usr['isadmin'] || Cot::$usr['auth_write'] && Cot::$usr['id'] == $item['msitem_ownerid']);

Cot::$out['subtitle'] = Cot::$L['mstore_edittitle'];
if (!isset(Cot::$out['head'])) {
    Cot::$out['head'] = '';
}
Cot::$out['head'] .= Cot::$R['code_noindex'];
Cot::$sys['sublocation'] = Cot::$structure['mstore'][$item['msitem_cat']]['title'];

$mskin = cot_tplfile(array('mstore', 'edit', Cot::$structure['mstore'][$item['msitem_cat']]['tpl']));

/* === Hook === */
foreach (cot_getextplugins('mstore.edit.main') as $pl) {
	include $pl;
}
/* ===== */


$t = new XTemplate($mskin);

$breadcrumbs = cot_structure_buildpath('mstore', $item['msitem_cat']);
$breadcrumbs[] = [cot_mstore_url($item), $item['msitem_title']];
$breadcrumbs[] = Cot::$L['mstore_edittitle'];

$itemedit_array = [
	'MSTOREEDIT_PAGETITLE' => Cot::$L['mstore_edittitle'],
	'MSTOREEDIT_SUBTITLE' => Cot::$L['mstore_editsubtitle'],
    'MSTOREEDIT_BREADCRUMBS' => cot_breadcrumbs($breadcrumbs, Cot::$cfg['homebreadcrumb']),
	'MSTOREEDIT_FORM_SEND' => cot_url('mstore', ['m' => 'edit', 'a' => 'update', 'id' => $item['msitem_id']]),
	'MSTOREEDIT_FORM_ID' => $item['msitem_id'],
	'MSTOREEDIT_FORM_STATE' => $item['msitem_state'],
	'MSTOREEDIT_FORM_STATUS' => $item['msitem_status'],
	'MSTOREEDIT_FORM_LOCAL_STATUS' => Cot::$L['mstore_status_' . $item['msitem_status']],
	'MSTOREEDIT_FORM_CAT' => cot_selectbox_structure('mstore', $item['msitem_cat'], 'rmsitemcat'),
	'MSTOREEDIT_FORM_CAT_SHORT' => cot_selectbox_structure('mstore', $item['msitem_cat'], 'rmsitemcat', $c),

	'MSTOREEDIT_FORM_CAT_S2' => cot_mstore_selectbox_structure_select2('mstore', $item['msitem_cat'], 'rmsitemcat'),
	'MSTOREEDIT_FORM_CAT_SHORT_S2' => cot_mstore_selectbox_structure_select2('mstore', $item['msitem_cat'], 'rmsitemcat', $c),
	
	'MSTOREEDIT_FORM_METATITLE' => cot_inputbox('text', 'rmsitemmetatitle', $item['msitem_metatitle'], array('maxlength' => '255')),
	'MSTOREEDIT_FORM_METADESC' => cot_textarea('rmsitemmetadesc', $item['msitem_metadesc'], 2, 64, array('maxlength' => '255')),
	'MSTOREEDIT_FORM_ALIAS' => cot_inputbox('text', 'rmsitemalias', $item['msitem_alias'], array('maxlength' => '255')),
	'MSTOREEDIT_FORM_TITLE' => cot_inputbox('text', 'rmsitemtitle', $item['msitem_title'], array('maxlength' => '255')),
    'MSTOREEDIT_FORM_DESCRIPTION' => cot_textarea('rmsitemdesc', $item['msitem_desc'], 2, 64, array('maxlength' => '255')),
	
	'MSTOREEDIT_FORM_DATE' => cot_selectbox_date($item['msitem_date'], 'long', 'rmsitemdate').' '.Cot::$usr['timetext'],
	'MSTOREEDIT_FORM_DATENOW' => cot_checkbox(0, 'rmsitemdatenow'),

	'MSTOREEDIT_FORM_UPDATED' => cot_date('datetime_full', $item['msitem_updated']).' '.Cot::$usr['timetext'],
	'MSTOREEDIT_FORM_TEXT' => cot_textarea('rmsitemtext', $item['msitem_text'], 24, 120, '', 'input_textarea_editor'),
	'MSTOREEDIT_FORM_DELETE' => cot_radiobox(0, 'rmsitemdelete', [1, 0], [Cot::$L['Yes'], Cot::$L['No']]),
	'MSTOREEDIT_FORM_PARSER' => cot_selectbox($item['msitem_parser'], 'rmsitemparser', cot_get_parsers(), cot_get_parsers(), false),
	'MSTOREEDIT_FORM_COSTDFLT' => cot_inputbox('text', 'rmsitemcostdflt', $item['msitem_costdflt'], 'size="10"'),
];

if (Cot::$usr['isadmin']) {
	$itemedit_array += [
		'MSTOREEDIT_FORM_OWNER_ID' => cot_inputbox('text', 'rmsitemownerid', $item['msitem_ownerid'], ['maxlength' => '24']),
		'MSTOREEDIT_FORM_HITS' => cot_inputbox('text', 'rmsitemcount', $item['msitem_count'], ['maxlength' => '8']),
	];
}

$t->assign($itemedit_array);

// Extra fields
if (!empty(Cot::$extrafields[Cot::$db->mstore])) {
    foreach (Cot::$extrafields[Cot::$db->mstore] as $exfld) {
        $uname = strtoupper($exfld['field_name']);
        $extrafieldElement = cot_build_extrafields(
            'rmsitem' . $exfld['field_name'],
            $exfld,
            $item['msitem_' . $exfld['field_name']]
        );
        $extrafieldTitle = cot_extrafield_title($exfld, 'mstore_');

        $t->assign([
            'MSTOREEDIT_FORM_' . $uname => $extrafieldElement,
            'MSTOREEDIT_FORM_' . $uname . '_TITLE' => $extrafieldTitle,
            'MSTOREEDIT_FORM_EXTRAFLD' => $extrafieldElement,
            'MSTOREEDIT_FORM_EXTRAFLD_TITLE' => $extrafieldTitle
        ]);
        $t->parse('MAIN.EXTRAFLD');
    }
}

// Error and message handling
cot_display_messages($t);

/* === Hook === */
foreach (cot_getextplugins('mstore.edit.tags') as $pl) {
	include $pl;
}
/* ===== */

$usr_can_publish = false;
if (Cot::$usr['isadmin']) {
	if (Cot::$cfg['mstore']['mstoreautovalidate']) {
        $usr_can_publish = true;
    }
	$t->parse('MAIN.ADMIN');
}

$t->parse('MAIN');
$moduleBody = $t->text('MAIN');