<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */


defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('mstoreautoalias', 'plug');
require_once cot_langfile('mstoreautoalias', 'plug');
require_once cot_langfile('autoalias2', 'plug');

$t = new XTemplate(cot_tplfile('mstoreautoalias.admin', 'plug', true));

$adminsubtitle = $L['mstoreautoalias'];

if ($a == 'create')
{	
	$for = cot_import('aliasfor', 'G', 'TXT');
	switch ($for) {
		case 'mstore':
			$queryToDB = "SELECT msitem_title, msitem_id FROM $db_mstore WHERE msitem_alias = ''";
			break;	
		default:
			break;
	}

	$count = 0;
	$res = $db->query($queryToDB);
	foreach ($res->fetchAll() as $row)
	{
		mstoreautoalias_update($row['msitem_title'], $row['msitem_id'], $for);
		$count++;
	}
	$res->closeCursor();
	cot_message(cot_rc('aliases_written', $count));
	cot_redirect(cot_url('admin', 'm=other&p=mstoreautoalias', '', true));
}
$t->assign(array(
	'AUTOALIAS_MSTORE_CREATE' => cot_url('admin', 'm=other&p=mstoreautoalias&a=create&aliasfor=mstore')
	));
cot_display_messages($t);

$t->parse();
$pluginBody = $t->text('MAIN');
