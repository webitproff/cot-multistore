<?php
defined('COT_CODE') or die('Wrong URL');

$pluginDir = $cfg['plugins_dir'] . '/mstoreexcelimport';
$libPathPhpSpreadsheet = "$pluginDir/lib/phpspreadsheet/src/PhpOffice/PhpSpreadsheet";
$libPathPsr = "$pluginDir/lib/psr/simple-cache/src/Psr/SimpleCache";
$logFile = "$pluginDir/logs/import.log";

spl_autoload_register(function (string $class) use ($libPathPhpSpreadsheet): void {
    if (str_starts_with($class, 'PhpOffice\\PhpSpreadsheet\\')) {
        $file = $libPathPhpSpreadsheet . '/' . str_replace('\\', '/', substr($class, strlen('PhpOffice\\PhpSpreadsheet\\'))) . '.php';
        if (file_exists($file)) require_once $file;
    }
});

spl_autoload_register(function (string $class) use ($libPathPsr): void {
    if (str_starts_with($class, 'Psr\\SimpleCache\\')) {
        $file = $libPathPsr . '/' . str_replace('\\', '/', substr($class, strlen('Psr\\SimpleCache\\'))) . '.php';
        if (file_exists($file)) require_once $file;
    }
});

require_once "$libPathPhpSpreadsheet/Spreadsheet.php";
require_once "$libPathPhpSpreadsheet/IOFactory.php";

/* function cot_mstoreexcelimport_log(string $message): void {
    global $logFile;
    file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] $message\n", FILE_APPEND);
} */
/**
 * Логирование сообщений в файл, если включено в конфиге плагина
 */
function cot_mstoreexcelimport_log(string $message): void {
    global $cfg, $logFile;
    if (isset($cfg['plugin']['mstoreexcelimport']['use_function_log']) && $cfg['plugin']['mstoreexcelimport']['use_function_log'] === '1') {
        file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] $message\n", FILE_APPEND);
    }
}

/**
 * Удаление ссылок из текста, если включено в конфиге
 */
function cot_mstoreexcelimport_strip_links(string $html): string {
    global $cfg;
    if (!isset($cfg['plugin']['mstoreexcelimport']['use_function_strip_links']) || $cfg['plugin']['mstoreexcelimport']['use_function_strip_links'] !== '1') {
        // Если функция отключена — вернуть вход без изменений
        return $html;
    }

    // 1. Удаляем все теги <script> и их содержимое
    $html = preg_replace('#<script\b[^>]*>(.*?)</script>#is', '', $html);

    // 2. Заменяем <div> на <p>, закрывающие теги тоже
    // Важно: Заменяем именно теги, без содержания
    $html = preg_replace('#<div\b([^>]*)>#i', '<p$1>', $html);
    $html = preg_replace('#</div>#i', '</p>', $html);

    // 3. Удаляем атрибуты событий (onclick, onload и др.) из всех тегов
    // Список атрибутов событий можно расширять по необходимости
    $html = preg_replace('#\s(on\w+)\s*=\s*(["\']).*?\2#i', '', $html);

    // 4. Удаляем javascript: и data: схемы из href/src
    $html = preg_replace_callback('#\s(href|src)\s*=\s*(["\'])(.*?)\2#i', function($m) {
        $attr = $m[1];
        $quote = $m[2];
        $val = trim($m[3]);
        // Удаляем, если начинается с javascript: или data:
        if (preg_match('#^(javascript:|data:)#i', $val)) {
            return '';
        }
        return " $attr=$quote$val$quote";
    }, $html);

    // 5. Удаляем все ссылки <a href=...> оставляя только текст
    $html = preg_replace_callback('#<a\b[^>]*>(.*?)</a>#si', function ($m) {
        // Оставляем только текст без тегов внутри ссылки
        return strip_tags($m[1]);
    }, $html);

    // 6. Удаляем прямые URL вида https://example.com, http://, ftp://, www.
    $html = preg_replace('#\b(?:https?://|ftp://|www\.)\S+\b#i', '', $html);

    // 7. Удаляем пустые теги, например <p></p> или <p> </p> после очистки
    $html = preg_replace('#<(\w+)[^>]*>\s*</\1>#', '', $html);

    // 8. Можно добавить ещё очистка от лишних пробелов и переносов
    $html = trim($html);

    return $html;
}



function cot_mstoreexcelimport_check_format(string $fileName): string|false {
    global $cfg;
    $allowedFormats = explode(',', $cfg['plugin']['mstoreexcelimport']['allowed_formats'] ?? 'xlsx,csv');
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($fileExt, $allowedFormats)) {
        cot_mstoreexcelimport_log("Ошибка: формат '$fileExt' не поддерживается.");
        return false;
    }
    return $fileExt;
}

function cot_mstoreexcelimport_get_headers(string $filePath, string $fileName): array|string {
    $fileExt = cot_mstoreexcelimport_check_format($fileName);
    if ($fileExt === false) return "Ошибка: формат не поддерживается.";
    try {
        $readerType = ($fileExt === 'csv') ? 'Csv' : 'Xlsx';
        $reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader($readerType);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $headers = [];
        foreach ($spreadsheet->getActiveSheet()->getRowIterator(1)->current()->getCellIterator() as $cell) {
            $value = trim((string) ($cell->getValue() ?? ''));
            $headers[] = $value ?: '0';
        }
        cot_mstoreexcelimport_log("Считаны заголовки: " . implode(', ', $headers));
        return $headers;
    } catch (Exception $e) {
        $error = "Ошибка чтения заголовков: " . $e->getMessage();
        cot_mstoreexcelimport_log($error);
        return $error;
    }
}

/* function cot_mstoreexcelimport_strip_links(string $html): string {
    // Удалить <a href="...">ссылка</a>, оставив текст внутри
    $html = preg_replace_callback('#<a[^>]*>(.*?)</a>#si', function ($m) {
        return strip_tags($m[1]);
    }, $html);

    // Удалить прямые ссылки вида https://example.com или www.example.com
    $html = preg_replace('#\b(?:https?://|www\.)\S+\b#i', '', $html);

    return $html;
} */




function cot_mstoreexcelimport_process(string $filePath, array $mapping, string $fileName): string {
    global $db, $cfg, $db_x, $db_mstore, $sys, $usr;
    $db_mstore = $db_x . 'mstore';
    $fileExt = cot_mstoreexcelimport_check_format($fileName);
    if ($fileExt === false) return "Ошибка: формат не поддерживается.";

    try {
        // Определяем существующие колонки в таблице
        $existingColumns = [];
        $colsRes = $db->query("SHOW COLUMNS FROM `$db_mstore`")->fetchAll();
        foreach ($colsRes as $col) {
            $existingColumns[] = $col['Field'];
        }
        cot_mstoreexcelimport_log("Найденные поля таблицы: " . implode(', ', $existingColumns));

        $readerType = ($fileExt === 'csv') ? 'Csv' : 'Xlsx';
        $reader = PhpOffice\PhpSpreadsheet\IOFactory::createReader($readerType);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $totalRows = $sheet->getHighestRow() - 1;
        $maxRows = (int) ($cfg['plugin']['mstoreexcelimport']['max_rows'] ?? 100);

        $_SESSION['import_progress'] = 0;
        $_SESSION['import_total'] = min($totalRows, $maxRows > 0 ? $maxRows : $totalRows);
        $importedRows = 0;

        $headers = cot_mstoreexcelimport_get_headers($filePath, $fileName);
        if (!is_array($headers)) return $headers;

        cot_mstoreexcelimport_log("Маппинг: " . json_encode($mapping));

        foreach ($sheet->getRowIterator() as $row) {
            if ($row->getRowIndex() === 1 || ($maxRows > 0 && $importedRows >= $maxRows)) continue;

            $data = [];
            foreach ($row->getCellIterator() as $cell) {
                $value = $cell->getValue();
                if ($value instanceof \PhpOffice\PhpSpreadsheet\Shared\Date) {
                    $value = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($value);
                }
                $data[] = trim((string) $value);
            }

            cot_mstoreexcelimport_log("Обрабатываемая строка {$row->getRowIndex()}: " . json_encode($data));

            // Заполняем только существующие поля
            $record = [];
            $defaults = [
                'msitem_id' => null,
                'msitem_alias' => '',
                'msitem_state' => 0,
                'msitem_cat' => '',
                'msitem_title' => '',
                'msitem_desc' => '',
                'msitem_keywords' => '',
                'msitem_metatitle' => '',
                'msitem_metadesc' => '',
                'msitem_text' => '',
                'msitem_costdflt' => 0.00,
                'msitem_price_opt' => 0.00,
                'msitem_price_drop' => 0.00,
                'msitem_price_rr' => 0.00,
                'msitem_parser' => 'html',
                'msitem_author' => '',
                'msitem_ownerid' => $usr['id'] ?: 1,
                'msitem_date' => (int) Cot::$sys['now'],
                'msitem_begin' => (int) Cot::$sys['now'],
                'msitem_expire' => 0,
                'msitem_updated' => (int) Cot::$sys['now'],
                'msitem_count' => 0,
            ];

            foreach ($defaults as $field => $defValue) {
                if (in_array($field, $existingColumns)) {
                    $record[$field] = $defValue;
                }
            }

            // Заполняем данными из Excel только те поля, которые реально есть в таблице
            foreach ($mapping as $dbField => $excelHeader) {
                if (in_array($dbField, $existingColumns) && $excelHeader && ($colIndex = array_search($excelHeader, $headers)) !== false) {
                    $value = $data[$colIndex] ?? '';

                    if (in_array($dbField, ['msitem_date', 'msitem_begin', 'msitem_expire', 'msitem_updated'])) {
                        $timestamp = is_numeric($value) ? (int)$value : strtotime((string)$value);
                        $record[$dbField] = $timestamp ?: (int) Cot::$sys['now'];
                        cot_mstoreexcelimport_log("Дата для $dbField: исходное '$value', результат {$record[$dbField]}");

                    } elseif (in_array($dbField, ['msitem_costdflt', 'msitem_price_opt', 'msitem_price_drop', 'msitem_price_rr'])) {
                        $value = str_replace(',', '.', (string)$value);
                        $integerPart = (int)explode('.', $value)[0];
                        $record[$dbField] = number_format($integerPart, 2, '.', '');
                        cot_mstoreexcelimport_log("Цена для $dbField: исходное '$value', результат {$record[$dbField]}");

                    } elseif (in_array($dbField, ['msitem_text', 'msitem_desc', 'msitem_metadesc'])) {
                        $record[$dbField] = cot_mstoreexcelimport_strip_links((string)$value);

                    } else {
                        $record[$dbField] = (string)$value;
                    }
                }
            }

            // Если msitem_text пустой — подставляем заголовок
            if (in_array('msitem_text', $existingColumns) && empty($record['msitem_text']) && !empty($record['msitem_title'])) {
                $record['msitem_text'] = $record['msitem_title'];
                cot_mstoreexcelimport_log("msitem_text пуст — подставлен заголовок: {$record['msitem_title']}");
            }

            cot_mstoreexcelimport_log("Подготовленный массив для вставки: " . json_encode($record));

            try {
                if (!empty($record) && $db->insert($db_mstore, $record)) {
                    $importedRows++;
                    $_SESSION['import_progress'] = $importedRows;
                    cot_mstoreexcelimport_log("Импортирована строка {$row->getRowIndex()}: " . implode(', ', $data));
                } else {
                    cot_mstoreexcelimport_log("Ошибка вставки строки {$row->getRowIndex()}: не удалось добавить запись");
                }
            } catch (Exception $e) {
                cot_mstoreexcelimport_log("Исключение при вставке строки {$row->getRowIndex()}: " . $e->getMessage());
            }
        }

        $result = "Импорт завершён. Добавлено записей: $importedRows из $totalRows";
        cot_mstoreexcelimport_log($result);

        unset($_SESSION['import_progress'], $_SESSION['import_total']);
        return $result;

    } catch (Exception $e) {
        $error = "Ошибка импорта: " . $e->getMessage();
        cot_mstoreexcelimport_log($error);
        return $error;
    }
}

?>