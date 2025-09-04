<?php
/**
 * Плагин MStore Email Order: форма нового заказа
 * Файл: mstoremailorder.new.php
 * @package MStoreEmailOrder for CMF Cotonti Siena v.0.9.26 on PHP 8.4
 * Version=2.0.1
 * Date=2025-09-05
 * @author webitproff
 * @copyright Copyright (c) 2025 webitproff | https://github.com/webitproff
 * @license BSD License
 */
 
defined('COT_CODE') or die('Неверный URL.');
require_once cot_incfile('mstoremailorder', 'plug', 'functions');
global $db, $db_x, $L, $sys, $usr, $cfg;

// Инициализация переменных
$db_mstoremailorders = $db_x . 'mstoremailorders';
$db_mstoremailorder_history = $db_x . 'mstoremailorder_history';
$db_mstore = $db_x . 'mstore';
$db_users = $db_x . 'users';
$pluginDir = isset($cfg['plugins_dir']) ? $cfg['plugins_dir'] . '/mstoremailorder' : __DIR__;
$item_id = cot_import('item_id', 'G', 'INT') ?: cot_import('item_id', 'P', 'INT');
$submit = cot_import('submit', 'P', 'TXT');
$t = new XTemplate(cot_tplfile('mstoremailorder.new', 'plug'));

if ($submit && $item_id) {
    mstoremailorder_log("Обработка формы: item_id=$item_id, submit=$submit, user_id=" . Cot::$usr['id'], $pluginDir);
    $email = cot_import('email', 'P', 'TXT');
    $phone = cot_import('phone', 'P', 'TXT');
    $quantity = cot_import('quantity', 'P', 'INT');
    $comment = cot_import('comment', 'P', 'HTM');

    // Проверка капчи для гостей
    if (Cot::$usr['id'] == 0 && !empty($cot_captcha)) {
        $rverify = cot_import('rverify', 'P', 'TXT');
        if (!cot_captcha_validate($rverify)) {
            cot_error('Ошибка проверки капчи', 'rverify');
            mstoremailorder_log("Ошибка: Неверная капча", $pluginDir);
        }
    }

    // Валидация полей
    if (empty($email) || !cot_check_email($email)) {
        cot_error($L['mstoremailorder_error_email']);
        mstoremailorder_log("Ошибка: Неверный или пустой email: $email", $pluginDir);
    }
    if (empty($phone)) {
        cot_error($L['mstoremailorder_error_phone']);
        mstoremailorder_log("Ошибка: Пустой телефон", $pluginDir);
    }
    if (!mstoremailorder_validate_phone($phone)) {
        cot_error($L['mstoremailorder_error_phone_invalid']);
        mstoremailorder_log("Ошибка: Неверный формат телефона: $phone", $pluginDir);
    }
    if ($quantity <= 0) {
        cot_error($L['mstoremailorder_error_quantity']);
        mstoremailorder_log("Ошибка: Неверное количество: $quantity", $pluginDir);
    }

    // Проверка существования товара
    $item = Cot::$db->query("SELECT * FROM $db_mstore WHERE msitem_id = ?", [$item_id])->fetch();
    if (!$item) {
        cot_error('Товар не найден');
        mstoremailorder_log("Ошибка: Товар не найден для item_id=$item_id", $pluginDir);
    }

    // Проверка существования продавца
    if ($item) {
        $seller = Cot::$db->query("SELECT user_name, user_email FROM $db_users WHERE user_id = ?", [$item['msitem_ownerid']])->fetch();
        if (!$seller || empty($seller['user_email'])) {
            cot_error('Продавец не найден или отсутствует email');
            mstoremailorder_log("Ошибка: Продавец не найден или отсутствует email для msitem_ownerid={$item['msitem_ownerid']}", $pluginDir);
        }
    }

    // Обработка заказа, если нет ошибок
    if (!cot_error_found()) {
        $buyer_nickname = Cot::$usr['id'] ? ($usr['name'] ?: '') : 'Гость';
        $seller_nickname = $seller['user_name'] ?: '';
        $order = [
            'order_item_id' => $item_id,
            'order_user_id' => Cot::$usr['id'] > 0 ? Cot::$usr['id'] : null,
            'order_seller_id' => $item['msitem_ownerid'],
            'order_buyer_nickname' => $buyer_nickname,
            'order_seller_nickname' => $seller_nickname,
            'order_costdflt' => $item['msitem_costdflt'],
            'order_quantity' => $quantity,
            'order_phone' => $phone,
            'order_email' => $email,
            'order_comment' => $comment,
            'order_date' => $sys['now'],
            'order_ip' => $usr['ip'],
            'order_status' => 0,
        ];

        try {
            $inserted = Cot::$db->insert($db_mstoremailorders, $order);
            if ($inserted) {
                $order_id = Cot::$db->lastInsertId();
				$order['order_id'] = $order_id; // Добавляем order_id в массив $order
                Cot::$db->insert($db_mstoremailorder_history, [
                    'order_id' => $order_id,
                    'status' => 0,
                    'change_date' => $sys['now']
                ]);
                mstoremailorder_log("Заказ создан: order_id=$order_id, email=$email, seller_email={$seller['user_email']}", $pluginDir);

                // Генерация тел писем
                $buyer_email_body = mstoremailorder_generate_order_email($order, $item);
                $seller_email_body = mstoremailorder_generate_order_email($order, $item, true);
                $admin_email_body = mstoremailorder_generate_order_email($order, $item, 'admin'); // Новый тип для админа, fallback на seller если шаблон не задан

                // $subject = $cfg['plugin']['mstoremailorder']['email_subject'];
				$subject = $L['mstoremailorder_email_subject'];

                // Отправка покупателю (заказчику)
                if (!mstoremailorder_send_email($email, $subject, $buyer_email_body)) {
                    cot_error('Не удалось отправить письмо покупателю');
                    mstoremailorder_log("Ошибка: Не удалось отправить письмо покупателю: $email", $pluginDir);
                } else {
                    mstoremailorder_log("Письмо успешно отправлено покупателю: $email", $pluginDir);
                }

                // Отправка продавцу
                if (!mstoremailorder_send_email($seller['user_email'], $subject, $seller_email_body)) {
                    cot_error('Не удалось отправить письмо продавцу');
                    mstoremailorder_log("Ошибка: Не удалось отправить письмо продавцу: {$seller['user_email']}", $pluginDir);
                } else {
                    mstoremailorder_log("Письмо успешно отправлено продавцу: {$seller['user_email']}", $pluginDir);
                }

                // Отправка администратору
                $admin_email = Cot::$cfg['adminemail'];
                if (!empty($admin_email) && cot_check_email($admin_email)) {
                    if (!mstoremailorder_send_email($admin_email, $subject, $admin_email_body)) {
                        cot_error('Не удалось отправить письмо администратору');
                        mstoremailorder_log("Ошибка: Не удалось отправить письмо администратору: $admin_email", $pluginDir);
                    } else {
                        mstoremailorder_log("Письмо успешно отправлено администратору: $admin_email", $pluginDir);
                    }
                } else {
                    mstoremailorder_log("Предупреждение: Admin email не задан или неверный: $admin_email", $pluginDir);
                }

                // Успешное сообщение
                cot_message($L['mstoremailorder_success']);

                // Для гостей: остаёмся на странице, очищаем форму
                if (Cot::$usr['id'] == 0) {
                    $t->assign([
                        'EMAIL' => '',
                        'PHONE' => '',
                        'QUANTITY' => '',
                        'COMMENT' => '',
                    ]);
                    mstoremailorder_log("Гость оформил заказ, форма очищена", $pluginDir);
                } else {
                    // Для авторизованных: перенаправление на исходящие заказы
                    try {
                        cot_redirect(cot_url('plug', 'e=mstoremailorder&m=outgoing', '', true));
                    } catch (Exception $e) {
                        cot_error('Ошибка перенаправления: ' . $e->getMessage());
                        mstoremailorder_log("Ошибка перенаправления: " . $e->getMessage(), $pluginDir);
                        header('Location: ' . Cot::$cfg['mainurl'] . '/index.php?e=mstoremailorder&m=outgoing');
                        exit;
                    }
                }
            } else {
                cot_error('Не удалось сохранить заказ в базе');
                mstoremailorder_log("Ошибка: Не удалось сохранить заказ в базе", $pluginDir);
            }
        } catch (Exception $e) {
            cot_error('Ошибка базы данных: ' . $e->getMessage());
            mstoremailorder_log("Ошибка базы данных: " . $e->getMessage(), $pluginDir);
        }
    }
}

if ($item_id) {
    $item = Cot::$db->query("SELECT * FROM $db_mstore WHERE msitem_id = ?", [$item_id])->fetch();
    if ($item) {
        $t->assign([
            'ITEM_ID' => $item_id,
            'EMAIL' => Cot::$usr['id'] ? ($usr['profile']['user_email'] ?: '') : '',
            'PHONE' => '',
            'ITEM_TITLE' => htmlspecialchars($item['msitem_title'] ?: 'Без названия', ENT_QUOTES, 'UTF-8'),
            'ITEM_URL' => cot_url('mstore', "id={$item_id}", '', true),
        ]);
    } else {
        cot_error('Товар не найден');
        mstoremailorder_log("Ошибка: Товар не найден для item_id=$item_id", $pluginDir);
        $t->assign([
            'ITEM_ID' => $item_id,
            'EMAIL' => Cot::$usr['id'] ? ($usr['profile']['user_email'] ?: '') : '',
            'PHONE' => '',
            'ITEM_TITLE' => 'Товар не найден',
            'ITEM_URL' => '#',
        ]);
    }
}

// Капча для гостей
if (Cot::$usr['id'] == 0 && !empty($cot_captcha)) {
    $t->assign(cot_generateCaptchaTags(null, 'rverify', 'MSTOREEMAILORDER_FORM_'));
    $t->assign([
        'MSTOREEMAILORDER_FORM_VERIFY' => cot_inputbox('text', 'rverify', '', 'id="rverify" size="20"'),
    ]);
    $t->parse('MAIN.CAPTCHA');
}

cot_display_messages($t);