<?php
defined('COT_CODE') or die('Неверный URL');

global $db, $db_x, $db_mstore_currency, $db_mstore_price_types, $db_mstore_prices;

$db_mstore_currency = $db_x . 'mstore_currency';
$db_mstore_price_types = $db_x . 'mstore_price_types';
$db_mstore_prices = $db_x . 'mstore_prices';

/**
 * Возвращает список всех валют из базы данных
 * @return array Список валют
 */
function mstoreprice_get_currencies() {
    global $db, $db_mstore_currency;
    return $db->query("SELECT * FROM $db_mstore_currency ORDER BY title")->fetchAll();
}

/**
 * Возвращает список всех типов цен из базы данных
 * @return array Список типов цен
 */
function mstoreprice_get_price_types() {
    global $db, $db_mstore_price_types;
    return $db->query("SELECT * FROM $db_mstore_price_types ORDER BY title")->fetchAll();
}

/**
 * Получает все цены для указанного товара
 * @param int $product_id ID товара
 * @return array Список цен
 */
function mstoreprice_get_product_prices($product_id) {
    global $db, $db_mstore_currency, $db_mstore_price_types, $db_mstore_prices;

    return $db->query("SELECT p.*, 
                             c.code AS currency_code, 
                             c.title AS currency_title, 
                             t.code AS price_type_code,
                             t.title AS price_type_title
                      FROM $db_mstore_prices p
                      LEFT JOIN $db_mstore_currency c ON p.currency_id = c.id
                      LEFT JOIN $db_mstore_price_types t ON p.price_type_id = t.id
                      WHERE p.product_id = ?", $product_id)->fetchAll();
}


/**
 * Сохраняет цены для указанного товара
 * @param int $product_id ID товара
 * @param array $prices Массив цен (currency_id, price_type_id, price)
 */
function mstoreprice_save_product_prices($product_id, $prices) {
    global $db, $db_x, $db_mstore_currency, $db_mstore_price_types, $db_mstore_prices;
    
    $db->delete($db_mstore_prices, "product_id = ?", $product_id);
    
    if (!empty($prices) && is_array($prices)) {
        foreach ($prices as $price) {
            if (!empty($price['currency_id']) && !empty($price['price_type_id']) && isset($price['price'])) {
                $db->insert($db_mstore_prices, [
                    'product_id' => $product_id,
                    'currency_id' => (int)$price['currency_id'],
                    'price_type_id' => (int)$price['price_type_id'],
                    'price' => (float)$price['price']
                ]);
            }
        }
    }
}

/**
 * Форматирует цену с указанием валюты
 * @param float $price Цена
 * @param string $currency_code Код валюты
 * @return string Отформатированная цена
 */
function mstoreprice_format_price($price, $currency_code) {
    return number_format($price, 2, '.', '') . ' ' . $currency_code;
}

/**
 * Проверяет валидность данных валюты
 * @param string $code Код валюты
 * @param string $title Название валюты
 * @param int|null $id ID валюты (для редактирования, чтобы исключить себя из проверки уникальности)
 * @return bool|string Возвращает true, если данные валидны, или ключ строки ошибки из локализации
 */
function mstoreprice_validate_currency($code, $title, $id = null) {
    global $db, $db_mstore_currency, $L;

    if (empty($code) || empty($title)) {
        return 'mstoreprice_error_empty_fields';
    }
    if (mb_strlen($code) > 10 || mb_strlen($title) > 50) {
        return 'mstoreprice_error_long_code_or_title';
    }
    if (!preg_match('/^[A-Za-z0-9_]+$/', $code)) {
        return 'mstoreprice_error_invalid_code_format';
    }

    $query = "SELECT COUNT(*) FROM $db_mstore_currency WHERE code = ? AND id != ?";
    $params = [$code, $id ?: 0];
    if ($db->query($query, $params)->fetchColumn() > 0) {
        return 'mstoreprice_error_currency_code_exists';
    }

    return true;
}

/**
 * Проверяет валидность данных типа цены
 * @param string $code Код типа цены
 * @param string $title Название типа цены
 * @param int|null $id ID типа цены (для редактирования, чтобы исключить себя из проверки уникальности)
 * @return bool|string Возвращает true, если данные валидны, или ключ строки ошибки из локализации
 */
function mstoreprice_validate_price_type($code, $title, $id = null) {
    global $db, $db_mstore_price_types, $L;

    if (empty($code) || empty($title)) {
        return 'mstoreprice_error_empty_fields';
    }
    if (mb_strlen($code) > 20 || mb_strlen($title) > 50) {
        return 'mstoreprice_error_long_code_or_title';
    }
    if (!preg_match('/^[A-Za-z0-9_]+$/', $code)) {
        return 'mstoreprice_error_invalid_code_format';
    }

    $query = "SELECT COUNT(*) FROM $db_mstore_price_types WHERE code = ? AND id != ?";
    $params = [$code, $id ?: 0];
    if ($db->query($query, $params)->fetchColumn() > 0) {
        return 'mstoreprice_error_price_type_code_exists';
    }

    return true;
}
?>