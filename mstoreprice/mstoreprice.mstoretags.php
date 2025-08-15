<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=mstoretags.main
 * Tags=mstore.list.tpl:{LIST_ROW_PRICE_ROWS_HTML}
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('mstore', 'module');
require_once cot_incfile('mstoreprice', 'plug');

if (is_array($item_data) && !empty($item_data['msitem_id'])) {
    $prices = mstoreprice_get_product_prices((int)$item_data['msitem_id']);
    $price_rows_html = '';
    if (is_array($prices) && !empty($prices)) {
        $price_rows_html .= '<div class="mstore-price-list">';
        foreach ($prices as $price) {
            $type_title = htmlspecialchars($price['price_type_title']);
            $formatted_price = mstoreprice_format_price($price['price'], $price['currency_code']);
            $currency_title = htmlspecialchars($price['currency_title']);
            $price_rows_html .= '
                <div class="row mb-1">
                    <div class="col-5 fw-bold">' . $type_title . '</div>
                    <div class="col-7">' . $formatted_price . ' <small>(' . $currency_title . ')</small></div>
                </div>';
        }
        $price_rows_html .= '</div>';
    }
    $temp_array['PRICE_ROWS_HTML'] = $price_rows_html;
}
?>