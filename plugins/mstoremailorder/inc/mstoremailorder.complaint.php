<?php
/**
 * MStore Email Order plugin: complaint form
 * Filename: mstoremailorder.complaint.php
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

$db_mstoremailorders          = $db_x . 'mstoremailorders';
$db_mstoremailorder_history   = $db_x . 'mstoremailorder_history';
$db_mstoremailorder_complaint = $db_x . 'mstoremailorder_complaint';
$db_mstore                    = $db_x . 'mstore';
$db_users                     = $db_x . 'users';

$pluginDir = isset($cfg['plugins_dir']) ? $cfg['plugins_dir'] . '/mstoremailorder' : __DIR__ . '/mstoremailorder';

$order_id = cot_import('order_id', 'G', 'INT') ?: cot_import('order_id', 'P', 'INT');
$submit   = cot_import('submit', 'P', 'TXT');

$t = new XTemplate(cot_tplfile('mstoremailorder.complaint', 'plug'));

// Проверка авторизации пользователя
if ($usr['id'] == 0) {
    // Сохраняем URL для возврата после авторизации
    $redirect = base64_encode(cot_url('plug', "e=mstoremailorder&m=complaint&order_id=$order_id", '', true));
    cot_redirect(cot_url('login', "redirect=$redirect", '', true));
}

/* === Отправка жалобы === */
if ($submit && $order_id) {
    $complaint_text = cot_import('complaint_text', 'P', 'HTM');

    if (empty($complaint_text)) {
        cot_error($L['mstoremailorder_complaint_error_text']);
    }

    // Проверка доступа к заказу + получаем email и имя покупателя и продавца
    $order = Cot::$db->query(
        "SELECT o.*, m.msitem_title, 
                u1.user_email AS buyer_email, u1.user_name AS buyer_name,
                u2.user_email AS seller_email
         FROM $db_mstoremailorders AS o
         LEFT JOIN $db_mstore AS m ON m.msitem_id = o.order_item_id
         LEFT JOIN $db_users AS u1 ON u1.user_id = o.order_user_id
         LEFT JOIN $db_users AS u2 ON u2.user_id = o.order_seller_id
         WHERE o.order_id = ? AND (o.order_user_id = ? OR o.order_seller_id = ?)",
        [$order_id, $usr['id'], $usr['id']]
    )->fetch();

    if (!$order) {
        cot_error($L['mstoremailorder_error_order_not_found']);
    }

    if (!cot_error_found()) {
        $complaint = [
            'order_id'         => $order_id,
            'user_id'          => $usr['id'],
            'complaint_text'   => $complaint_text,
            'complaint_date'   => $sys['now'],
            'complaint_status' => 0,
        ];

        try {
            Cot::$db->insert($db_mstoremailorder_complaint, $complaint);

            // Определяем имя автора жалобы
            $complaint_user_name = $usr['user_name'] ?: ($order['buyer_name'] ?? 'User');

            // Формируем и отправляем уведомления
            $body = mstoremailorder_generate_complaint_email($order, $complaint_text, $complaint_user_name);

            // Админу
            mstoremailorder_send_email(Cot::$cfg['adminemail'], $L['mstoremailorder_complaint_subject'], $body);
            // Продавцу
            if (!empty($order['seller_email'])) {
                mstoremailorder_send_email($order['seller_email'], $L['mstoremailorder_complaint_subject'], $body);
            }
            // Покупателю
            if (!empty($order['buyer_email'])) {
                mstoremailorder_send_email($order['buyer_email'], $L['mstoremailorder_complaint_subject'], $body);
            }

            mstoremailorder_log("Complaint added for order #$order_id by user #{$usr['id']}", $pluginDir);

            cot_message($L['mstoremailorder_complaint_success']);
            cot_redirect(cot_url('plug', "e=mstoremailorder&m=outgoing", '', true));
        } catch (Exception $e) {
            cot_error('Ошибка базы данных: ' . $e->getMessage());
        }
    }
}

/* === Подготовка формы === */
if ($order_id) {
    $order = Cot::$db->query(
        "SELECT o.*, m.msitem_title 
         FROM $db_mstoremailorders AS o
         LEFT JOIN $db_mstore AS m ON m.msitem_id = o.order_item_id
         WHERE o.order_id = ? AND (o.order_user_id = ? OR o.order_seller_id = ?)",
        [$order_id, $usr['id'], $usr['id']]
    )->fetch();

    if ($order) {
        $t->assign([
            'ORDER_ID'         => $order['order_id'],
            'ORDER_ITEM_TITLE' => htmlspecialchars($order['msitem_title'] ?? 'Без названия', ENT_QUOTES, 'UTF-8'),
            'COMPLAINT_TEXT'   => '',
        ]);
    } else {
        cot_error($L['mstoremailorder_error_order_not_found']);
        $t->assign([
            'ORDER_ID'         => $order_id,
            'ORDER_ITEM_TITLE' => 'Заказ не найден',
            'COMPLAINT_TEXT'   => '',
        ]);
    }
}

cot_display_messages($t);