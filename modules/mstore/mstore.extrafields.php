<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=admin.extrafields.first
[END_COT_EXT]
==================== */

/**
 * Mstore module
 * filename mstore.extrafields.php
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('mstore', 'module');
$extra_whitelist[$db_mstore] = [
	'name' => $db_mstore,
	'caption' => $L['Module'].' Mstore',
	'type' => 'module',
	'code' => 'mstore',
	'tags' => [
		'mstore.list.tpl' => '{LIST_ROW_XXXXX}, {LIST_TOP_XXXXX}',
		'mstore.tpl' => '{MSTORE_XXXXX}, {MSTORE_XXXXX_TITLE}',
		'mstore.add.tpl' => '{MSTOREADD_FORM_XXXXX}, {MSTOREADD_FORM_XXXXX_TITLE}',
		'mstore.edit.tpl' => '{MSTOREEDIT_FORM_XXXXX}, {MSTOREEDIT_FORM_XXXXX_TITLE}',
		'news.tpl' => '{MSTORE_ROW_XXXXX}',
		'recentitems.mstore.tpl' => '{MSTORE_ROW_XXXXX}',
	]
];