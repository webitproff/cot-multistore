<?php
/**
 * MStore Email Order plugin: outgoing orders
 * Filename: mstoremailorder.outgoing.php
 * @package MStoreEmailOrder for CMF Cotonti Siena v.0.9.26 on PHP 8.4
 * Version=2.0.1
 * Date=2025-09-05
 * @author webitproff
 * @copyright Copyright (c) 2025 webitproff | https://github.com/webitproff
 * @license BSD License
 */

defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('mstoremailorder', 'plug', 'functions');

global $db, $db_x, $L, $sys, $usr, $cfg;

$db_mstoremailorders = $db_x . 'mstoremailorders';
$db_mstoremailorder_history = $db_x . 'mstoremailorder_history';
$db_mstore = $db_x . 'mstore';
$db_users = $db_x . 'users';

$pluginDir = $cfg['plugins_dir'] ?? __DIR__;

// Импорт параметров
$id            = cot_import('id', 'G', 'INT');
$item_id       = cot_import('item_id', 'G', 'INT') ?: cot_import('item_id', 'P', 'INT');
$submit        = cot_import('submit', 'P', 'TXT');
$order_id      = cot_import('order_id', 'G', 'INT');
$new_status    = cot_import('new_status', 'P', 'INT');
$filter_status = cot_import('filter_status', 'G', 'TXT');
$search        = cot_import('search', 'G', 'TXT');

// Количество записей на страницу
$maxrowsperpage = (int)($cfg['plugin']['mstoremailorder']['list_maxrowsperpage'] ?? $cfg['maxrowsperpage'] ?: 10);

// Текущая страница
$page = cot_import('d', 'G', 'INT');
if (!$page || $page < 1) $page = 1;

// Логирование
mstoremailorder_log("User ID: {$usr['id']}, Mode: outgoing, Page: $page, Max rows per page: $maxrowsperpage", $pluginDir);

$t = new XTemplate(cot_tplfile('mstoremailorder.outgoing', 'plug'));

// Проверка авторизации
if ($usr['id'] == 0) {
    mstoremailorder_log("User not logged in.", $pluginDir);
    cot_message("Please log in to view your orders.", 'error');
} else {

    // Формируем условия выборки
    $where = ["o.order_user_id = ?"];
    $params = [$usr['id']];

    if ($filter_status !== '' && $filter_status !== null) {
        $where[] = "o.order_status = ?";
        $params[] = $filter_status;
    }

    if ($search) {
        $where[] = "(o.order_email LIKE ? OR COALESCE(m.msitem_title, '') LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }

    $where_sql = $where ? "WHERE " . implode(' AND ', $where) : '';

    // Общее количество заказов
    $totallines = Cot::$db->query("
        SELECT COUNT(*) 
        FROM $db_mstoremailorders AS o
        LEFT JOIN $db_mstore AS m ON m.msitem_id = o.order_item_id
        $where_sql", $params)->fetchColumn();

    mstoremailorder_log("Total orders: $totallines", $pluginDir);

    // Вычисляем смещение
    $offset = ($page - 1) * $maxrowsperpage;

    // Получаем заказы для текущей страницы
    $orders = Cot::$db->query("
        SELECT o.*, COALESCE(m.msitem_title, 'Item not found') AS msitem_title
        FROM $db_mstoremailorders AS o
        LEFT JOIN $db_mstore AS m ON m.msitem_id = o.order_item_id
        $where_sql
        ORDER BY o.order_date DESC
        LIMIT ?, ?", array_merge($params, [$offset, $maxrowsperpage]))->fetchAll();

    // Пагинация
    $pagenav = cot_pagenav(
        'plug',
        "e=mstoremailorder&m=outgoing" . ($filter_status ? "&filter_status=$filter_status" : '') . ($search ? "&search=$search" : ''),
        $offset, // передаем смещение
        $totallines,
        $maxrowsperpage,
        'd',
        '',
        Cot::$cfg['jquery'] && $cfg['turnajax']
    );

    // Если заказов нет
    if (empty($orders)) {
        cot_message("No outgoing orders found.", 'warning');
        mstoremailorder_log("No orders for user {$usr['id']}", $pluginDir);
    } else {
        $i = 0;
        foreach ($orders as $order) {
            $i++;

            $status_text = match($order['order_status']) {
                1 => $L['mstoremailorder_status_processing'] ?? 'Processing',
                2 => $L['mstoremailorder_status_completed'] ?? 'Completed',
                3 => $L['mstoremailorder_status_canceled'] ?? 'Canceled',
                4 => $L['mstoremailorder_status_rejected'] ?? 'Rejected',
                default => $L['mstoremailorder_status_new'] ?? 'New',
            };

            // История заказа
            $history = Cot::$db->query("
                SELECT * 
                FROM $db_mstoremailorder_history 
                WHERE order_id = ? 
                ORDER BY change_date DESC", [$order['order_id']])->fetchAll();

            foreach ($history as $hist) {
                $hist_status_text = match($hist['status']) {
                    1 => $L['mstoremailorder_status_processing'] ?? 'Processing',
                    2 => $L['mstoremailorder_status_completed'] ?? 'Completed',
                    3 => $L['mstoremailorder_status_canceled'] ?? 'Canceled',
                    4 => $L['mstoremailorder_status_rejected'] ?? 'Rejected',
                    default => $L['mstoremailorder_status_new'] ?? 'New',
                };
                $t->assign([
                    'HISTORY_STATUS_TEXT' => htmlspecialchars($hist_status_text, ENT_QUOTES, 'UTF-8'),
                    'HISTORY_DATE' => cot_date('datetime_full', $hist['change_date'] ?? $sys['now']),
                ]);
                $t->parse('MAIN.OUTGOING.HISTORY');
            }

            // Теги товара, покупателя и продавца
            $t->assign(cot_generate_mstoretags($order['order_item_id'], 'ORDER_ITEM_', $cfg['mstore']['shorttextlen'] ?? 255, $usr['isadmin'], $cfg['homebreadcrumb']));
            $t->assign($order['order_user_id'] > 0 ? cot_generate_usertags($order['order_user_id'], 'ORDER_BUYER_') : [
                'ORDER_BUYER_NAME'  => htmlspecialchars($order['order_buyer_nickname'] ?? $order['order_email'], ENT_QUOTES, 'UTF-8'),
                'ORDER_BUYER_EMAIL' => htmlspecialchars($order['order_email'], ENT_QUOTES, 'UTF-8'),
            ]);
            $t->assign(cot_generate_usertags($order['order_seller_id'], 'ORDER_SELLER_'));

            $t->assign([
                'ORDER_ID'          => $order['order_id'],
                'ORDER_QUANTITY'    => $order['order_quantity'],
                'ORDER_COMMENT'     => htmlspecialchars($order['order_comment'], ENT_QUOTES, 'UTF-8'),
                'ORDER_DATE'        => cot_date('datetime_full', $order['order_date']),
                'ORDER_STATUS'      => $order['order_status'],
                'ORDER_STATUS_TEXT' => htmlspecialchars($status_text, ENT_QUOTES, 'UTF-8'),
                'ORDER_ODDEVEN'     => cot_build_oddeven($i),
                'ORDER_I'           => $i,
                'ORDER_COST'        => number_format($order['order_costdflt'], 2, '.', ''),
                'ORDER_DETAILS_URL' => cot_url('plug', "e=mstoremailorder&m=details&id={$order['order_id']}"),
                'COMPLAINT_URL'     => cot_url('plug', "e=mstoremailorder&m=complaint&order_id={$order['order_id']}", '', true),
            ]);
            $t->parse('MAIN.OUTGOING');
        }

        $t->assign([
            'OUTGOING_COUNT' => count($orders),
        ]);
    }
}

// Передаем пагинацию в шаблон
$t->assign(cot_generatePaginationTags($pagenav));

// Формируем фильтры и форму
$t->assign([
    'FORM_URL' => cot_url('plug', "e=mstoremailorder&m=outgoing" . ($filter_status ? "&filter_status=$filter_status" : '') . ($search ? "&search=$search" : '')),
    'FILTER_STATUS_0_SELECTED'=> ($filter_status === '' || $filter_status === null) ? 'selected' : '',
    'FILTER_STATUS_1_SELECTED'=> ($filter_status === '1') ? 'selected' : '',
    'FILTER_STATUS_2_SELECTED'=> ($filter_status === '2') ? 'selected' : '',
    'FILTER_STATUS_3_SELECTED'=> ($filter_status === '3') ? 'selected' : '',
    'FILTER_STATUS_4_SELECTED'=> ($filter_status === '4') ? 'selected' : '',
    'SEARCH' => htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8'),
]);

cot_display_messages($t);
