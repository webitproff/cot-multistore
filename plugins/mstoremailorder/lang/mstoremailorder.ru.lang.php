<?php
/**
 * MStore Email Order plugin: Russian language
 * Filename: mstoremailorder.ru.lang.php
 * @package MStoreEmailOrder for CMF Cotonti Siena v.0.9.26 on PHP 8.4
 * Version=2.0.1
 * Date=2025-09-05
 * @author webitproff
 * @copyright Copyright (c) 2025 webitproff | https://github.com/webitproff
 * @license BSD License
 */
defined('COT_CODE') or die('Wrong URL.');

/**
 * Plugin Config
 */

$L['cfg_email_from'] = 'Email, от имени которого отправляем уведомления пользователям';
$L['cfg_use_function_log'] = 'Использовать логировани';
$L['cfg_use_function_log_hint'] = 'не рекомендуется на рабочем сайте (production project). используется для отладки';
$L['cfg_list_maxrowsperpage'] = 'Количество элементов в списках';
$L['cfg_list_maxrowsperpage_hint'] = 'Число заказов или жалоб в списках, в админке, входящие и исходящие заказы у пользователей';
$L['adminHelpInfo'] = '
	<p>Всевозможные справки, документация и помощь по этому плагину находится на <a href="https://abuyfile.com/ru/forums/mstore/mstoremailorder" target="_blank" rel="nofollow"><strong>форуме автора</strong></a></p>
	<p>Исходный код и актуальные обновления смотрите <a href="https://github.com/webitproff/cot-multistore/tree/main/plugins/mstoremailorder" target="_blank" rel="nofollow"><strong>в директории на GitHub</strong></a></p>
	<p>Что бы предложить работу, - воспользуйтесь контактами <a href="https://github.com/webitproff" target="_blank" rel="nofollow"><strong>на странице разработок разработчика</strong></a></p>
';

/**
 * Plugin Info
 */
$L['info_name'] = 'MStore Email Order';
$L['info_desc'] = 'Управление заказами товаров на сайте, размещенными в модуле MStore в режиме каталога без онлайн платежей';
$L['info_notes'] = 'Для уведомлений на почту требует наличия плагина "phpmailer". Тестировалось на CMF Cotonti Siena v.0.9.26 PHP 8.4.';
$L['mstoremailorder_set'] = 'Инфо и установки';
$L['mstoremailorder_order_id'] = 'Номер заказа';

/* === Общие === */
$L['mstoremailorder'] = 'Заказы по email для MStore';
$L['mstoremailorder_order_button'] = 'Заказать';
$L['mstoremailorder_incoming_orders'] = 'Входящие заказы';
$L['mstoremailorder_outgoing_orders'] = 'Исходящие заказы';
$L['mstoremailorder_form_title'] = 'Форма заказа';
$L['mstoremailorder_email'] = 'Электронная почта';
$L['mstoremailorder_phone'] = 'Телефон';
$L['mstoremailorder_quantity'] = 'Количество';
$L['mstoremailorder_comment'] = 'Комментарий';
$L['mstoremailorder_submit'] = 'Оформить заказ';
$L['mstoremailorder_success'] = 'Заказ успешно оформлен!';
$L['mstoremailorder_error_email'] = 'Электронная почта обязательна';
$L['mstoremailorder_error_phone'] = 'Телефон обязателен';
$L['mstoremailorder_error_phone_invalid'] = 'Неверный формат номера телефона';
$L['mstoremailorder_error_quantity'] = 'Количество должно быть больше 0';
$L['mstoremailorder_status_new'] = 'Новый';
$L['mstoremailorder_status_processing'] = 'В обработке';
$L['mstoremailorder_status_completed'] = 'Выполнен';
$L['mstoremailorder_status_canceled'] = 'Отменен';
$L['mstoremailorder_status_rejected'] = 'Отказано';
$L['mstoremailorder_update_status'] = 'Обновить статус';
$L['mstoremailorder_status_updated'] = 'Статус успешно обновлен!';
$L['mstoremailorder_filter_status'] = 'Фильтр по статусу';
$L['mstoremailorder_filter_search'] = 'Поиск заказов';
$L['mstoremailorder_item'] = 'Товар';
$L['mstoremailorder_buyer'] = 'Покупатель';
$L['mstoremailorder_date'] = 'Дата';
$L['mstoremailorder_status'] = 'Статус';
$L['mstoremailorder_history'] = 'История';
$L['mstoremailorder_actions'] = 'Действия';
$L['mstoremailorder_no_outgoing_orders'] = 'Нет исходящих заказов';
$L['mstoremailorder_no_incoming_orders'] = 'Нет входящих заказов';
$L['mstoremailorder_login_required'] = 'Требуется авторизация';

/* === Жалобы === */
$L['mstoremailorder_complaint'] = 'Жалоба';
$L['mstoremailorder_complaints'] = 'Жалобы';
$L['mstoremailorder_complaint_form_title'] = 'Подать жалобу на заказ';
$L['mstoremailorder_complaint_title'] = 'Жалоба на заказ';
$L['mstoremailorder_complaint_text'] = 'Текст жалобы';
$L['mstoremailorder_complaint_submit'] = 'Отправить жалобу';
$L['mstoremailorder_complaint_success'] = 'Ваша жалоба успешно отправлена.';
$L['mstoremailorder_complaint_error_text'] = 'Пожалуйста, укажите текст жалобы.';
$L['mstoremailorder_error_order_not_found'] = 'Заказ не найден или у вас нет прав для подачи жалобы.';
$L['mstoremailorder_complaint_status_new'] = 'Новая';
$L['mstoremailorder_complaint_status_processing'] = 'В обработке';
$L['mstoremailorder_complaint_status_resolved'] = 'Решена';
$L['mstoremailorder_complaint_from'] = 'От пользователя';

/* === Email жалобы === */
$L['mstoremailorder_complaint_email_subject'] = 'Жалоба на заказ №{ORDER_ID}';
$L['mstoremailorder_complaint_email_body'] = 'Поступила жалоба на заказ №{ORDER_ID} по товару "{ITEM_TITLE}".<br /><br />
<strong>Жалоба от:</strong> {USER_LOGIN} ({USER_NAME})<br />
<strong>Текст жалобы:</strong><br />{COMPLAINT_TEXT}<br /><br />
Посмотреть заказ: {ORDER_URL}';

/* === Email уведомления о смене статуса жалобы === */
$L['mstoremailorder_complaint_status_changed'] = 'Статус жалобы обновлен';
$L['mstoremailorder_complaint_status_updated'] = 'Статус вашей жалобы обновлен';
$L['mstoremailorder_complaint_new'] = 'Новая жалоба';

$L['mstoremailorder_item_title'] = 'Название товара';
$L['mstoremailorder_details'] = 'Детали заказа';
$L['mstoremailorder_cost'] = 'Стоимость';
$L['ID'] = 'ID';
$L['Item'] = 'Товар';
$L['Seller'] = 'Продавец';
$L['Date'] = 'Дата';
$L['Status'] = 'Статус';
$L['History'] = 'История';
$L['Actions'] = 'Действия';
$L['Filter'] = 'Фильтровать';
$L['All'] = 'Все';
$L['mstoremailorder_email_subject'] = 'Уведомление о заказе';
$L['mstoremailorder_status_email_subject'] = 'Обноление статуса заказа';
/**
 * Email templates for orders
 */
$L['mstoremailorder_buyer_email_body'] = <<<HTML
Ваш заказ №{ORDER_ID} успешно оформлен!<br /><br />
<strong>Детали заказа:</strong> <a href="{ORDER_URL}">Перейти к заказу</a><br />
<strong>Товар:</strong> <a href="{ITEM_URL}">{ITEM_TITLE}</a><br />
<strong>Количество:</strong> {QUANTITY}<br />
<strong>Телефон:</strong> {PHONE}<br />
<strong>Дата:</strong> {DATE}<br />
<strong>Комментарий:</strong> {COMMENT}<br />
<strong>Email продавца:</strong> {SELLER_EMAIL}<br /><br />
Продавец: <a href="{SELLER_PROFILE_URL}">{SELLER_NICKNAME}</a><br />
Ваш профиль Покупатель: <a href="{BUYER_PROFILE_URL}">{BUYER_NICKNAME}</a>
HTML;

$L['mstoremailorder_seller_email_body'] = <<<HTML
Поступил новый заказ №{ORDER_ID}!<br /><br />
<strong>Детали заказа:</strong> <a href="{ORDER_URL}">Перейти к заказу</a><br />
<strong>Товар:</strong> <a href="{ITEM_URL}">{ITEM_TITLE}</a><br />
<strong>Количество:</strong> {QUANTITY}<br />
<strong>Телефон покупателя:</strong> {PHONE}<br />
<strong>Дата:</strong> {DATE}<br />
<strong>Комментарий:</strong> {COMMENT}<br />
<strong>Email покупателя:</strong> {BUYER_EMAIL}<br /><br />
Покупатель: <a href="{BUYER_PROFILE_URL}">{BUYER_NICKNAME}</a><br />
Ваш профиль Продавец: <a href="{SELLER_PROFILE_URL}">{SELLER_NICKNAME}</a>
HTML;


$L['mstoremailorder_admin_email_body'] = <<<HTML
Поступил новый заказ №{ORDER_ID}!<br /><br />
<strong>Детали заказа:</strong> <a href="{ORDER_URL}">Перейти к заказу</a><br />
<strong>Товар:</strong> <a href="{ITEM_URL}">{ITEM_TITLE}</a><br />
<strong>Количество:</strong> {QUANTITY}<br />
<strong>Телефон покупателя:</strong> {PHONE}<br />
<strong>Дата:</strong> {DATE}<br />
<strong>Комментарий:</strong> {COMMENT}<br />
<strong>Email покупателя:</strong> {BUYER_EMAIL}<br />
<strong>Email продавца:</strong> {SELLER_EMAIL}<br /><br />
Покупатель: <a href="{BUYER_PROFILE_URL}">{BUYER_NICKNAME}</a><br />
Продавец: <a href="{SELLER_PROFILE_URL}">{SELLER_NICKNAME}</a>
HTML;


$L['mstoremailorder_status_email_body'] = <<<HTML
Статус вашего заказа изменен!<br /><br />
<strong>Товар:</strong> <a href="{ITEM_URL}">{ITEM_TITLE}</a><br />
<strong>Количество:</strong> {QUANTITY}<br />
<strong>Телефон:</strong> {PHONE}<br />
<strong>Дата:</strong> {DATE}<br />
<strong>Комментарий:</strong> {COMMENT}<br />
<strong>Статус:</strong> {STATUS}
HTML;


?>
