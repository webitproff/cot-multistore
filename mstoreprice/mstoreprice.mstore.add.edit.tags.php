<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=mstore.add.tags,mstore.edit.tags
Tags=mstore.add.tpl:{MSTOREPRICE_FORM};mstore.edit.tpl:{MSTOREPRICE_FORM}
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('mstoreprice', 'plug');

global $db, $db_x, $db_mstore_currency, $db_mstore_price_types, $db_mstore_prices;
$db_mstore_currency = $db_x . 'mstore_currency';
$db_mstore_price_types = $db_x . 'mstore_price_types';
$db_mstore_prices = $db_x . 'mstore_prices';

$currencies = mstoreprice_get_currencies(); // id => currency
$price_types = mstoreprice_get_price_types(); // id => type
$product_prices = $id ? mstoreprice_get_product_prices($id) : [];

// Индексация по price_type_id для удобства
$prices_by_type = [];
foreach ($product_prices as $price) {
    $prices_by_type[$price['price_type_id']] = $price;
}

$form_html = '<div class="mstoreprice-block">';
$form_html .= '<h3>' . cot::$L['mstoreprice_prices'] . '</h3>';
$form_html .= '<table class="table table-sm table-bordered mstoreprice-table">';
$form_html .= '<thead><tr>';
$form_html .= '<th>' . cot::$L['mstoreprice_price_type'] . '</th>';
$form_html .= '<th>' . cot::$L['mstoreprice_currency'] . '</th>';
$form_html .= '<th>' . cot::$L['mstoreprice_price'] . '</th>';
$form_html .= '</tr></thead><tbody>';

foreach ($price_types as $type) {
    $type_id = $type['id'];
    $existing = isset($prices_by_type[$type_id]) ? $prices_by_type[$type_id] : null;

    $form_html .= '<tr>';
    $form_html .= '<td>' . htmlspecialchars($type['title']) . '</td>';

    // select валют
    $form_html .= '<td><select name="mstoreprice_prices[' . $type_id . '][currency_id]" class="form-select form-select-sm">';
    foreach ($currencies as $currency) {
        $selected = ($existing && $existing['currency_id'] == $currency['id']) ? ' selected' : '';
        $form_html .= '<option value="' . $currency['id'] . '"' . $selected . '>';
        $form_html .= htmlspecialchars($currency['title']) . ' (' . htmlspecialchars($currency['code']) . ')';
        $form_html .= '</option>';
    }
    $form_html .= '</select></td>';

    // input цены
$price_val = $existing ? htmlspecialchars($existing['price']) : '';
$form_html .= '<td><input type="text" class="form-control form-control-sm" name="mstoreprice_prices[' . $type_id . '][price]" value="' . $price_val . '"></td>';
    $form_html .= '</tr>';
}

$form_html .= '</tbody></table>';
$form_html .= '</div>';

$t->assign('MSTOREPRICE_FORM', $form_html);
