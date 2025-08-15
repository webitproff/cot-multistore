<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=mstore.tags
 * Tags=mstore.tpl:{MSTOREPRICE_PRICES}
 * [END_COT_EXT]
 */
defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('mstore', 'module');
require_once cot_incfile('mstoreprice', 'plug', 'functions');

$prices = mstoreprice_get_product_prices($item['msitem_id']);

$dl_html = '<dl class="row">';

foreach ($prices as $price) {
    // Безопасно подставляем данные в html
    $price_type_title = htmlspecialchars($price['price_type_title']); // Розничная цена, Оптовая цена...
    $price_val = number_format($price['price'], 2, '.', ''); // 4000.00
    $currency_code = htmlspecialchars($price['currency_code']); // USD, EUR
    $currency_title = htmlspecialchars($price['currency_title']); // Доллар США, Евро

    $dl_html .= '<dt class="col-sm-4">' . $price_type_title . '</dt>';
    $dl_html .= '<dd class="col-sm-8">' . $price_val . ' ' . $currency_code;

    // Добавляем в скобках название валюты, если есть
    if ($currency_title && $currency_title !== $currency_code) {
        $dl_html .= ' (' . $currency_title . ')';
    }
    $dl_html .= '</dd>';
}

$dl_html .= '</dl>';

$t->assign('MSTOREPRICE_PRICES', $dl_html);
