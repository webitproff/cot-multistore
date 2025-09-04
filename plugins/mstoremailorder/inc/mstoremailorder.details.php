<?php
/**
 * MStore Email Order plugin: order details
 * Filename: mstoremailorder.details.php
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
$db_mstoremailorder_complaint = $db_x . 'mstoremailorder_complaint';
$db_mstore = $db_x . 'mstore';
$db_users = $db_x . 'users';
$pluginDir = isset($cfg['plugins_dir']) ? $cfg['plugins_dir'] . '/mstoremailorder' : __DIR__ . '/mstoremailorder';
$order_id = cot_import('id', 'G', 'INT');
$new_status = cot_import('new_status', 'P', 'INT');
$t = new XTemplate(cot_tplfile('mstoremailorder.details', 'plug'));
// Проверка авторизации пользователя
if ($usr['id'] == 0) {
    // Сохраняем URL для возврата после авторизации
    $redirect = base64_encode(cot_url('plug', "e=mstoremailorder&m=details&id=$order_id", '', true));
    cot_redirect(cot_url('login', "redirect=$redirect", '', true));
}
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
        cot_redirect(cot_url('plug', "e=mstoremailorder&m=details&id=$order_id", '', true));
    } else {
        mstoremailorder_log("Update failed: Order not found or user is not seller for order_id=$order_id", $pluginDir);
        cot_message("Order not found or you are not the seller.", 'warning');
    }
}
if ($order_id) {
    $order = Cot::$db->query("
        SELECT o.*, m.*
        FROM $db_mstoremailorders AS o
        LEFT JOIN $db_mstore AS m ON m.msitem_id = o.order_item_id
        WHERE o.order_id = ? AND (o.order_user_id = ? OR o.order_seller_id = ? OR ?)",
        [$order_id, $usr['id'], $usr['id'], $usr['isadmin']]
    )->fetch();
    if ($order) {
        $status_text = $L['mstoremailorder_status_new'] ?? 'New';
        if ($order['order_status'] == 1) $status_text = $L['mstoremailorder_status_processing'] ?? 'Processing';
        elseif ($order['order_status'] == 2) $status_text = $L['mstoremailorder_status_completed'] ?? 'Completed';
        elseif ($order['order_status'] == 3) $status_text = $L['mstoremailorder_status_canceled'] ?? 'Canceled';
        elseif ($order['order_status'] == 4) $status_text = $L['mstoremailorder_status_rejected'] ?? 'Rejected';
        // Генерация тегов для товара
        $t->assign(cot_generate_mstoretags($order['order_item_id'], 'ORDER_ITEM_', $cfg['mstore']['shorttextlen'] ?? 255, $usr['isadmin'], $cfg['homebreadcrumb']));
        // Генерация тегов для покупателя
        if ($order['order_user_id'] > 0) {
            $t->assign(cot_generate_usertags($order['order_user_id'], 'ORDER_BUYER_'));
            // Явно задаём email покупателя
            $buyer = Cot::$db->query("SELECT user_email FROM $db_users WHERE user_id = ?", [$order['order_user_id']])->fetch();
            $t->assign([
                'ORDER_BUYER_EMAIL' => htmlspecialchars($buyer['user_email'] ?? $order['order_email'], ENT_QUOTES, 'UTF-8'),
            ]);
        } else {
            $t->assign([
                'ORDER_BUYER_NAME' => htmlspecialchars($order['order_buyer_nickname'] ?? $order['order_email'], ENT_QUOTES, 'UTF-8'),
                'ORDER_BUYER_EMAIL' => htmlspecialchars($order['order_email'], ENT_QUOTES, 'UTF-8'),
            ]);
        }
        // Генерация тегов для продавца
        $seller = Cot::$db->query("SELECT user_email FROM $db_users WHERE user_id = ?", [$order['order_seller_id']])->fetch();
        $t->assign(cot_generate_usertags($order['order_seller_id'], 'ORDER_SELLER_'));
        $t->assign([
            'ORDER_SELLER_EMAIL' => htmlspecialchars($seller['user_email'] ?? '', ENT_QUOTES, 'UTF-8'),
        ]);
        $history = Cot::$db->query("SELECT * FROM $db_mstoremailorder_history WHERE order_id = ? ORDER BY change_date DESC", [$order['order_id']])->fetchAll();
        foreach ($history as $hist) {
            $hist_status_text = $L['mstoremailorder_status_new'] ?? 'New';
            if ($hist['status'] == 1) $hist_status_text = $L['mstoremailorder_status_processing'] ?? 'Processing';
            elseif ($hist['status'] == 2) $hist_status_text = $L['mstoremailorder_status_completed'] ?? 'Completed';
            elseif ($hist['status'] == 3) $hist_status_text = $L['mstoremailorder_status_canceled'] ?? 'Canceled';
            elseif ($hist['status'] == 4) $hist_status_text = $L['mstoremailorder_status_rejected'] ?? 'Rejected';
            $t->assign([
                'HISTORY_STATUS_TEXT' => htmlspecialchars($hist_status_text, ENT_QUOTES, 'UTF-8'),
                'HISTORY_DATE' => cot_date('datetime_full', $hist['change_date'] ?? $sys['now']),
            ]);
            $t->parse('MAIN.HISTORY');
        }
        $complaints = Cot::$db->query("SELECT * FROM $db_mstoremailorder_complaint WHERE order_id = ? ORDER BY complaint_date DESC", [$order['order_id']])->fetchAll();
        $t->assign([
            'COMPLAINTS_COUNT' => count($complaints),
        ]);
        foreach ($complaints as $complaint) {
            $complaint_status_text = $L['mstoremailorder_complaint_status_new'] ?? 'New';
            if ($complaint['complaint_status'] == 1) $complaint_status_text = $L['mstoremailorder_complaint_status_processing'] ?? 'Processing';
            elseif ($complaint['complaint_status'] == 2) $complaint_status_text = $L['mstoremailorder_complaint_status_resolved'] ?? 'Resolved';
            $t->assign([
                'COMPLAINT_ID' => $complaint['complaint_id'],
                'COMPLAINT_TEXT' => htmlspecialchars($complaint['complaint_text'], ENT_QUOTES, 'UTF-8'),
                'COMPLAINT_DATE' => cot_date('datetime_full', $complaint['complaint_date'] ?? $sys['now']),
                'COMPLAINT_STATUS_TEXT' => htmlspecialchars($complaint_status_text, ENT_QUOTES, 'UTF-8'),
            ]);
            $t->parse('MAIN.COMPLAINTS');
        }
        $t->assign([
            'ORDER_ID' => $order['order_id'] ?? 0,
            'ORDER_QUANTITY' => $order['order_quantity'] ?? 0,
            'ORDER_EMAIL' => htmlspecialchars($order['order_email'] ?? '', ENT_QUOTES, 'UTF-8'),
            'ORDER_PHONE' => htmlspecialchars($order['order_phone'] ?? '', ENT_QUOTES, 'UTF-8'),
            'ORDER_COMMENT' => htmlspecialchars($order['order_comment'] ?? '', ENT_QUOTES, 'UTF-8'),
            'ORDER_DATE' => cot_date('datetime_full', $order['order_date'] ?? $sys['now']),
            'ORDER_STATUS' => $order['order_status'] ?? 0,
            'ORDER_STATUS_TEXT' => htmlspecialchars($status_text, ENT_QUOTES, 'UTF-8'),
            'ORDER_UPDATE_URL' => cot_url('plug', "e=mstoremailorder&m=details&id={$order['order_id']}"),
            'ORDER_STATUS_0_SELECTED' => ($order['order_status'] == 0) ? 'selected' : '',
            'ORDER_STATUS_1_SELECTED' => ($order['order_status'] == 1) ? 'selected' : '',
            'ORDER_STATUS_2_SELECTED' => ($order['order_status'] == 2) ? 'selected' : '',
            'ORDER_STATUS_3_SELECTED' => ($order['order_status'] == 3) ? 'selected' : '',
            'ORDER_STATUS_4_SELECTED' => ($order['order_status'] == 4) ? 'selected' : '',
            'ORDER_COST' => number_format($order['order_costdflt'], 2, '.', ''),
            'ORDER_BUYER_NICKNAME' => htmlspecialchars($order['order_buyer_nickname'] ?? '', ENT_QUOTES, 'UTF-8'),
            'ORDER_SELLER_NICKNAME' => htmlspecialchars($order['order_seller_nickname'] ?? '', ENT_QUOTES, 'UTF-8'),
            'COMPLAINT_URL' => cot_url('plug', "e=mstoremailorder&m=complaint&order_id={$order['order_id']}", '', true),
        ]);
    } else {
        mstoremailorder_log("Error: Order not found or user not authorized for order_id=$order_id", $pluginDir);
        cot_error('Order not found or you are not authorized.');
    }
}
cot_display_messages($t);
?>