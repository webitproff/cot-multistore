<?php
/**
 * Add store item.
 *
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('forms');

$id = cot_import('id', 'G', 'INT');
$c = cot_import('c', 'G', 'TXT');

if (empty($c) && !isset(Cot::$structure['mstore'][$c])) {
	$c = '';
}

list(Cot::$usr['auth_read'], Cot::$usr['auth_write'], Cot::$usr['isadmin']) = cot_auth('mstore', 'any');

/* === Hook === */
foreach (cot_getextplugins('mstore.add.first') as $pl) {
	include $pl;
}
/* ===== */
cot_block(Cot::$usr['auth_write']);

Cot::$sys['mstoreparser'] = Cot::$cfg['mstore']['mstoreparser'];
$parser_list = cot_get_parsers();

if ($a == 'add') {
	cot_shield_protect();

	/* === Hook === */
	foreach (cot_getextplugins('mstore.add.add.first') as $pl) {
		include $pl;
	}
	/* ===== */

	$ritem = cot_mstore_import('POST', array(), Cot::$usr);

	list(Cot::$usr['auth_read'], Cot::$usr['auth_write'], Cot::$usr['isadmin']) = cot_auth('mstore', $ritem['msitem_cat']);
	cot_block(Cot::$usr['auth_write']);

	/* === Hook === */
	foreach (cot_getextplugins('mstore.add.add.import') as $pl) {
		include $pl;
	}
	/* ===== */

	cot_mstore_validate($ritem);

	/* === Hook === */
	foreach (cot_getextplugins('mstore.add.add.error') as $pl) {
		include $pl;
	}
	/* ===== */
	if (!cot_error_found()) {
		$id = cot_mstore_add($ritem, Cot::$usr);

		switch ($ritem['msitem_state']) {
			case 0:
				$r_url = empty($ritem['msitem_alias']) 
					? cot_url('mstore', ['c' => $ritem['msitem_cat'], 'id' => $id], '', true)
					: cot_url('mstore', ['c' => $ritem['msitem_cat'], 'al' => $ritem['msitem_alias']], '', true);
               //$r_url = cot_mstore_url($ritem, [], '', true);
				break;
			case 1:
				$r_url = cot_url('message', 'msg=300', '', true);
				break;
			case 2:
				cot_message('mstore_savedasdraft');
				$r_url = cot_url('mstore', 'm=edit&id='.$id, '', true);
				break;
		}
		cot_redirect($r_url);

	} else {
        $urlParams = ['m' => 'add'];
	    if (!empty($c)) {
            $urlParams['c'] = $c;
        }
		cot_redirect(cot_url('mstore', $urlParams, '', true));
	}

}

$ritem = [
    'msitem_metatitle' => '',
    'msitem_metadesc' => '',
    'msitem_alias' => '',
    'msitem_title' => '',
    'msitem_desc' => '',
    'msitem_text' => '',
];

// Item cloning support
$clone = cot_import('clone', 'G', 'INT');
if ($clone > 0) {
	$ritem = Cot::$db->query('SELECT * FROM ' . Cot::$db->mstore . ' WHERE msitem_id = ?', $clone)->fetch();
    if (!$ritem) {
        cot_die_message(404);
    }
}

if (empty($ritem['msitem_cat'])) {
    $ritem['msitem_cat'] = isset($c) ? $c : '';
}

$breadcrumbs = [];
$urlParams = ['m' => 'add'];

if (!empty($ritem['msitem_cat'])) {
    list(Cot::$usr['auth_read'], Cot::$usr['auth_write'], Cot::$usr['isadmin']) = cot_auth('mstore', $ritem['msitem_cat']);
    cot_block(Cot::$usr['auth_write']);

    if (!Cot::$usr['isadmin'] && Cot::$structure['mstore'][$ritem['msitem_cat']]['locked']) {
        cot_die_message(602, TRUE);
    }

    Cot::$sys['sublocation'] = Cot::$structure['mstore'][$ritem['msitem_cat']]['title'];
    $mskin = cot_tplfile(['mstore', 'add', Cot::$structure['mstore'][$ritem['msitem_cat']]['tpl']]);
    $breadcrumbs = cot_structure_buildpath('mstore', $ritem['msitem_cat']);
    $urlParams['c'] = $ritem['msitem_cat'];
} else {
    if (!Cot::$usr['isadmin']) {
        // User can add item to these categories
        $categories = [];
        if (!empty(Cot::$structure['mstore'])) {
            foreach (Cot::$structure['mstore'] as $i => $x) {
                $display = cot_auth('mstore', $i, 'W');
                if ($display && !empty($subcat) && isset(Cot::$structure['mstore'][$subcat])) {
                    $mtch = Cot::$structure['mstore'][$subcat]['path'] . ".";
                    $mtchlen = mb_strlen($mtch);
                    $display = (mb_substr($x['path'], 0, $mtchlen) == $mtch || $i === $subcat);
                }
                if ($i != 'all' && $display) {
                    $categories[] = $i;
                }
            }
        }
        cot_block(count($categories) > 0);
    }

    Cot::$sys['sublocation'] = Cot::$L['mstore_addtitle'];
    $mskin = cot_tplfile(['mstore', 'add']);
}

Cot::$out['subtitle'] = Cot::$L['mstore_addsubtitle'];
if (!isset(Cot::$out['head'] )) {
    Cot::$out['head']  = '';
}
Cot::$out['head'] .= Cot::$R['code_noindex'];

$breadcrumbs[] = [cot_url('mstore', $urlParams), Cot::$L['mstore_addtitle']];

/* === Hook === */
foreach (cot_getextplugins('mstore.add.main') as $pl) {
	include $pl;
}
/* ===== */


$t = new XTemplate($mskin);

$itemadd_array = [
	'MSTOREADD_PAGETITLE' => Cot::$L['mstore_addtitle'],
    'MSTOREADD_BREADCRUMBS' => cot_breadcrumbs($breadcrumbs, Cot::$cfg['homebreadcrumb']),
	'MSTOREADD_SUBTITLE'  => Cot::$L['mstore_addsubtitle'],
	'MSTOREADD_ADMINEMAIL' => 'mailto:' . Cot::$cfg['adminemail'],
	'MSTOREADD_FORM_SEND' => cot_url('mstore', 'm=add&a=add&c=' . $c),
	'MSTOREADD_FORM_CAT' => cot_selectbox_structure('mstore', $ritem['msitem_cat'], 'rmsitemcat'),
	'MSTOREADD_FORM_CAT_SHORT' => cot_selectbox_structure('mstore', $ritem['msitem_cat'], 'rmsitemcat', $c),
	
	'MSTOREADD_FORM_METATITLE' => cot_inputbox('text', 'rmsitemmetatitle', $ritem['msitem_metatitle'], ['maxlength' => '255']),
	'MSTOREADD_FORM_METADESC' => cot_textarea('rmsitemmetadesc', $ritem['msitem_metadesc'], 2, 64, ['maxlength' => '255']),
	'MSTOREADD_FORM_ALIAS' => cot_inputbox('text', 'rmsitemalias', $ritem['msitem_alias'], ['maxlength' => '255']),
	'MSTOREADD_FORM_TITLE' => cot_inputbox('text', 'rmsitemtitle', $ritem['msitem_title'], ['maxlength' => '255']),
	'MSTOREADD_FORM_DESCRIPTION' => cot_textarea('rmsitemdesc', $ritem['msitem_desc'], 2, 64, ['maxlength' => '255']),
	
	'MSTOREADD_FORM_OWNER' => cot_build_user(Cot::$usr['id'], Cot::$usr['name']),
	'MSTOREADD_FORM_OWNER_ID' => Cot::$usr['id'],
	'MSTOREADD_FORM_DATE' => cot_selectbox_date(Cot::$sys['now'], 'long', 'rmsitemdate'),

	'MSTOREADD_FORM_TEXT' => cot_textarea('rmsitemtext', $ritem['msitem_text'], 24, 120, '', 'input_textarea_editor'),
	'MSTOREADD_FORM_PARSER' => cot_selectbox(Cot::$cfg['mstore']['mstoreparser'], 'rmsitemparser', $parser_list, $parser_list, false),
];


$t->assign($itemadd_array);

// Extra fields
if (!empty(Cot::$extrafields[Cot::$db->mstore])) {
    foreach (Cot::$extrafields[Cot::$db->mstore] as $exfld) {
        $uname = strtoupper($exfld['field_name']);
        $data = isset($ritem['msitem_' . $exfld['field_name']]) ? $ritem['msitem_' . $exfld['field_name']] : null;
        $exfld_val = cot_build_extrafields('rmsitem' . $exfld['field_name'], $exfld, $data);
        $exfld_title = cot_extrafield_title($exfld, 'mstore_');

        $t->assign([
            'MSTOREADD_FORM_' . $uname => $exfld_val,
            'MSTOREADD_FORM_' . $uname . '_TITLE' => $exfld_title,
            'MSTOREADD_FORM_EXTRAFLD' => $exfld_val,
            'MSTOREADD_FORM_EXTRAFLD_TITLE' => $exfld_title,
        ]);
        $t->parse('MAIN.EXTRAFLD');
    }
}

// Error and message handling
cot_display_messages($t);

/* === Hook === */
foreach (cot_getextplugins('mstore.add.tags') as $pl) {
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