<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once cot_incfile('mstoreexcelimport', 'plug');
require_once cot_langfile('mstoreexcelimport', 'plug');

if (!cot_auth('plug', 'mstoreexcelimport', 'W')) {
    cot_die_message(403);
}
$adminTitle = Cot::$L['mstoreexcelimport_title'];

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$uploadDir = $cfg['plugins_dir'] . '/mstoreexcelimport/uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$pageFields = [
    'msitem_id' => 'ID (auto)',
    'msitem_alias' => 'Alias',
    'msitem_state' => 'State',
    'msitem_cat' => 'Category',
    'msitem_title' => 'Title',
    'msitem_desc' => 'Description',
    'msitem_keywords' => 'Keywords',
    'msitem_metatitle' => 'Meta Title',
    'msitem_metadesc' => 'Meta Description',
    'msitem_text' => 'Text',
    'msitem_costdflt' => 'Цена стандартная',
	'msitem_price_opt' => 'Опт',
	'msitem_price_drop' => 'Дроп',
	'msitem_price_rr' => 'Розница',
    'msitem_parser' => 'Parser',
    'msitem_author' => 'Author',
    'msitem_ownerid' => 'Owner ID',
    'msitem_date' => 'Date',
    'msitem_begin' => 'Begin Date',
    'msitem_expire' => 'Expire Date',
    'msitem_updated' => 'Updated Date',
    'msitem_count' => 'Count',
];

$t = new XTemplate(cot_tplfile('mstoreexcelimport.tools', 'plug', true));

$a = cot_import('a', 'G', 'TXT');
cot_mstoreexcelimport_log("Запрос: m=" . cot_import('m', 'G', 'TXT') . ", p=" . cot_import('p', 'G', 'TXT') . ", a=" . ($a ?? 'пусто'));

if ($a === 'upload' && !empty($_FILES['excel_file']['tmp_name'])) {
    cot_mstoreexcelimport_log("Начало загрузки файла");
    $fileTmpPath = (string) $_FILES['excel_file']['tmp_name'];
    $fileName = (string) $_FILES['excel_file']['name'];
    $filePath = $uploadDir . uniqid('excel_') . '_' . basename($fileName);
    
    if (move_uploaded_file($fileTmpPath, $filePath)) {
        $_SESSION['excel_file_path'] = $filePath;
        $_SESSION['excel_file_name'] = $fileName;
        $headers = cot_mstoreexcelimport_get_headers($filePath, $fileName);
        if (is_array($headers)) {
            $_SESSION['excel_headers'] = $headers;
            cot_mstoreexcelimport_log("Файл загружен успешно, путь: $filePath, заголовки: " . implode(', ', $headers));
        } else {
            cot_message("Ошибка при загрузке: $headers", 'error');
            unset($_SESSION['excel_file_path'], $_SESSION['excel_file_name'], $_SESSION['excel_headers']);
            if (file_exists($filePath)) unlink($filePath);
            cot_mstoreexcelimport_log("Сессия очищена из-за ошибки, файл удалён: $filePath");
        }
    } else {
        cot_message("Ошибка: не удалось переместить файл.", 'error');
        cot_mstoreexcelimport_log("Ошибка перемещения файла из $fileTmpPath в $filePath");
    }
    $redirectUrl = cot_url('admin', ['m' => 'other', 'p' => 'mstoreexcelimport'], '', true);
    cot_mstoreexcelimport_log("Редирект на: $redirectUrl");
    cot_redirect($redirectUrl);
} elseif ($a === 'import' && !empty($_POST['mapping'])) {
    cot_mstoreexcelimport_log("Начало импорта");
    $mapping = (array) $_POST['mapping'];
    $validMapping = false;
    foreach ($mapping as $dbField => $excelHeader) {
        if ($excelHeader !== '0') {
            $validMapping = true;
            break;
        }
    }
    if (!$validMapping) {
        cot_message("Ошибка: маппинг не задан. Выберите хотя бы одно соответствие полей.", 'error');
        cot_mstoreexcelimport_log("Ошибка: маппинг не задан");
    } else {
        $filePath = $_SESSION['excel_file_path'] ?? '';
        $fileName = $_SESSION['excel_file_name'] ?? '';
        if ($filePath && file_exists($filePath)) {
            $result = cot_mstoreexcelimport_process($filePath, $mapping, $fileName);
            cot_message($result);
            unset($_SESSION['excel_file_path'], $_SESSION['excel_headers'], $_SESSION['excel_file_name']);
            if (file_exists($filePath)) unlink($filePath);
            cot_mstoreexcelimport_log("Импорт завершён, сессия очищена, файл удалён: $filePath");
        } else {
            $error = "Ошибка: файл не найден для импорта.";
            cot_message($error, 'error');
            cot_mstoreexcelimport_log($error . " Путь: $filePath");
        }
    }
    $redirectUrl = cot_url('admin', ['m' => 'other', 'p' => 'mstoreexcelimport'], '', true);
    cot_mstoreexcelimport_log("Редирект на: $redirectUrl");
    cot_redirect($redirectUrl);
} elseif ($a === 'reset') {
    $filePath = $_SESSION['excel_file_path'] ?? '';
    if ($filePath && file_exists($filePath)) unlink($filePath);
    unset($_SESSION['excel_file_path'], $_SESSION['excel_headers'], $_SESSION['excel_file_name']);
    cot_mstoreexcelimport_log("Сессия сброшена вручную, файл удалён: $filePath");
    $redirectUrl = cot_url('admin', ['m' => 'other', 'p' => 'mstoreexcelimport'], '', true);
    cot_mstoreexcelimport_log("Редирект на: $redirectUrl");
    cot_redirect($redirectUrl);
}

$sessionHeaders = isset($_SESSION['excel_headers']) ? (is_array($_SESSION['excel_headers']) ? 'массив (' . count($_SESSION['excel_headers']) . ')' : 'строка: ' . $_SESSION['excel_headers']) : 'не задано';
cot_mstoreexcelimport_log("Состояние сессии: headers=$sessionHeaders");

if (!isset($_SESSION['excel_headers']) || !is_array($_SESSION['excel_headers'])) {
    cot_mstoreexcelimport_log("Показываем форму загрузки");
    $t->assign([
        'EXCELIMPORT_FORM_ACTION' => cot_url('admin', ['m' => 'other', 'p' => 'mstoreexcelimport', 'a' => 'upload'], '', true),
        'EXCELIMPORT_FORM_ENCTYPE' => 'multipart/form-data',
        'EXCELIMPORT_MAX_ROWS' => $cfg['plugin']['mstoreexcelimport']['max_rows'],
        'EXCELIMPORT_ALLOWED_FORMATS' => $cfg['plugin']['mstoreexcelimport']['allowed_formats']
    ]);
    $t->parse('MAIN.UPLOAD');
    cot_mstoreexcelimport_log("Форма загрузки отрендерена");
} else {
    cot_mstoreexcelimport_log("Показываем форму маппинга");
    $headers = $_SESSION['excel_headers'];
    foreach ($pageFields as $field => $label) {
        $options = ['0' => 'Не импортировать'];
        foreach ($headers as $header) {
            $options[$header] = $header;
        }
        $t->assign([
            'FIELD_NAME' => $field,
            'FIELD_LABEL' => $label,
            'FIELD_INPUT' => cot_selectbox('', "mapping[$field]", array_keys($options), array_values($options), false)
        ]);
        $t->parse('MAIN.MAPPING.FIELDS');
    }
    $t->assign([
        'EXCELIMPORT_MAPPING_ACTION' => cot_url('admin', ['m' => 'other', 'p' => 'mstoreexcelimport', 'a' => 'import'], '', true),
        'EXCELIMPORT_PROGRESS_URL' => cot_url('admin', ['m' => 'other', 'p' => 'mstoreexcelimport', 'a' => 'progress'], '', true),
        'EXCELIMPORT_HEADERS' => implode(', ', $headers),
        'EXCELIMPORT_RESET_URL' => cot_url('admin', ['m' => 'other', 'p' => 'mstoreexcelimport', 'a' => 'reset'], '', true)
    ]);
    $t->parse('MAIN.MAPPING');
    cot_mstoreexcelimport_log("Форма маппинга отрендерена");
}

cot_display_messages($t);
$t->parse('MAIN');
$pluginBody = $t->text('MAIN');

cot_mstoreexcelimport_log("Отрендерено: " . (!empty($pluginBody) ? 'содержимое есть (' . strlen($pluginBody) . ' символов)' : 'пусто'));