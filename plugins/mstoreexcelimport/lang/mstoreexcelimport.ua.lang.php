<?php
// українська локалізація

defined('COT_CODE') or die('Wrong URL');

/**
 * Налаштування плагіна
 */
$L['cfg_import_table'] = 'Цільова таблиця';
$L['cfg_import_table_hint'] = 'Вкажіть <code>pages</code> для імпорту даних, наприклад, у cot_pages';
$L['cfg_max_rows'] = 'Максимум рядків';
$L['cfg_max_rows_hint'] = 'Максимальна кількість рядків для імпорту (0 — без обмежень)';
$L['cfg_allowed_formats'] = 'Дозволені формати';
$L['cfg_allowed_formats_hint'] = 'Список форматів файлів через кому (наприклад: xlsx,csv)';
$L['info_title'] = 'Імпорт з Excel через PhpSpreadsheet';
$L['info_desc'] = 'Інструмент для імпорту даних з Excel-файлів';
$L['info_notes'] = 'Використовується бібліотека PhpSpreadsheet версії 1.23.0 без Composer. Протестовано на Cotonti 0.9.26 з PHP 8.2';

/**
 * Адмінка плагіна
 */
$L['mstoreexcelimport_upload'] = 'Завантажити файл';
$L['mstoreexcelimport_import'] = 'Розпочати імпорт';
$L['mstoreexcelimport_progress'] = 'Прогрес імпорту';

/**
 * Назви та підзаголовки
 */
$L['mstoreexcelimport_title'] = 'Mstore Excel Імпорт';
$L['mstoreexcelimport_subtitle'] = 'Інструмент для імпорту даних з Excel-файлів';

/**
 * Основне тіло плагіна
 */
$L['mstoreexcelimport_select_file'] = 'Оберіть файл';
$L['mstoreexcelimport_max_rows_label'] = 'Максимальна кількість рядків';
$L['mstoreexcelimport_allowed_formats_label'] = 'Допустимі формати';
$L['mstoreexcelimport_upload'] = 'Завантажити файл';
$L['mstoreexcelimport_headers'] = 'Заголовки з Excel';
$L['mstoreexcelimport_field_table'] = 'Поле в базі даних';
$L['mstoreexcelimport_field_excel'] = 'Поле в Excel';
$L['mstoreexcelimport_import'] = 'Імпортувати';
$L['mstoreexcelimport_reset'] = 'Завантажити новий файл';
