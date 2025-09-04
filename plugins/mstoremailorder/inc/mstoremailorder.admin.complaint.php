<?php
/**
 * MStore Email Order plugin: admin complaint management
 * Filename: mstoremailorder.admin.complaint.php
 * @package MStoreEmailOrder for CMF Cotonti Siena v.0.9.26 on PHP 8.4
 * Version=2.0.1
 * Date=2025-09-05
 * @author webitproff
 * @copyright Copyright (c) 2025 webitproff
 * @license BSD License
 */
defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('mstoremailorder', 'plug', 'functions');

global $db, $db_x, $L, $sys, $usr, $cfg;
cot_block(Cot::$usr['isadmin']);

$db_mstoremailorders           = $db_x . 'mstoremailorders';
$db_mstoremailorder_complaint  = $db_x . 'mstoremailorder_complaint';
$db_mstoremailorder_history    = $db_x . 'mstoremailorder_history';
$db_mstore                     = $db_x . 'mstore';
$db_users                      = $db_x . 'users';

$pluginDir = isset($cfg['plugins_dir']) ? $cfg['plugins_dir'] . '/mstoremailorder' : __DIR__ . '/mstoremailorder';

$a             = cot_import('a', 'G', 'ALP');
$complaint_id  = cot_import('complaint_id', 'G', 'INT');
$order_id      = cot_import('order_id', 'G', 'INT');
$new_status    = cot_import('new_status', 'P', 'INT');
$filter_status = cot_import('filter_status', 'G', 'TXT');

// --- Настройки пагинации ---
$maxrowsperpage = (int)($cfg['plugin']['mstoremailorder']['list_maxrowsperpage'] ?? $cfg['maxrowsperpage'] ?? 20);
$page   = cot_import('d', 'G', 'INT');
if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $maxrowsperpage;

$t = new XTemplate(cot_tplfile('mstoremailorder.admin.complaint', 'plug'));

// ===== Обновление статуса =====
if ($a === 'update' && $complaint_id && isset($new_status)) {
    $complaint = Cot::$db->query("SELECT * FROM $db_mstoremailorder_complaint WHERE complaint_id = ?", [$complaint_id])->fetch();

    if ($complaint) {
        Cot::$db->update($db_mstoremailorder_complaint, ['complaint_status' => $new_status], "complaint_id = ?", [$complaint_id]);
        mstoremailorder_log("Admin updated complaint status for complaint_id=$complaint_id to $new_status", $pluginDir);
        cot_message($L['mstoremailorder_complaint_status_updated']);

        // ===== Получение всех данных после обновления для уведомлений =====
        $complaint_full = Cot::$db->query("
            SELECT c.*, o.*, m.msitem_title, u.user_email AS buyer_email, s.user_email AS seller_email
            FROM $db_mstoremailorder_complaint AS c
            LEFT JOIN $db_mstoremailorders AS o ON o.order_id = c.order_id
            LEFT JOIN $db_mstore AS m ON m.msitem_id = o.order_item_id
            LEFT JOIN $db_users AS u ON u.user_id = o.order_user_id
            LEFT JOIN $db_users AS s ON s.user_id = o.order_seller_id
            WHERE c.complaint_id = ?
        ", [$complaint_id])->fetch();

        if ($complaint_full) {
            mstoremailorder_notify_complaint_status($complaint_full);
        }

        cot_redirect(cot_url('admin', "m=other&p=mstoremailorder&sp=complaint&order_id={$complaint['order_id']}", '', true));
    } else {
        mstoremailorder_log("Admin update failed: Complaint not found for complaint_id=$complaint_id", $pluginDir);
        cot_message("Complaint not found.", 'warning');
    }
}

// ===== Получение списка жалоб =====
$where = [];
$params = [];

if ($order_id) {
    $where[] = "c.order_id = ?";
    $params[] = $order_id;
}
if ($filter_status !== '' && $filter_status !== null) {
    $where[] = "c.complaint_status = ?";
    $params[] = $filter_status;
}
$where_sql = $where ? "WHERE " . implode(' AND ', $where) : '';

// --- Общее количество жалоб ---
$totallines = Cot::$db->query("
    SELECT COUNT(*) 
    FROM $db_mstoremailorder_complaint AS c
    LEFT JOIN $db_mstoremailorders AS o ON o.order_id = c.order_id
    LEFT JOIN $db_mstore AS m ON m.msitem_id = o.order_item_id
    $where_sql
", $params)->fetchColumn();

// --- Пагинация ---
$pagenav = cot_pagenav(
    'admin',
    "m=other&p=mstoremailorder&sp=complaint" 
        . ($filter_status ? "&filter_status=$filter_status" : '') 
        . ($order_id ? "&order_id=$order_id" : ''),
    $offset,
    $totallines,
    $maxrowsperpage,
    'd'
);

// --- Выборка жалоб ---
$complaints = Cot::$db->query("
    SELECT c.*, o.*, m.*, u.user_name AS complainant_name
    FROM $db_mstoremailorder_complaint AS c
    LEFT JOIN $db_mstoremailorders AS o ON o.order_id = c.order_id
    LEFT JOIN $db_mstore AS m ON m.msitem_id = o.order_item_id
    LEFT JOIN $db_users AS u ON u.user_id = c.user_id
    $where_sql
    ORDER BY c.complaint_date DESC
    LIMIT ?, ?
", array_merge($params, [$offset, $maxrowsperpage]))->fetchAll();

// --- Вывод жалоб ---
$i = $offset;
foreach ($complaints as $complaint) {
    $i++;
    $status_text = match($complaint['complaint_status']) {
        1 => $L['mstoremailorder_complaint_status_processing'] ?? 'Processing',
        2 => $L['mstoremailorder_complaint_status_resolved'] ?? 'Resolved',
        default => $L['mstoremailorder_complaint_status_new'] ?? 'New',
    };

    $t->assign(cot_generate_mstoretags($complaint['order_item_id'], 'ORDER_ITEM_', $cfg['mstore']['shorttextlen'] ?? 255, true, $cfg['homebreadcrumb']));
    $t->assign(cot_generate_usertags($complaint['order_seller_id'], 'ORDER_SELLER_'));
    if ($complaint['order_user_id'] > 0) {
        $t->assign(cot_generate_usertags($complaint['order_user_id'], 'ORDER_BUYER_'));
    } else {
        $t->assign([
            'ORDER_BUYER_NAME'  => htmlspecialchars($complaint['order_buyer_nickname'] ?? $complaint['order_email'], ENT_QUOTES, 'UTF-8'),
            'ORDER_BUYER_EMAIL' => htmlspecialchars($complaint['order_email'], ENT_QUOTES, 'UTF-8'),
        ]);
    }

    $t->assign([
        'COMPLAINT_ID'              => $complaint['complaint_id'],
        'COMPLAINT_TEXT'            => htmlspecialchars($complaint['complaint_text'], ENT_QUOTES, 'UTF-8'),
        'COMPLAINT_DATE'            => cot_date('datetime_full', $complaint['complaint_date'] ?? $sys['now']),
        'COMPLAINT_STATUS'          => $complaint['complaint_status'],
        'COMPLAINT_STATUS_TEXT'     => htmlspecialchars($status_text, ENT_QUOTES, 'UTF-8'),
        'COMPLAINT_UPDATE_URL'      => cot_url('admin', "m=other&p=mstoremailorder&sp=complaint&a=update&complaint_id={$complaint['complaint_id']}"),
        'ORDER_ID'                  => $complaint['order_id'],
        'ORDER_DETAILS_URL'         => cot_url('plug', "e=mstoremailorder&m=details&id={$complaint['order_id']}", '', true),
        'ORDER_QUANTITY'            => $complaint['order_quantity'],
        'ORDER_EMAIL'               => htmlspecialchars($complaint['order_email'], ENT_QUOTES, 'UTF-8'),
        'ORDER_PHONE'               => htmlspecialchars($complaint['order_phone'], ENT_QUOTES, 'UTF-8'),
        'ORDER_COMMENT'             => htmlspecialchars($complaint['order_comment'], ENT_QUOTES, 'UTF-8'),
        'ORDER_DATE'                => cot_date('datetime_full', $complaint['order_date'] ?? $sys['now']),
        'ORDER_COST'                => number_format($complaint['order_costdflt'], 2, '.', ''),
        'COMPLAINT_STATUS_0_SELECTED' => ($complaint['complaint_status'] == 0) ? 'selected' : '',
        'COMPLAINT_STATUS_1_SELECTED' => ($complaint['complaint_status'] == 1) ? 'selected' : '',
        'COMPLAINT_STATUS_2_SELECTED' => ($complaint['complaint_status'] == 2) ? 'selected' : '',
        'COMPLAINT_ODDEVEN'         => cot_build_oddeven($i),
        'COMPLAINT_I'               => $i,
    ]);
    $t->parse('MAIN.COMPLAINTS');
}

// --- Пагинация в шаблон ---
$t->assign(cot_generatePaginationTags($pagenav));

$t->assign([
    'FILTER_STATUS_0_SELECTED' => ($filter_status === '0') ? 'selected' : '',
    'FILTER_STATUS_1_SELECTED' => ($filter_status === '1') ? 'selected' : '',
    'FILTER_STATUS_2_SELECTED' => ($filter_status === '2') ? 'selected' : '',
    'ORDER_ID'                 => $order_id ?? '',
    'FORM_URL'                 => cot_url('admin', 'm=other&p=mstoremailorder&sp=complaint' 
        . ($filter_status ? "&filter_status=$filter_status" : '') 
        . ($order_id ? "&order_id=$order_id" : '')),
]);

cot_display_messages($t);
$t->parse();
$pluginBody = $t->text('MAIN');
?>
