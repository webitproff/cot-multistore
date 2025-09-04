<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=standalone
[END_COT_EXT]
==================== */

/**
 * MStore Email Order plugin: standalone. CMF Cotonti Siena v.0.9.26 PHP 8.4 
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
    if (isset($_GET['id'])) {
        $m = 'details';
    } else {
        $m = 'new';
    }
}

require_once cot_incfile('mstoremailorder', 'plug', $m);

?>
