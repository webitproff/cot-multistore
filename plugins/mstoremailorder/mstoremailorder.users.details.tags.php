<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=users.details.tags
[END_COT_EXT]
==================== */

/**
 * MStore Email Order plugin: users details tags
 * Filename: mstoremailorder.users.details.tags.php
 * @package MStoreEmailOrder for CMF Cotonti Siena v.0.9.26 on PHP 8.4
 * Version=2.0.1
 * Date=2025-09-05
 * @author webitproff
 * @copyright Copyright (c) 2025 webitproff | https://github.com/webitproff
 * @license BSD License
 */

defined('COT_CODE') or die('Wrong URL.');

$t->assign([
    'USER_INCOMING_ORDERS_LINK' => cot_url('plug', 'e=mstoremailorder&m=incoming'),
    'USER_OUTGOING_ORDERS_LINK' => cot_url('plug', 'e=mstoremailorder&m=outgoing'),
]);
?>