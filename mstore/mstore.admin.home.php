<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=admin.home.sidepanel
[END_COT_EXT]
==================== */

/**
 * Mstore manager & Queue of store items
 *
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL');

$tt = new XTemplate(cot_tplfile('mstore.admin.home', 'module', true));

require_once cot_incfile('mstore', 'module');

$itemsqueued = $db->query("SELECT COUNT(*) FROM $db_mstore WHERE msitem_state='1'");
$itemsqueued = $itemsqueued->fetchColumn();
$tt->assign([
	'ADMIN_HOME_URL' => cot_url('admin', 'm=mstore'),
	'ADMIN_HOME_MSTOREQUEUED' => $itemsqueued
]);

$tt->parse('MAIN');

$line = $tt->text('MAIN');