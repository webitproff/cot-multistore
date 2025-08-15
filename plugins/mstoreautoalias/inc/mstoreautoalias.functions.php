<?php
/**
 * mstoreautoalias functions
 *
 * @package mstoreautoalias
 * @copyright (c) 
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('autoalias2', 'plug');

require_once cot_incfile('mstore', 'module');


function mstoreautoalias_update($title, $id, $aliasfor)
{
	global $cfg, $db, $db_mstore;
	$duplicate = false;
	do
	{
		$alias = autoalias2_convert($title, $id, $duplicate);

		switch ($aliasfor) {
			case 'mstore':				
				$queryToDB = "SELECT COUNT(*) FROM $db_mstore WHERE msitem_alias = '$alias' AND msitem_id != $id";
				break;	
			default:
				break;
		}
		if (!$cfg['plugin']['autoalias2']['prepend_id']
			&& $db->query($queryToDB)->fetchColumn() > 0)
		{
			$duplicate = true;
		}
		else
		{
			switch ($aliasfor) {
				case 'mstore':
					$db->update($db_mstore, array('msitem_alias' => $alias), "msitem_id = $id");
					break;		
				default:
					break;
			}			
			$duplicate = false;
		}
	}
	while ($duplicate && !$cfg['plugin']['autoalias2']['prepend_id']);
	return $alias;
}