<?php
/**
 * MStore Email Order plugin: functions
 * Filename: mstoremailorder.functions.php
 * @package MStoreEmailOrder for CMF Cotonti Siena v.0.9.26 on PHP 8.4
 * Version=2.0.1
 * Date=2025-09-05
 * @author webitproff
 * @copyright Copyright (c) 2025 webitproff | https://github.com/webitproff
 * @license BSD License
 */
defined('COT_CODE') or die('Wrong URL.');

/**
 * Логирование сообщений в файл
 */
function mstoremailorder_log($message, $pluginDir) {
    global $cfg;
    $logPath = $pluginDir . '/mstoremailorder.log';
    if (
        !empty($cfg['plugin']['mstoremailorder']['use_function_log']) &&
        $cfg['plugin']['mstoremailorder']['use_function_log'] === '1' &&
        (is_writable($logPath) || (!file_exists($logPath) && is_writable($pluginDir)))
    ) {
        file_put_contents($logPath, date('Y-m-d H:i:s') . ": $message\n", FILE_APPEND);
    } else {
        error_log("mstoremailorder_log: Cannot write to $logPath. Message: $message");
    }
}

// Функция проверки $item_id
function cot_mstoremailorder_block_id_empty()
{
    global $item_id, $sys;

    if (empty($item_id) || !is_numeric($item_id) || $item_id < 1) {
        cot_redirect(cot_url('message', 'msg=930&' . $sys['url_redirect'], '', true));
        return false;
    }
    return true;
}


function mstoremailorder_send_email($to, $subject, $body) {
    global $cfg;

    // Проверка валидности email
    if (!cot_check_email($to)) {
        mstoremailorder_log("Ошибка: Неверный email получателя: $to", __DIR__);
        return false;
    }

    $fromemail = !empty($cfg['plugin']['mstoremailorder']['email_from']) 
        ? $cfg['plugin']['mstoremailorder']['email_from'] 
        : ($cfg['adminemail'] ?? 'noreply@example.com');
    $fromname = !empty($cfg['plugin']['mstoremailorder']['email_from_name']) 
        ? $cfg['plugin']['mstoremailorder']['email_from_name'] 
        : ($cfg['maintitle'] ?? 'Cotonti');

    try {
        if (cot_plugin_active('phpmailer')) {
            $body = "<!DOCTYPE html>\n<html lang=\"ru\">\n<head>\n<meta charset=\"UTF-8\">\n<title>" . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') . "</title>\n</head>\n<body>\n" . $body . "\n</body>\n</html>";
            // Декодируем тему, если она уже закодирована
            if (preg_match('/=\?UTF-8\?[BQ]\?.+\?=/i', $subject)) {
                $subject = mb_decode_mimeheader($subject);
            }
            $result = cot_mail_custom(
                $to,
                $subject,
                $body,
                $fromemail,
                $fromname,
                true, // HTML
                'UTF-8'
            );
        } else {
            $body = "<!DOCTYPE html>\n<html lang=\"ru\">\n<head>\n<meta charset=\"UTF-8\">\n<title>" . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') . "</title>\n</head>\n<body>\n" . $body . "\n</body>\n</html>";
            $body = str_replace(["\r\n", "\r"], "\n", $body);
            $body = mb_convert_encoding($body, 'UTF-8', 'UTF-8');
            $body = wordwrap($body, 70, "\r\n", true);
            $body = str_replace("\n", "\r\n", $body);

            $headers = [
                'MIME-Version: 1.0',
                'Content-Type: text/html; charset=UTF-8',
                'Content-Transfer-Encoding: quoted-printable',
                'From: ' . mb_encode_mimeheader($fromname, 'UTF-8', 'Q', "\r\n") . ' <' . $fromemail . '>',
                'Reply-To: ' . $fromemail,
                'X-Mailer: Cotonti v.' . Cot::$cfg['version'],
            ];

            //$subjectEncoded = mb_encode_mimeheader($subject, 'UTF-8', 'Q', "\r\n");

            $result = cot_mail(
                $to,
                $subject,
                $body,
                $headers,
                true,
                '',
                true
            );
        }
        mstoremailorder_log($result ? "Письмо отправлено на $to" : "Ошибка: Не удалось отправить письмо на $to", __DIR__);
        return $result;
    } catch (Exception $e) {
        mstoremailorder_log("Ошибка отправки письма: " . $e->getMessage() . " | Email: $to", __DIR__);
        return false;
    }
}

/**
 * Generate order email body
 * @param array $order Order data array containing order details
 * @param array $item Item data array containing item details
 * @param string $type Email type ('buyer', 'seller', 'admin' or 'status')
 * @return string|bool Generated email body or false if template not found
 */
function mstoremailorder_generate_order_email($order, $item, $type = 'buyer') {
    global $L, $cfg, $structure, $db, $sys, $db_structure, $db_mstore, $db_users;
    // Определяем ключ шаблона в зависимости от типа
    $template_key = '';
    if ($type === 'seller' || $type === true) {
        $template_key = 'mstoremailorder_seller_email_body';
    } elseif ($type === 'admin') {
        $template_key = 'mstoremailorder_admin_email_body'; // Новый ключ для админа, если задан в $L
        if (!isset($L[$template_key])) {
            $template_key = 'mstoremailorder_seller_email_body'; // Fallback на seller
        }
    } elseif ($type === 'status') {
        $template_key = 'mstoremailorder_status_email_body';
    } else {
        $template_key = 'mstoremailorder_buyer_email_body';
    }
    
    // Проверяем, существует ли шаблон
    if (!isset($L[$template_key])) {
        mstoremailorder_log("Ошибка: Шаблон для {$type} не найден в \$L", __DIR__);
        return false;
    }
    // Получаем категорию товара из базы данных
    $item_id = $order['order_item_id'];
    $item_data = $db->query("SELECT msitem_cat, msitem_alias FROM $db_mstore WHERE msitem_id = ?", [$item_id])->fetch();
    $item_cat = $item_data['msitem_cat'] ?? '';
    $item_alias = $item_data['msitem_alias'] ?? '';

    // Получаем email продавца из таблицы cot_users
    $seller_data = $db->query("SELECT user_email FROM $db_users WHERE user_id = ?", [$order['order_seller_id']])->fetch();
    $seller_email = $seller_data['user_email'] ?? '';

    // Формируем параметры URL для товара
    $url_params = !empty($item_alias) 
        ? ['c' => $item_cat, 'al' => $item_alias]
        : ['c' => $item_cat, 'id' => $item_id];

    // Генерируем URL-адреса
    $item_url = $cfg['mainurl'] . '/' . cot_url(
        'mstore',
        $url_params,
        '',
        true
    );
    $buyer_profile_url = ($order['order_user_id'] > 0)
        ? $cfg['mainurl'] . '/' . cot_url(
            'users',
            ['m' => 'details', 'id' => $order['order_user_id']],
            '',
            true
        )
        : '';
    $seller_profile_url = $cfg['mainurl'] . '/' . cot_url(
        'users',
        ['m' => 'details', 'id' => $order['order_seller_id']],
        '',
        true
    );
    $order_url = $cfg['mainurl'] . '/' . cot_url(
        'plug',
        ['e' => 'mstoremailorder', 'm' => 'details', 'id' => $order['order_id']],
        '',
        true
    );

    // Подготавливаем замены для шаблона
    $replacements = [
        '{ITEM_TITLE}' => htmlspecialchars($item['msitem_title'] ?: 'No title', ENT_QUOTES, 'UTF-8'),
        '{ITEM_URL}' => $item_url,
        '{QUANTITY}' => $order['order_quantity'],
        '{PHONE}' => htmlspecialchars($order['order_phone'], ENT_QUOTES, 'UTF-8'),
        '{DATE}' => cot_date('datetime_full', $order['order_date']),
        '{COMMENT}' => htmlspecialchars($order['order_comment'] ?: '', ENT_QUOTES, 'UTF-8'),
        '{BUYER_PROFILE_URL}' => $buyer_profile_url,
        '{SELLER_PROFILE_URL}' => $seller_profile_url,
        '{BUYER_NICKNAME}' => htmlspecialchars($order['order_buyer_nickname'] ?: 'Гость', ENT_QUOTES, 'UTF-8'),
        '{SELLER_NICKNAME}' => htmlspecialchars($order['order_seller_nickname'] ?: 'Неизвестен', ENT_QUOTES, 'UTF-8'),
        '{STATUS}' => htmlspecialchars($order['order_status'] ?: '', ENT_QUOTES, 'UTF-8'),
        '{ORDER_ID}' => $order['order_id'],
        '{ORDER_URL}' => $order_url,
        '{BUYER_EMAIL}' => htmlspecialchars($order['order_email'], ENT_QUOTES, 'UTF-8'),
        '{SELLER_EMAIL}' => htmlspecialchars($seller_email, ENT_QUOTES, 'UTF-8')
    ];

    // Возвращаем сформированное письмо
    return str_replace(array_keys($replacements), array_values($replacements), $L[$template_key]);
}
/**
 * Генерация тела письма для изменения статуса
 */
function mstoremailorder_generate_status_email($order, $item, $new_status) {
    global $L;

    $item_url = cot_url('mstore', "id={$order['order_item_id']}", '', true);
    $status_text = $L['mstoremailorder_status_new'] ?? 'Новый';
    if ($new_status == 1) $status_text = $L['mstoremailorder_status_processing'] ?? 'В обработке';
    elseif ($new_status == 2) $status_text = $L['mstoremailorder_status_completed'] ?? 'Выполнен';
    elseif ($new_status == 3) $status_text = $L['mstoremailorder_status_canceled'] ?? 'Отменен';
    elseif ($new_status == 4) $status_text = $L['mstoremailorder_status_rejected'] ?? 'Отказано';

    $body = $L['mstoremailorder_status_email_body'] ?? "Статус вашего заказа изменен!\nТовар: {ITEM_TITLE}\nСсылка: {ITEM_URL}\nКоличество: {QUANTITY}\nТелефон: {PHONE}\nДата: {DATE}\nКомментарий: {COMMENT}\nСтатус: {STATUS}";

    return str_replace(
        ['{ITEM_TITLE}', '{ITEM_URL}', '{QUANTITY}', '{PHONE}', '{DATE}', '{COMMENT}', '{STATUS}'],
        [
            htmlspecialchars($item['msitem_title'] ?: 'No title', ENT_QUOTES, 'UTF-8'),
            $item_url,
            $order['order_quantity'],
            htmlspecialchars($order['order_phone'], ENT_QUOTES, 'UTF-8'),
            cot_date('datetime_full', $order['order_date']),
            htmlspecialchars($order['order_comment'] ?: '', ENT_QUOTES, 'UTF-8'),
            $status_text
        ],
        $body
    );
}

/**
 * Валидация телефона
 */
function mstoremailorder_validate_phone($phone) {
    return preg_match('/^[\+]?[0-9\s\-\(\)]{7,20}$/', $phone);
}



/**
 * Генерация письма при создании жалобы
 */
function mstoremailorder_generate_complaint_email($order, $complaint_text, $complaint_user_name) {
    global $L, $cfg;

    $item_url = cot_url('mstore', "id={$order['order_item_id']}", '', true);
    $order_url = cot_url('plug', "e=mstoremailorder&m=details&id={$order['order_id']}", '', true);
    $body = $L['mstoremailorder_complaint_email_body'] ?? "Поступила жалоба на заказ №{ORDER_ID} по товару \"{ITEM_TITLE}\".\nЖалоба от: {USER_NAME}\nТекст жалобы: {COMPLAINT_TEXT}\nПосмотреть заказ: {ORDER_URL}";

    return str_replace(
        ['{ORDER_ID}', '{ITEM_TITLE}', '{ITEM_URL}', '{USER_NAME}', '{COMPLAINT_TEXT}', '{ORDER_URL}'],
        [
            htmlspecialchars($order['order_id'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($order['msitem_title'] ?: 'No title', ENT_QUOTES, 'UTF-8'),
            $item_url,
            htmlspecialchars($complaint_user_name, ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($complaint_text, ENT_QUOTES, 'UTF-8'),
            $order_url
        ],
        $body
    );
}

/**
 * Уведомление о статусе жалобы
 */
function mstoremailorder_notify_complaint_status($complaint) {
    global $L, $cfg, $db, $db_users, $db_x;

    $db_mstoremailorder = $db_x . 'mstoremailorders';
    $db_mstore = $db_x . 'mstore';
    $status_labels = [
        0 => $L['mstoremailorder_complaint_status_new'] ?? 'Новая',
        1 => $L['mstoremailorder_complaint_status_processing'] ?? 'В обработке',
        2 => $L['mstoremailorder_complaint_status_resolved'] ?? 'Решена',
    ];

    if (empty($complaint['order_id']) || !is_numeric($complaint['order_id'])) {
        mstoremailorder_log("Ошибка: Неверный order_id в жалобе: " . print_r($complaint, true), __DIR__);
        return;
    }
    if (empty($complaint['user_id']) || !is_numeric($complaint['user_id'])) {
        mstoremailorder_log("Ошибка: Неверный user_id в жалобе: " . print_r($complaint, true), __DIR__);
        return;
    }

    $user = $db->query("SELECT user_name, user_email FROM $db_users WHERE user_id = ?", [$complaint['user_id']])->fetch();
    $complainant_name = htmlspecialchars($user['user_name'] ?? 'Неизвестный пользователь', ENT_QUOTES, 'UTF-8');
    $buyer_email = $user['user_email'] ?? '';

    $order = $db->query("SELECT order_seller_id, order_item_id, order_buyer_nickname, order_id FROM $db_mstoremailorder WHERE order_id = ?", [$complaint['order_id']])->fetch();
    if (!$order) {
        mstoremailorder_log("Ошибка: Заказ не найден для order_id: {$complaint['order_id']}", __DIR__);
        return;
    }

    $seller_id = $order['order_seller_id'] ?? 0;
    $item_id = $order['order_item_id'] ?? 0;
    $complainant_login = htmlspecialchars($order['order_buyer_nickname'] ?? 'Не указан', ENT_QUOTES, 'UTF-8');

    if (empty($item_id) || !is_numeric($item_id)) {
        mstoremailorder_log("Ошибка: Неверный item_id: {$item_id}", __DIR__);
        $msitem_title = 'Не указан';
    } else {
        $item = $db->query("SELECT msitem_title FROM $db_mstore WHERE msitem_id = ?", [$item_id])->fetch();
        $msitem_title = htmlspecialchars($item['msitem_title'] ?? 'Не указан', ENT_QUOTES, 'UTF-8');
    }

    $seller = $db->query("SELECT user_email FROM $db_users WHERE user_id = ?", [$seller_id])->fetch();
    $seller_email = $seller['user_email'] ?? '';

    $order_url = $cfg['mainurl'] . '/' . cot_url('plug', "e=mstoremailorder&m=details&id={$complaint['order_id']}", '', true);
    $item_url = $cfg['mainurl'] . '/' . cot_url('mstore', "id={$item_id}", '', true);
    $complainant_url = $cfg['mainurl'] . '/' . cot_url('users', "m=details&id={$complaint['user_id']}", '', true);
    $status_text = $status_labels[$complaint['complaint_status']] ?? 'Неизвестный статус';

    // Покупатель
    if (!empty($buyer_email)) {
        $subject = $L['mstoremailorder_complaint_status_updated'] ?? 'Статус вашей жалобы обновлен';
        $body = sprintf(
            "<p><b>%s</b></p> <p>%s: <a href='%s'>%s</a></p> <p>%s: <a href='%s'>%s</a></p> <p>%s: <a href='%s'>%s (%s)</a></p> <p>%s: %s</p>",
            htmlspecialchars($L['mstoremailorder_complaint_status_updated'] ?? 'Статус вашей жалобы обновлен', ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($L['mstoremailorder_order_id'] ?? 'Номер заказа', ENT_QUOTES, 'UTF-8'),
            $order_url,
            htmlspecialchars($complaint['order_id'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($L['mstoremailorder_item'] ?? 'Товар', ENT_QUOTES, 'UTF-8'),
            $item_url,
            $msitem_title,
            htmlspecialchars($L['mstoremailorder_complaint_from'] ?? 'От пользователя', ENT_QUOTES, 'UTF-8'),
            $complainant_url,
            $complainant_name,
            $complainant_login,
            htmlspecialchars($L['mstoremailorder_complaint_status'] ?? 'Статус жалобы', ENT_QUOTES, 'UTF-8'),
            $status_text
        );
        mstoremailorder_send_email($buyer_email, $subject, $body);
    }

    // Продавец
    if (!empty($seller_email)) {
        $subject = $L['mstoremailorder_complaint_new'] ?? 'Новая жалоба';
        $body = sprintf(
            "<p><b>%s</b></p> <p>%s: <a href='%s'>%s</a></p> <p>%s: <a href='%s'>%s</a></p> <p>%s: <a href='%s'>%s (%s)</a></p> <p>%s: %s</p>",
            htmlspecialchars($L['mstoremailorder_complaint_new'] ?? 'Новая жалоба', ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($L['mstoremailorder_item'] ?? 'Товар', ENT_QUOTES, 'UTF-8'),
            $item_url,
            $msitem_title,
            htmlspecialchars($L['mstoremailorder_order_id'] ?? 'Номер заказа', ENT_QUOTES, 'UTF-8'),
            $order_url,
            htmlspecialchars($complaint['order_id'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($L['mstoremailorder_complaint_from'] ?? 'От пользователя', ENT_QUOTES, 'UTF-8'),
            $complainant_url,
            $complainant_name,
            $complainant_login,
            htmlspecialchars($L['mstoremailorder_complaint_status'] ?? 'Статус жалобы', ENT_QUOTES, 'UTF-8'),
            $status_text
        );
        mstoremailorder_send_email($seller_email, $subject, $body);
    }

    // Администратор
    if (!empty($cfg['adminemail'])) {
        $subject = $L['mstoremailorder_complaint_status_changed'] ?? 'Статус жалобы обновлен';
        $body = sprintf(
            "<p><b>%s</b></p> <p>%s: <a href='%s'>%s</a></p> <p>%s: <a href='%s'>%s</a></p> <p>%s: <a href='%s'>%s (%s)</a></p> <p>%s: %s</p>",
            htmlspecialchars($L['mstoremailorder_complaint_status_changed'] ?? 'Статус жалобы обновлен', ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($L['mstoremailorder_order_id'] ?? 'Номер заказа', ENT_QUOTES, 'UTF-8'),
            $order_url,
            htmlspecialchars($complaint['order_id'], ENT_QUOTES, 'UTF-8'),
            htmlspecialchars($L['mstoremailorder_item'] ?? 'Товар', ENT_QUOTES, 'UTF-8'),
            $item_url,
            $msitem_title,
            htmlspecialchars($L['mstoremailorder_complaint_from'] ?? 'От пользователя', ENT_QUOTES, 'UTF-8'),
            $complainant_url,
            $complainant_name,
            $complainant_login,
            htmlspecialchars($L['mstoremailorder_complaint_status'] ?? 'Статус жалобы', ENT_QUOTES, 'UTF-8'),
            $status_text
        );
        mstoremailorder_send_email($cfg['adminemail'], $subject, $body);
    }
}

?>
