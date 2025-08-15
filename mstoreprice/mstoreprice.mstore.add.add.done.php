<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=mstore.add.add.done,mstore.edit.update.done
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('mstore', 'module');
require_once cot_incfile('mstoreprice', 'plug', 'functions');

global $id, $db, $db_x;

$db_mstore_prices = $db_x . 'mstore_prices';

// Удалим старые записи по product_id
$db->delete($db_mstore_prices, 'product_id = ?', [$id]);

if (isset($_POST['mstoreprice_prices']) && is_array($_POST['mstoreprice_prices'])) {
    foreach ($_POST['mstoreprice_prices'] as $type_id => $data) {
        $price = (float)str_replace(',', '.', $data['price']);
        $currency_id = isset($data['currency_id']) ? (int)$data['currency_id'] : 1;

        if ($price > 0) {
            $db->insert($db_mstore_prices, [
                'product_id' => $id,
                'price_type_id' => (int)$type_id,
                'currency_id' => $currency_id,
                'price' => $price
            ]);
        }
    }
}
