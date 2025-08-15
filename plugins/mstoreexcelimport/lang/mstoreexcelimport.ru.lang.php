<?php
// русская локализация

defined('COT_CODE') or die('Wrong URL');

/**
 * Plugin Config
 */
$L['cfg_import_table'] = 'Целевая таблица';
$L['cfg_import_table_hint'] = 'Нужно указать <code>pages</code> для импорта данных например, в cot_pages';
$L['cfg_max_rows'] = 'Максимум строк';
$L['cfg_max_rows_hint'] = 'Максимальное число строк для импорта (0 — без ограничений)';
$L['cfg_allowed_formats'] = 'Разрешённые форматы';
$L['cfg_allowed_formats_hint'] = 'Список форматов файлов через запятую (например, xlsx,csv)';
$L['info_title'] = 'Импорт из Excel через PhpSpreadsheet';
$L['info_desc'] = 'Инструмент для импорта данных из Excel-файлов';
$L['info_notes'] = 'Используется библиотека PhpSpreadsheet версии 1.23.0 без Composer. Тестировалось на сайте Cotonti 0.9.26 под версией PHP 8.2';
/**
 * Plugin Admin
 */
$L['mstoreexcelimport_upload'] = 'Загрузить файл';
$L['mstoreexcelimport_import'] = 'Начать импорт';
$L['mstoreexcelimport_progress'] = 'Прогресс импорта';

/**
 * Plugin Title & Subtitle
 */
$L['mstoreexcelimport_title'] = 'Mstore Excel Import';
$L['mstoreexcelimport_subtitle'] = 'Инструмент для импорта данных из Excel-файлов';

/**
 * Plugin Body
 */


$L['mstoreexcelimport_select_file'] = 'Выберите файл';
$L['mstoreexcelimport_max_rows_label'] = 'Максимальное количество строк';
$L['mstoreexcelimport_allowed_formats_label'] = 'Допустимые форматы';
$L['mstoreexcelimport_upload'] = 'Загрузить файл';
$L['mstoreexcelimport_headers'] = 'Заголовки из Excel';
$L['mstoreexcelimport_field_table'] = 'Поле в базе данных';
$L['mstoreexcelimport_field_excel'] = 'Поле в Excel';
$L['mstoreexcelimport_import'] = 'Импортировать';
$L['mstoreexcelimport_reset'] = 'Загрузить новый файл'; // 