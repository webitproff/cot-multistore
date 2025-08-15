<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=mstore.add.add.done,mstore.edit.update.done
[END_COT_EXT]
==================== */


defined('COT_CODE') or die('Wrong URL');

if($cfg['plugin']['mstoreautoalias']['mstore_alias_enable']){

	if (empty($ritem['msitem_alias']))
	{
		require_once cot_incfile('mstoreautoalias', 'plug');
		$ritem['msitem_alias'] = mstoreautoalias_update($ritem['msitem_title'], $id, 'mstore');
	}
}