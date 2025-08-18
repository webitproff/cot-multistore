<?php
/* ====================
[BEGIN_COT_EXT]
Code=mstoreexcelimport
Name=MStore Excel Import Excel Import
Category=tools
Description=Plugin for importing data to MultiStore module from Excel files into Cotonti using PhpSpreadsheet
Version=1.0.0
Date=2025-08-15
Author=cot_webitproff
Copyright=(c) 2025 cot_webitproff
Notes=BSD License
Auth_guests=R
Lock_guests=12345A
Auth_members=RW
Lock_members=
Requires_modules=mstore
Recommends_modules=
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
import_table=01:string::mstore:Target table for import (e.g., 'mstore' for cot_mstore)
max_rows=02:string::300:Maximum rows to import per file (0 for unlimited)
allowed_formats=03:string::xlsx,csv:Comma-separated list of allowed file formats
use_function_strip_links=04:radio::0:Использовать функцию для вырезания ссылок в тексте файла импорта
use_function_log=05:radio::0:Использовать функцию логирования при импорте
[END_COT_EXT_CONFIG]
==================== */

/**
 * MStore Excel Import plugin for MStore on Cotonti
 *
 * @package mstoreexcelimport
 * @copyright (c) 2025 cot_webitproff
 * @license BSD License
 */

defined('COT_CODE') or die('Wrong URL');

