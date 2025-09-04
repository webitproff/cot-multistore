<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=tools
[END_COT_EXT]
==================== */

/**
 * MStore Email Order plugin: admin tools
 * Filename: mstoremailorder.admin.php
 * @package MStoreEmailOrder for CMF Cotonti Siena v.0.9.26 on PHP 8.4
 * Version=2.0.1
 * Date=2025-09-05
 * @author webitproff
 * @copyright Copyright (c) 2025 webitproff | https://github.com/webitproff
 * @license BSD License
 */

defined('COT_CODE') or die('Wrong URL.');

require_once cot_incfile('mstoremailorder', 'plug', 'functions');

global $db, $db_x, $L, $sys, $cfg;
cot_block(Cot::$usr['isadmin']);

$adminHelp = Cot::$L['adminHelpInfo'];

// Subpage (complaints)
$sp = cot_import('sp', 'G', 'ALP');
if ($sp === 'complaint') {
    require_once __DIR__ . '/inc/mstoremailorder.admin.complaint.php';
    return;
}

// Генерация корректной ссылки на жалобы
$complaints_url = cot_url('admin', 'm=other&p=mstoremailorder&sp=complaint', '', false); // raw=false


$db_mstoremailorders = $db_x . 'mstoremailorders';
$db_mstoremailorder_history = $db_x . 'mstoremailorder_history';
$db_mstore = $db_x . 'mstore';
$db_users = $db_x . 'users';

// Inputs
$a = cot_import('a', 'G', 'ALP');
$order_id = cot_import('order_id', 'G', 'INT');
$new_status = cot_import('new_status', 'P', 'INT');
$filter_status = cot_import('filter_status', 'G', 'TXT');
$search = cot_import('search', 'G', 'TXT');

// Pagination settings
$maxrowsperpage = (int)($cfg['plugin']['mstoremailorder']['list_maxrowsperpage'] ?? 20);
if ($maxrowsperpage <= 0) $maxrowsperpage = 20;

$page = cot_import('d', 'G', 'INT');
if ($page < 1) $page = 1;
$offset = ($page - 1) * $maxrowsperpage;

$pluginDir = $cfg['plugins_dir'] ?? __DIR__;

// Handle status update
if ($a === 'update' && $order_id && isset($new_status)) {
    $order = Cot::$db->query("SELECT * FROM $db_mstoremailorders WHERE order_id = ?", [$order_id])->fetch();
    if ($order) {
        Cot::$db->update($db_mstoremailorders, ['order_status' => $new_status], "order_id = ?", [$order_id]);
        Cot::$db->insert($db_mstoremailorder_history, [
            'order_id' => $order_id,
            'status'   => $new_status,
            'change_date' => $sys['now']
        ]);
        $item = Cot::$db->query("SELECT * FROM $db_mstore WHERE msitem_id = ?", [$order['order_item_id']])->fetch();
        $status_email_body = mstoremailorder_generate_status_email($order, $item, $new_status);
        mstoremailorder_send_email($order['order_email'], $L['mstoremailorder_status_email_subject'], $status_email_body);
        mstoremailorder_log("Admin updated status for order_id={$order_id} to {$new_status}", $pluginDir);
        cot_message($L['mstoremailorder_status_updated']);
        cot_redirect(cot_url('admin', 'm=other&p=mstoremailorder', '', true));
    } else {
        mstoremailorder_log("Admin update failed: Order not found for order_id={$order_id}", $pluginDir);
        cot_message("Order not found.", 'warning');
    }
}

$t = new XTemplate(cot_tplfile('mstoremailorder.admin', 'plug'));

// Build WHERE
$where = [];
$params = [];

if (!empty($order_id)) {
    $where[] = "o.order_id = ?";
    $params[] = $order_id;
} else {
    if ($filter_status !== '' && $filter_status !== null) {
        $where[] = "o.order_status = ?";
        $params[] = $filter_status;
    }
    if ($search) {
        $where[] = "(o.order_email LIKE ? OR m.msitem_title LIKE ? OR bu.user_name LIKE ? OR su.user_name LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
}
$where_sql = $where ? "WHERE " . implode(' AND ', $where) : '';

// Count total
$totalRows = (int)Cot::$db->query("
    SELECT COUNT(*) 
    FROM $db_mstoremailorders AS o
    LEFT JOIN $db_mstore AS m ON m.msitem_id = o.order_item_id
    LEFT JOIN $db_users AS bu ON bu.user_id = o.order_user_id
    LEFT JOIN $db_users AS su ON su.user_id = o.order_seller_id
    $where_sql
", $params)->fetchColumn();

// Pagination
$pagenav = cot_pagenav(
    'admin',
    "m=other&p=mstoremailorder"
        . ($filter_status !== '' ? "&filter_status={$filter_status}" : '')
        . ($search ? "&search=" . rawurlencode($search) : '')
        . ($order_id ? "&order_id={$order_id}" : ''),
    $offset,
    $totalRows,
    $maxrowsperpage,
    'd',
    '',
    $cfg['jquery'] && $cfg['turnajax']
);

// Query
$sql = "
    SELECT o.*, m.*, bu.user_name AS buyer_name, su.user_name AS seller_name
    FROM $db_mstoremailorders AS o
    LEFT JOIN $db_mstore AS m ON m.msitem_id = o.order_item_id
    LEFT JOIN $db_users AS bu ON bu.user_id = o.order_user_id
    LEFT JOIN $db_users AS su ON su.user_id = o.order_seller_id
    $where_sql
    ORDER BY o.order_date DESC
    LIMIT ?, ?
";
$orders = Cot::$db->query($sql, array_merge($params, [$offset, $maxrowsperpage]))->fetchAll();

// Render
$i = $offset;
foreach ($orders as $order) {
    $i++;
    $status_text = $L['mstoremailorder_status_new'] ?? 'New';
    if ($order['order_status'] == 1) $status_text = $L['mstoremailorder_status_processing'] ?? 'Processing';
    elseif ($order['order_status'] == 2) $status_text = $L['mstoremailorder_status_completed'] ?? 'Completed';
    elseif ($order['order_status'] == 3) $status_text = $L['mstoremailorder_status_canceled'] ?? 'Canceled';
    elseif ($order['order_status'] == 4) $status_text = $L['mstoremailorder_status_rejected'] ?? 'Rejected';

    // history
    $history = Cot::$db->query("SELECT * FROM $db_mstoremailorder_history WHERE order_id = ? ORDER BY change_date DESC", [$order['order_id']])->fetchAll();
    foreach ($history as $hist) {
        $hist_status_text = $L['mstoremailorder_status_new'] ?? 'New';
        if ($hist['status'] == 1) $hist_status_text = $L['mstoremailorder_status_processing'] ?? 'Processing';
        elseif ($hist['status'] == 2) $hist_status_text = $L['mstoremailorder_status_completed'] ?? 'Completed';
        elseif ($hist['status'] == 3) $hist_status_text = $L['mstoremailorder_status_canceled'] ?? 'Canceled';
        elseif ($hist['status'] == 4) $hist_status_text = $L['mstoremailorder_status_rejected'] ?? 'Rejected';
        $t->assign([
            'HISTORY_STATUS_TEXT' => htmlspecialchars($hist_status_text, ENT_QUOTES, 'UTF-8'),
            'HISTORY_DATE'        => cot_date('datetime_full', $hist['change_date'] ?? $sys['now']),
        ]);
        $t->parse('MAIN.ORDERS.HISTORY');
    }

    // tags
    $t->assign(cot_generate_mstoretags($order['order_item_id'], 'ORDER_ITEM_', $cfg['mstore']['shorttextlen'] ?? 255, true, $cfg['homebreadcrumb']));
    $t->assign(cot_generate_usertags($order['order_seller_id'], 'ORDER_SELLER_'));
    if ($order['order_user_id'] > 0) {
        $t->assign(cot_generate_usertags($order['order_user_id'], 'ORDER_BUYER_'));
    } else {
        $t->assign([
            'ORDER_BUYER_NAME'  => htmlspecialchars($order['order_buyer_nickname'] ?? $order['order_email'], ENT_QUOTES, 'UTF-8'),
            'ORDER_BUYER_EMAIL' => htmlspecialchars($order['order_email'], ENT_QUOTES, 'UTF-8'),
        ]);
    }

    $t->assign([
        'ORDER_ID'             => $order['order_id'],
        'ORDER_QUANTITY'       => $order['order_quantity'],
        'ORDER_EMAIL'          => htmlspecialchars($order['order_email'], ENT_QUOTES, 'UTF-8'),
        'ORDER_PHONE'          => htmlspecialchars($order['order_phone'], ENT_QUOTES, 'UTF-8'),
        'ORDER_COMMENT'        => htmlspecialchars($order['order_comment'], ENT_QUOTES, 'UTF-8'),
        'ORDER_DATE'           => cot_date('datetime_full', $order['order_date']),
        'ORDER_STATUS'         => $order['order_status'],
        'ORDER_STATUS_TEXT'    => htmlspecialchars($status_text, ENT_QUOTES, 'UTF-8'),
        'ORDER_UPDATE_URL'     => cot_url('admin', 'm=other&p=mstoremailorder&a=update&order_id=' . $order['order_id']),
        'ORDER_DETAILS_URL'    => cot_url('plug', "e=mstoremailorder&m=details&id={$order['order_id']}", '', true),
        'ORDER_COMPLAINT_URL'  => cot_url('admin', "m=other&p=mstoremailorder&sp=complaint&order_id={$order['order_id']}", '', true),
        'ORDER_COST'           => number_format($order['order_costdflt'], 2, '.', ''),
        'ORDER_ODDEVEN'        => cot_build_oddeven($i),
        'ORDER_I'              => $i,
    ]);

    foreach (range(0, 4) as $status) {
        $t->assign([
            "ORDER_STATUS_{$status}_SELECTED" => ($order['order_status'] == $status) ? 'selected' : ''
        ]);
    }

    $t->parse('MAIN.ORDERS');
}

// Pagination tags
$t->assign(cot_generatePaginationTags($pagenav));

// Assign filters
$t->assign([
    'FILTER_STATUS_ALL_SELECTED' => ($filter_status === '' || $filter_status === null) ? 'selected' : '',
    'FILTER_STATUS_0_SELECTED'   => ($filter_status === '0') ? 'selected' : '',
    'FILTER_STATUS_1_SELECTED'   => ($filter_status === '1') ? 'selected' : '',
    'FILTER_STATUS_2_SELECTED'   => ($filter_status === '2') ? 'selected' : '',
    'FILTER_STATUS_3_SELECTED'   => ($filter_status === '3') ? 'selected' : '',
    'FILTER_STATUS_4_SELECTED'   => ($filter_status === '4') ? 'selected' : '',
    'SEARCH'                     => htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8'),
    'FILTER_ORDER_ID'            => $order_id ? (int)$order_id : '',
	'FORM_URL' => cot_url(
		'admin',
		'm=other&p=mstoremailorder'
			. ($filter_status !== '' && $filter_status !== null ? "&filter_status=" . rawurlencode((string)$filter_status) : '')
			. ($search !== '' && $search !== null ? "&search=" . rawurlencode((string)$search) : '')
			. ($order_id !== '' && $order_id !== null ? "&order_id=" . rawurlencode((string)$order_id) : '')
	),
    'ADMIN_COMPLAINTS_URL' => $complaints_url,
]);


cot_display_messages($t);
$t->parse();
$pluginBody = $t->text('MAIN');
