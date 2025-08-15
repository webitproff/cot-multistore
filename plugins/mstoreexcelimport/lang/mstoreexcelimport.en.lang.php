<?php


defined('COT_CODE') or die('Wrong URL');
/**
 * Plugin Config
 */
$L['cfg_import_table'] = 'Target Table';
$L['cfg_import_table_hint'] = 'You need to specify <code>pages</code> to import data, for example, into cot_pages';
$L['cfg_max_rows'] = 'Maximum Rows';
$L['cfg_max_rows_hint'] = 'Maximum number of rows to import (0 — no limit)';
$L['cfg_allowed_formats'] = 'Allowed Formats';
$L['cfg_allowed_formats_hint'] = 'List of file formats separated by commas (e.g., xlsx,csv)';
$L['info_title'] = 'Import from Excel via PhpSpreadsheet';
$L['info_desc'] = 'Tool for importing data from Excel files';
$L['info_notes'] = 'Uses the PhpSpreadsheet library version 1.23.0 without Composer. Tested on a Cotonti 0.9.26 website running PHP 8.2';

/**
 * Plugin Admin
 */
$L['mstoreexcelimport_upload'] = 'Upload File';
$L['mstoreexcelimport_import'] = 'Start Import';
$L['mstoreexcelimport_progress'] = 'Import Progress';

/**
 * Plugin Title & Subtitle
 */
$L['mstoreexcelimport_title'] = 'Import from Excel via PhpSpreadsheet';
$L['mstoreexcelimport_subtitle'] = 'Tool for importing data from Excel files';

/**
 * Plugin Body
 */
$L['mstoreexcelimport_select_file'] = 'Select File';
$L['mstoreexcelimport_max_rows_label'] = 'Maximum Number of Rows';
$L['mstoreexcelimport_allowed_formats_label'] = 'Allowed Formats';
$L['mstoreexcelimport_upload'] = 'Upload File';
$L['mstoreexcelimport_headers'] = 'Headers from Excel';
$L['mstoreexcelimport_field_table'] = 'Database Field';
$L['mstoreexcelimport_field_excel'] = 'Excel Field';
$L['mstoreexcelimport_import'] = 'Import';
$L['mstoreexcelimport_reset'] = 'Upload New File';
