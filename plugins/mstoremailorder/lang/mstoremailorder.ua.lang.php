<?php
/**
 * MStore Email Order plugin: Ukrainian language
 * Filename: mstoremailorder.uk.lang.php
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

$L['cfg_email_from'] = 'Електронна пошта, від імені якої надсилаються сповіщення користувачам';
$L['cfg_use_function_log'] = 'Використовувати логування';
$L['cfg_use_function_log_hint'] = 'не рекомендується на робочому сайті (production project). використовується для налагодження';
$L['cfg_list_maxrowsperpage'] = 'Кількість елементів у списках';
$L['cfg_list_maxrowsperpage_hint'] = 'Кількість замовлень або скарг у списках, в адмінпанелі, вхідні та вихідні замовлення у користувачів';
$L['adminHelpInfo'] = '
	<p>Усі довідки, документація та допомога щодо цього плагіна доступні на <a href="https://abuyfile.com/uk/forums/mstore/mstoremailorder" target="_blank" rel="nofollow"><strong>форумі автора</strong></a></p>
	<p>Вихідний код та актуальні оновлення дивіться <a href="https://github.com/webitproff/cot-multistore/tree/main/plugins/mstoremailorder" target="_blank" rel="nofollow"><strong>у директорії на GitHub</strong></a></p>
	<p>Щоб запропонувати роботу, скористайтеся контактами <a href="https://github.com/webitproff" target="_blank" rel="nofollow"><strong>на сторінці розробок розробника</strong></a></p>
';

/**
 * Plugin Info
 */
$L['info_name'] = 'MStore Email Order';
$L['info_desc'] = 'Керування замовленнями товарів на сайті, розміщених у модулі MStore в режимі каталогу без онлайн-платежів';
$L['info_notes'] = 'Для сповіщень на пошту потрібен плагін "phpmailer". Тестувалося на CMF Cotonti Siena v.0.9.26 PHP 8.4.';
$L['mstoremailorder_set'] = 'Інформація та налаштування';
$L['mstoremailorder_order_id'] = 'Номер замовлення';

/* === Загальні === */
$L['mstoremailorder'] = 'Замовлення по email для MStore';
$L['mstoremailorder_order_button'] = 'Замовити';
$L['mstoremailorder_incoming_orders'] = 'Вхідні замовлення';
$L['mstoremailorder_outgoing_orders'] = 'Вихідні замовлення';
$L['mstoremailorder_form_title'] = 'Форма замовлення';
$L['mstoremailorder_email'] = 'Електронна пошта';
$L['mstoremailorder_phone'] = 'Телефон';
$L['mstoremailorder_quantity'] = 'Кількість';
$L['mstoremailorder_comment'] = 'Коментар';
$L['mstoremailorder_submit'] = 'Оформити замовлення';
$L['mstoremailorder_success'] = 'Замовлення успішно оформлено!';
$L['mstoremailorder_error_email'] = 'Електронна пошта обов’язкова';
$L['mstoremailorder_error_phone'] = 'Телефон обов’язковий';
$L['mstoremailorder_error_phone_invalid'] = 'Невірний формат номера телефону';
$L['mstoremailorder_error_quantity'] = 'Кількість має бути більшою за 0';
$L['mstoremailorder_status_new'] = 'Нове';
$L['mstoremailorder_status_processing'] = 'В обробці';
$L['mstoremailorder_status_completed'] = 'Виконано';
$L['mstoremailorder_status_canceled'] = 'Скасовано';
$L['mstoremailorder_status_rejected'] = 'Відхилено';
$L['mstoremailorder_update_status'] = 'Оновити статус';
$L['mstoremailorder_status_updated'] = 'Статус успішно оновлено!';
$L['mstoremailorder_filter_status'] = 'Фільтр за статусом';
$L['mstoremailorder_filter_search'] = 'Пошук замовлень';
$L['mstoremailorder_item'] = 'Товар';
$L['mstoremailorder_buyer'] = 'Покупець';
$L['mstoremailorder_date'] = 'Дата';
$L['mstoremailorder_status'] = 'Статус';
$L['mstoremailorder_history'] = 'Історія';
$L['mstoremailorder_actions'] = 'Дії';
$L['mstoremailorder_no_outgoing_orders'] = 'Немає вихідних замовлень';
$L['mstoremailorder_no_incoming_orders'] = 'Немає вхідних замовлень';
$L['mstoremailorder_login_required'] = 'Потрібна авторизація';

/* === Скарги === */
$L['mstoremailorder_complaint'] = 'Скарга';
$L['mstoremailorder_complaints'] = 'Скарги';
$L['mstoremailorder_complaint_form_title'] = 'Подати скаргу на замовлення';
$L['mstoremailorder_complaint_title'] = 'Скарга на замовлення';
$L['mstoremailorder_complaint_text'] = 'Текст скарги';
$L['mstoremailorder_complaint_submit'] = 'Відправити скаргу';
$L['mstoremailorder_complaint_success'] = 'Ваша скарга успішно відправлена.';
$L['mstoremailorder_complaint_error_text'] = 'Будь ласка, вкажіть текст скарги.';
$L['mstoremailorder_error_order_not_found'] = 'Замовлення не знайдено або у вас немає прав для подання скарги.';
$L['mstoremailorder_complaint_status_new'] = 'Нова';
$L['mstoremailorder_complaint_status_processing'] = 'В обробці';
$L['mstoremailorder_complaint_status_resolved'] = 'Вирішена';
$L['mstoremailorder_complaint_from'] = 'Від користувача';

/* === Email скарги === */
$L['mstoremailorder_complaint_email_subject'] = 'Скарга на замовлення №{ORDER_ID}';
$L['mstoremailorder_complaint_email_body'] = 'Надійшла скарга на замовлення №{ORDER_ID} щодо товару "{ITEM_TITLE}".<br /><br />
<strong>Скарга від:</strong> {USER_LOGIN} ({USER_NAME})<br />
<strong>Текст скарги:</strong><br />{COMPLAINT_TEXT}<br /><br />
Переглянути замовлення: {ORDER_URL}';

/* === Email сповіщення про зміну статусу скарги === */
$L['mstoremailorder_complaint_status_changed'] = 'Статус скарги оновлено';
$L['mstoremailorder_complaint_status_updated'] = 'Статус вашої скарги оновлено';
$L['mstoremailorder_complaint_new'] = 'Нова скарга';
$L['mstoremailorder_order_id'] = 'Номер замовлення';
$L['mstoremailorder_item_title'] = 'Назва товару';
$L['mstoremailorder_details'] = 'Деталі замовлення';
$L['mstoremailorder_cost'] = 'Вартість';
$L['ID'] = 'ID';
$L['Item'] = 'Товар';
$L['Seller'] = 'Продавець';
$L['Date'] = 'Дата';
$L['Status'] = 'Статус';
$L['History'] = 'Історія';
$L['Actions'] = 'Дії';
$L['Filter'] = 'Фільтрувати';
$L['All'] = 'Усі';
$L['mstoremailorder_email_subject'] = 'Сповіщення про замовлення';
$L['mstoremailorder_status_email_subject'] = 'Оновлення статусу замовлення';

/**
 * Email templates for orders
 */
$L['mstoremailorder_buyer_email_body'] = <<<HTML
Ваше замовлення №{ORDER_ID} успішно оформлено!<br /><br />
<strong>Деталі замовлення:</strong> <a href="{ORDER_URL}">Переглянути замовлення</a><br />
<strong>Товар:</strong> <a href="{ITEM_URL}">{ITEM_TITLE}</a><br />
<strong>Кількість:</strong> {QUANTITY}<br />
<strong>Телефон:</strong> {PHONE}<br />
<strong>Дата:</strong> {DATE}<br />
<strong>Коментар:</strong> {COMMENT}<br />
<strong>Email продавця:</strong> {SELLER_EMAIL}<br /><br />
Продавець: <a href="{SELLER_PROFILE_URL}">{SELLER_NICKNAME}</a><br />
Ваш профіль Покупця: <a href="{BUYER_PROFILE_URL}">{BUYER_NICKNAME}</a>
HTML;

$L['mstoremailorder_seller_email_body'] = <<<HTML
Надійшло нове замовлення №{ORDER_ID}!<br /><br />
<strong>Деталі замовлення:</strong> <a href="{ORDER_URL}">Переглянути замовлення</a><br />
<strong>Товар:</strong> <a href="{ITEM_URL}">{ITEM_TITLE}</a><br />
<strong>Кількість:</strong> {QUANTITY}<br />
<strong>Телефон покупця:</strong> {PHONE}<br />
<strong>Дата:</strong> {DATE}<br />
<strong>Коментар:</strong> {COMMENT}<br />
<strong>Email покупця:</strong> {BUYER_EMAIL}<br /><br />
Покупець: <a href="{BUYER_PROFILE_URL}">{BUYER_NICKNAME}</a><br />
Ваш профіль Продавця: <a href="{SELLER_PROFILE_URL}">{SELLER_NICKNAME}</a>
HTML;

$L['mstoremailorder_admin_email_body'] = <<<HTML
Надійшло нове замовлення №{ORDER_ID}!<br /><br />
<strong>Деталі замовлення:</strong> <a href="{ORDER_URL}">Переглянути замовлення</a><br />
<strong>Товар:</strong> <a href="{ITEM_URL}">{ITEM_TITLE}</a><br />
<strong>Кількість:</strong> {QUANTITY}<br />
<strong>Телефон покупця:</strong> {PHONE}<br />
<strong>Дата:</strong> {DATE}<br />
<strong>Коментар:</strong> {COMMENT}<br />
<strong>Email покупця:</strong> {BUYER_EMAIL}<br />
<strong>Email продавця:</strong> {SELLER_EMAIL}<br /><br />
Покупець: <a href="{BUYER_PROFILE_URL}">{BUYER_NICKNAME}</a><br />
Продавець: <a href="{SELLER_PROFILE_URL}">{SELLER_NICKNAME}</a>
HTML;

$L['mstoremailorder_status_email_body'] = <<<HTML
Статус вашого замовлення змінено!<br /><br />
<strong>Товар:</strong> <a href="{ITEM_URL}">{ITEM_TITLE}</a><br />
<strong>Кількість:</strong> {QUANTITY}<br />
<strong>Телефон:</strong> {PHONE}<br />
<strong>Дата:</strong> {DATE}<br />
<strong>Коментар:</strong> {COMMENT}<br />
<strong>Статус:</strong> {STATUS}
HTML;

?>