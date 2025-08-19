<?php
/**
 * Пользовательский файл 2waydeal.uk.lang.php UA локализации скина
 *
 * @package Cotonti
 * @version 0.9.26
 * @author 
 * @copyright Copyright (c)
 * @license BSD
 */
/**
 * Данный файл предназначен для дополнительных пользовательских локализационных строк
 */
defined('COT_CODE') or die('Wrong URL.');

$L['2wd_darkMode'] = 'Нічний режим';
$L['2wd_lightMode'] = 'Світлий режим';

/**
 * Перевизначаємо налаштування конфігурації того, що в адмінці
 * Керування сайтом / Конфігурація / Заголовки та мета-теги 
 */
global $cfg;
$useCfgFromLang = true; // використовувати значення конфігурації з файлу локалізації

if ($useCfgFromLang === true) {
    // Заголовок сайту
    $cfg['maintitle'] = 'MultiStore CMS';
    // Опис сайту
    $cfg['subtitle'] = 'Платформа для оптового інтернет-магазину з каталогом товарів постачальників за моделлю дропшипінгу, завантажити безкоштовно скрипт';
}

$cot_groups['7']['name'] = 'Покупці';
$cot_groups['4']['name'] = 'Постачальники та продавці';

// Локалізація структури модуля market
if (isset($structure['market']['programming']) && is_array($structure['market']['programming'])) {
    $structure['market']['programming']['title'] = 'Програмні продукти';
    $structure['market']['programming']['tpath'] = 'Програми та скрипти (див. 2waydeal.uk.lang.php)';
    $structure['market']['programming']['desc'] = '<i class="fa-solid fa-circle-info fa-xl me-2"></i> продаж цифрових товарів і програмних продуктів за низькими цінами — це демпінг і нечесно! <span class="fw-bold text-danger">(Див. детальніше в 2waydeal.uk.lang.php)</span>';
}
if (isset($structure['market']['management']) && is_array($structure['market']['management'])) {
    $structure['market']['management']['title'] = 'Керування проєктами';
}
if (isset($structure['market']['marketing']) && is_array($structure['market']['marketing'])) {
    $structure['market']['marketing']['title'] = 'Реклама та маркетинг';
}

// Налаштування категорій market
if (isset($structure['market']['programming']) && is_array($structure['market']['programming'])) {
    $cfg['market']['cat_programming']['market_cat_metatitle'] = 'Мета-заголовок категорії програмування (див. 2waydeal.uk.lang.php)';
    $cfg['market']['cat_programming']['market_cat_metadesc'] = 'Мета-опис: Продаж цифрових товарів і програмних продуктів за низькими цінами — це демпінг (див. 2waydeal.uk.lang.php)';
}

// Локалізація структури usercategories
if (isset($structure['usercategories']['programming']) && is_array($structure['usercategories']['programming'])) {
    $structure['usercategories']['programming']['title'] = 'Кодування та програмування';
}
if (isset($structure['usercategories']['management']) && is_array($structure['usercategories']['management'])) {
    $structure['usercategories']['management']['title'] = 'Керівництво';
}
if (isset($structure['usercategories']['marketing']) && is_array($structure['usercategories']['marketing'])) {
    $structure['usercategories']['marketing']['title'] = 'Реклама та маркетинг';
}

$L['footer_engine'] = 'Движок сайту';
$L['footer_cotonti'] = 'CMF Cotonti 0.9.26';
$L['footer_cotonti_tooltip'] = 'Сайт працює на Cotonti Siena CMF — потужний каркас веб-розробки та інструмент керування контентом з відкритим кодом';

$L['2wd_menu_sections'] = 'Розділи сайту';
$L['2wd_info_and_support'] = 'Довідка та підтримка';
$L['2wd_info_sourceCode'] = 'Вихідний код та оновлення';
$L['2wd_info_forumSupport'] = 'Підтримка, допомога, запитання';
$L['2wd_Publications'] = 'Статті та блоги';
$L['2wd_cat_title_news'] = 'Новини';
$L['2wd_cat_title_events'] = 'Події';
$L['2wd_cat_title_articles'] = 'Статті';
$L['2wd_cat_title_usersblog'] = 'Блог користувачів сайту';
$L['2wd_cat_title_blogs'] = 'Блоги користувачів';
$L['header_notice'] = 'Є сповіщення';
$L['captcha_verification'] = 'Захист від ботів';
$L['2wd_WhosOnline'] = 'Хто зараз онлайн';
$L['2wd_Contact'] = 'Написати нам';
$L['2wd_recentitems_title'] = 'Останні матеріали';

/**
 * Модуль "Pages"
 */
$L['2wd_contentAuthor'] = 'Автор контенту';
$L['2wd_page_published'] = 'Сторінка опублікована:';
$L['2wd_page_latest_update'] = 'Останнє оновлення:';
$L['2wd_page_HasAttachment'] = 'Є файл для завантаження';
$L['2wd_page_DownloadFile'] = 'Завантажити файл зараз';
$L['2wd_page_LinkMainImage_hint'] = 'Через менеджер файлів завантажуємо головне зображення для цієї статті, а потім вставляємо посилання на зображення сюди';
$L['2wd_page_catEmpty'] = 'У розділі поки немає статей';

$L['2wd_Comments'] = 'Коментарів на сторінці';
$L['2wd_Comment_Edit'] = 'Редагування коментаря';

$L['Username'] = 'Нікнейм';
$L['2wd_Account'] = 'Акаунт';
$L['2wd_passCurrent'] = 'Поточний пароль';
$L['2wd_passNew'] = 'Новий пароль';
$L['2wd_passNewRepeat'] = 'Повторіть новий пароль';
$L['2wd_public_profile_page'] = 'Публічний профіль на сайті';
$L['2wd_public_profile_set_data'] = 'Налаштування профілю та дані';

// Повідомлення адміністратора про розташування шаблону
$L['mskin_admin'] = 'Адміністратор';
$L['mskin_attention'] = 'зверніть увагу!';
$L['mskin_template_file_location'] = 'Розташування файлу, що формує шаблон сторінки:';
$L['mskin_template_file_info'] = 'Ця інформація вам знадобиться, якщо ви вирішили змінити сторінку під свої потреби або знайшли помилку';

// Значення за замовчуванням для PFS
$L['pfs_pastefile'] = isset($L['pfs_pastefile']) ? $L['pfs_pastefile'] : 'Вставити як посилання на файл';
$L['pfs_pasteimage'] = isset($L['pfs_pasteimage']) ? $L['pfs_pasteimage'] : 'Вставити як зображення';
$L['pfs_pastethumb'] = isset($L['pfs_pastethumb']) ? $L['pfs_pastethumb'] : 'Вставити мініатюру';
$L['2wd_PFS_Attention'] = 'Увага! Для завантаження файлів потрібно створити папку!';
$L['2wd_PFS_Upl_Btn'] = 'Відкриється форма внизу сторінки!';
$L['2wd_not_installed_PFS'] = 'Не встановлено модуль <span class="fw-bold">"PFS"</span> для зберігання файлів — персональне (PFS) та загальне (SFS) сховище. Ви можете встановити його в панелі керування сайтом або переглянути <a href="https://www.cotonti.com/extensions/files-media/" class="alert-link" target="_blank">альтернативні розширення</a> на сайті спільноти';
$L['2wd_PFS'] = 'Модуль <span class="fw-bold">"PFS"</span> для зберігання файлів';
$L['2wd_PFS_myFiles_Title'] = 'Менеджер моїх файлів';

$L['pm_Selectall'] = 'Вибрати все';
$L['pm_Unselectall'] = 'Зняти вибір';

$L['plu_search_options'] = 'Фільтри';
$L['2wd_ReserFilter'] = 'Скинути фільтр';
$L['2wd_StartSearch'] = 'Почати пошук';

$L['2wd_region'] = 'Область';
$L['2wd_city'] = 'Місто';

$L['2wd_usrSeller'] = 'Продавець';
$L['2wd_usrCustomer'] = 'Покупець';
$L['2wd_usrFreelancer'] = 'Виконавець';
$L['2wd_usrEmployer'] = 'Замовник';
$L['2wd_publicCardAdmin'] = 'Керування публікацією';
$L['2wd_toolsAdmin'] = 'Інструменти адміна';

$L['2wd_prj_offers_posttext'] = 'Введіть текст вашого повідомлення ...';