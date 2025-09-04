<?php
/**
 * MStore Email Order plugin: incoming orders
 * Filename: mstoremailorder.incoming.php
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
mstoremailorder_log("User ID: {$usr['id']}, Mode: incoming, Page: $page, Max rows per page: $maxrowsperpage", $pluginDir);

$t = new XTemplate(cot_tplfile('mstoremailorder.incoming', 'plug'));

// Проверка авторизации
if ($usr['id'] == 0) {
    mstoremailorder_log("User not logged in.", $pluginDir);
    cot_message("Please log in to view incoming orders.", 'error');
} else {

    // Обработка изменения статуса заказа
    if ($new_status !== null && $order_id) {
        $order = Cot::$db->query("SELECT * FROM $db_mstoremailorders WHERE order_id = ? AND order_seller_id = ?", [$order_id, $usr['id']])->fetch();
        if ($order) {
            Cot::$db->update($db_mstoremailorders, ['order_status' => $new_status], "order_id = ?", [$order_id]);
            Cot::$db->insert($db_mstoremailorder_history, [
                'order_id' => $order_id,
                'status' => $new_status,
                'change_date' => $sys['now']
            ]);
            $item = Cot::$db->query("SELECT * FROM $db_mstore WHERE msitem_id = ?", [$order['order_item_id']])->fetch();
            $status_email_body = mstoremailorder_generate_status_email($order, $item, $new_status);
            mstoremailorder_send_email($order['order_email'], $L['mstoremailorder_status_email_subject'], $status_email_body);
            cot_message($L['mstoremailorder_status_updated']);
            cot_redirect(cot_url('plug', "e=mstoremailorder&m=incoming", '', true));
        } else {
            mstoremailorder_log("Update failed: Order not found or user is not seller for order_id=$order_id", $pluginDir);
            cot_message("Order not found or you are not the seller.", 'warning');
        }
    }

    // Формируем условия для выборки
    $where = ["o.order_seller_id = ?"];
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

    mstoremailorder_log("Total incoming orders: $totallines", $pluginDir);

    // Смещение для SQL
    $offset = ($page - 1) * $maxrowsperpage;

    // Выборка заказов
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
        "e=mstoremailorder&m=incoming" . ($filter_status ? "&filter_status=$filter_status" : '') . ($search ? "&search=$search" : ''),
        $offset,
        $totallines,
        $maxrowsperpage,
        'd',
        '',
        Cot::$cfg['jquery'] && $cfg['turnajax']
    );

    // Отображение заказов
    if (!empty($orders)) {
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

            // История
            $history = Cot::$db->query("SELECT * FROM $db_mstoremailorder_history WHERE order_id = ? ORDER BY change_date DESC", [$order['order_id']])->fetchAll();
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
                $t->parse('MAIN.INCOMING.HISTORY');
            }

            // Теги
            $t->assign(cot_generate_mstoretags($order['order_item_id'], 'ORDER_ITEM_', $cfg['mstore']['shorttextlen'] ?? 255, $usr['isadmin'], $cfg['homebreadcrumb']));
            $t->assign($order['order_user_id'] > 0 ? cot_generate_usertags($order['order_user_id'], 'ORDER_BUYER_') : [
                'ORDER_BUYER_NAME' => htmlspecialchars($order['order_buyer_nickname'] ?? $order['order_email'], ENT_QUOTES, 'UTF-8'),
                'ORDER_BUYER_EMAIL' => htmlspecialchars($order['order_email'], ENT_QUOTES, 'UTF-8'),
            ]);
            $t->assign(cot_generate_usertags($order['order_seller_id'], 'ORDER_SELLER_'));

            $t->assign([
                'ORDER_ID' => $order['order_id'],
                'ORDER_QUANTITY' => $order['order_quantity'],
                'ORDER_COMMENT' => htmlspecialchars($order['order_comment'], ENT_QUOTES, 'UTF-8'),
                'ORDER_DATE' => cot_date('datetime_full', $order['order_date']),
                'ORDER_STATUS' => $order['order_status'],
                'ORDER_STATUS_TEXT' => htmlspecialchars($status_text, ENT_QUOTES, 'UTF-8'),
                'ORDER_ODDEVEN' => cot_build_oddeven($i),
                'ORDER_I' => $i,
                'ORDER_COST' => number_format($order['order_costdflt'], 2, '.', ''),
                'ORDER_UPDATE_URL' => cot_url('plug', "e=mstoremailorder&m=incoming&order_id={$order['order_id']}"),
                'ORDER_DETAILS_URL' => cot_url('plug', "e=mstoremailorder&m=details&id={$order['order_id']}"),
                'ORDER_STATUS_0_SELECTED' => ($order['order_status'] == 0) ? 'selected' : '',
                'ORDER_STATUS_1_SELECTED' => ($order['order_status'] == 1) ? 'selected' : '',
                'ORDER_STATUS_2_SELECTED' => ($order['order_status'] == 2) ? 'selected' : '',
                'ORDER_STATUS_3_SELECTED' => ($order['order_status'] == 3) ? 'selected' : '',
                'ORDER_STATUS_4_SELECTED' => ($order['order_status'] == 4) ? 'selected' : '',
            ]);

            $t->parse('MAIN.INCOMING');
        }
        $t->assign([
            'INCOMING_COUNT' => count($orders),
        ]);
    } else {
        cot_message("No incoming orders found.", 'warning');
    }
}

// Пагинация
$t->assign(cot_generatePaginationTags($pagenav));
$t->assign([
    'FORM_URL' => cot_url('plug', "e=mstoremailorder&m=incoming" . ($filter_status ? "&filter_status=$filter_status" : '') . ($search ? "&search=$search" : '')),
    'FILTER_STATUS_0_SELECTED'=> ($filter_status === '' || $filter_status === null) ? 'selected' : '',
    'FILTER_STATUS_1_SELECTED'=> ($filter_status === '1') ? 'selected' : '',
    'FILTER_STATUS_2_SELECTED'=> ($filter_status === '2') ? 'selected' : '',
    'FILTER_STATUS_3_SELECTED'=> ($filter_status === '3') ? 'selected' : '',
    'FILTER_STATUS_4_SELECTED'=> ($filter_status === '4') ? 'selected' : '',
    'SEARCH' => htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8'),
]);

cot_display_messages($t);
