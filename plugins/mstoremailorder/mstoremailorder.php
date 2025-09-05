<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=standalone
[END_COT_EXT]
==================== */

/**
 * MStore Email Order plugin: standalone for Mode choice
 * Filename: mstoremailorder.php
 * @package MStoreEmailOrder for CMF Cotonti Siena v.0.9.26 on PHP 8.4
 * Version=2.0.1
 * Date=2025-09-05
 * @author webitproff
 * @copyright Copyright (c) 2025 webitproff | https://github.com/webitproff
 * @license BSD License
 */
 
defined('COT_CODE') && defined('COT_PLUG') or die('Wrong URL');

require_once cot_incfile('mstoremailorder', 'plug');
require_once cot_incfile('mstore', 'module');
require_once cot_incfile('users', 'module');
require_once cot_incfile('extrafields');

// Mode choice
// Mode incoming - страница списков входящие заказы
// Mode outgoing - страница списков исходящие заказы
// Mode complaint - страница с формой жалобы на заказ
// Mode details - страница со всеми деталями конкретного заказа
// Mode new - страница с формой создания нового заказа
if (!in_array($m, ['incoming', 'outgoing', 'complaint'])) {
    if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
        $m = 'details'; // Режим просмотра деталей заказа
    } else {
	// Инициализация $item_id из GET или POST
	// импортируем id товара для создания заказа из mstoremailorder.new.php
		$item_id = cot_import('item_id', 'G', 'INT') ?: cot_import('item_id', 'P', 'INT'); 
        // Проверяем $item_id для режима создания заказа
        if (cot_mstoremailorder_block_id_empty()) {
            $m = 'new';
        }
        // Если $item_id некорректен, функция cot_mstoremailorder_block_id_empty()

    }
}
require_once cot_incfile('mstoremailorder', 'plug', $m);

?>
