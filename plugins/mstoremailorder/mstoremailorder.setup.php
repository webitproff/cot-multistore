<?php
/* ====================
[BEGIN_COT_EXT]
Code=mstoremailorder
Name=MStore Email Order
Category=ecommerce
Description=Plugin for handling email-based orders in MStore module
Version=2.0.1
Date=2025-09-05
Author=webitproff
Copyright=Copyright (c) 2025 webitproff | https://github.com/webitproff
Notes=
Auth_guests=R
Lock_guests=12345A
Auth_members=RW
Lock_members=345
Requires_modules=mstore,users
Requires_plugins=
Recommends_plugins=phpmailer
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
email_from=01:string::no-reply@site.com:Email address for sending order notifications
use_function_log=05:radio::0:Использовать функцию логирования 
list_maxrowsperpage=06:select:1,2,5,6,7,8,9,10,15:10:
[END_COT_EXT_CONFIG]
==================== */

/**
 * MStore Email Order plugin: setup
 * filename mstoremailorder.setup.php
 * @package MStoreEmailOrder for CMF Cotonti Siena v.0.9.26 on PHP 8.4
 * Version=2.0.1
 * Date=2025-09-05
 * @author webitproff
 * @copyright Copyright (c) 2025 webitproff | https://github.com/webitproff
 * @license BSD License
 */

defined('COT_CODE') or die('Wrong URL.');



//status_email_subject=03:string::Order Status Update:Subject for order status update emails
//email_subject=02:string::New Order Notification:Subject for order notification emails