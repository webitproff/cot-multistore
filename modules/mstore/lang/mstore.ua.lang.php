<?php
/**
 * Ukrainian Language File for the Mstore Module (mstore.ua.lang.php)
 *
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');

if (!isset($L['PFS'])) {
    $mainLangFile = cot_langfile('main', 'core');
    if (file_exists($mainLangFile)) {
        include $mainLangFile;
    }
}

$L['cfg_mstoremarkup'] = 'Розмітка в описі товару';
$L['cfg_mstoremarkup_hint'] = 'Увімкнути використання HTML або BBCode в описі товару';

$L['cfg_mstoreparser'] = 'Парсер опису';
$L['cfg_mstoreparser_hint'] = 'Оберіть парсер для обробки опису товару (наприклад, BBCode, HTML тощо)';

$L['cfg_mstorecount_admin'] = 'Рахувати відвідування адміністраторів';
$L['cfg_mstorecount_admin_hint'] = 'Включити відвідування адміністраторів у статистику';

$L['cfg_mstoreautovalidate'] = 'Автоматичне затвердження товарів';
$L['cfg_mstoreautovalidate_hint'] = 'Автоматично затверджувати товари, створені користувачами з правом модерації розділу';

$L['cfg_mstoremaxlistsperpage'] = 'Макс. кількість категорій на сторінці';

$L['cfg_mstoretitle_page'] = 'Формат заголовку товару';
$L['cfg_mstoretitle_page_hint'] = 'Опції: {TITLE}, {CATEGORY}';

$L['cfg_mstoreblacktreecatspage'] = 'Чорний список категорій';
$L['cfg_mstoreblacktreecatspage_hint'] = 'Коди категорій, виключених з дерева категорій на сторінках (наприклад: system, unvalidated)';
$L['cfg_mstore_currency'] = 'Валюта за замовчуванням';
$L['cfg_mstore_currency_hint'] = 'Ні на що не впливає, виключно для інформації';

// === STRUCTURE ===
$L['cfg_mstoreorder'] = 'Поле сортування';
$L['cfg_mstoreorder_params'] = [];

$L['cfg_mstoreway'] = 'Напрямок сортування';
$L['cfg_mstoreway_params'] = [$L['Ascending'], $L['Descending']];

$L['cfg_maxrowsperpage'] = 'Макс. елементів на сторінці списку';

$L['cfg_mstoretruncatetext'] = 'Обмежити довжину тексту в списках товарів';
$L['cfg_mstoretruncatetext_hint'] = '0 для вимкнення';

$L['cfg_mstoreallowemptytext'] = 'Дозволити порожній опис товару';

$L['cfg_mstorekeywords'] = 'Ключові слова';
$L['cfg_mstoremetatitle'] = 'Meta-заголовок';
$L['cfg_mstoremetadesc'] = 'Meta-опис';

$L['cfg_mstoremaxlistsperpage'] = 'Макс. кількість категорій на сторінці'; // дублюється. Потрібно для категорій

$L['info_desc'] = 'Управління контентом: товари та категорії товарів';

$L['mstore_Mstore'] = 'MultiStore';

/**
 * Перевизначаємо налаштування конфігурації того, що є в адмінці модуля
 * Керування сайтом / Конфігурація /
 */

$useCfgMstoreFromLang = true; // Використовувати значення конфігурації з файлу локалізації

if ($useCfgMstoreFromLang === true) {
    $cfg['mstore']['mstorelist_default_title'] = 'Вітрина MultiStore на FunSmart Club';
    $cfg['mstore']['mstorelist_default_desc'] = '<span class="badge text-bg-primary">Опт</span>, <span class="badge text-bg-success">дропшипінг</span> та <span class="badge text-bg-info">роздріб</span> - каталог електротранспорту від оптовиків для власників інтернет-магазинів та роздрібних продавців в Україні';
}

$L['adm_valqueue'] = 'У черзі на затвердження';
$L['adm_validated'] = 'Затверджені';
$L['adm_expired'] = 'З простроченим терміном';
$L['adm_structure'] = 'Структура товарів (категорії)';
$L['adm_sort'] = 'Сортувати';
$L['adm_sortingorder'] = 'Порядок сортування за замовчуванням в категорії';
$L['adm_showall'] = 'Показати все';
$L['adm_help_mstore'] = 'Товари з категорії «system» не відображаються у списках та є окремими записами';
$L['adm_fileyesno'] = 'Файл (так/ні)';
$L['adm_fileurl'] = 'URL файлу';
$L['adm_filecount'] = 'Кількість завантажень';
$L['adm_filesize'] = 'Розмір файлу';

$L['mstore_addtitle'] = 'Додати товар';
$L['mstore_addsubtitle'] = 'Заповніть необхідні поля та надішліть форму для продовження';
$L['mstore_edittitle'] = 'Властивості товару';
$L['mstore_editsubtitle'] = 'Змініть необхідні поля та натисніть "Надіслати" для продовження';

$L['mstore_all_items'] = 'Усі товари';
$L['mstore_all_items_desc'] = 'Усі доступні товари магазину';

$L['mstore_aliascharacters'] = 'Заборонено використовувати символи "+", "/", "?", "%", "#", "&" в псевдонімах';
$L['mstore_catmissing'] = 'Код категорії відсутній';
$L['mstore_clone'] = 'Клонувати товар';
$L['mstore_confirm_delete'] = 'Ви дійсно хочете видалити цей товар?';
$L['mstore_confirm_validate'] = 'Затвердити цей товар?';
$L['mstore_confirm_unvalidate'] = 'Ви дійсно хочете відправити товар у чергу на затвердження?';
$L['mstore_date_now'] = 'Оновити дату товару';
$L['mstore_deleted'] = 'Товар видалено';
$L['mstore_deletedToTrash'] = 'Товар переміщено до кошика';
$L['mstore_drafts'] = 'Чернетки';
$L['mstore_drafts_desc'] = 'Товари, збережені як чернетки';
$L['mstore_notavailable'] = 'Товар буде опубліковано через';
$L['mstore_textmissing'] = 'Опис товару не повинен бути порожнім';
$L['mstore_titletooshort'] = 'Назва занадто коротка або відсутня';
$L['mstore_validation'] = 'Очікують затвердження';
$L['mstore_validation_desc'] = 'Ваші товари, які ще не затверджені адміністратором';

$L['mstore_file'] = 'Прикріпити файл';
$L['mstore_filehint'] = '(при ввімкненні завантажень заповніть поля нижче)';
$L['mstore_urlhint'] = '(якщо прикріплено файл)';
$L['mstore_filesize'] = 'Розмір файлу, Кб';
$L['mstore_filesizehint'] = '(якщо прикріплено файл)';
$L['mstore_filehitcount'] = 'Завантажень';
$L['mstore_filehitcounthint'] = '(якщо прикріплено файл)';
$L['mstore_metakeywords'] = 'Ключові слова';
$L['mstore_metatitle'] = 'Meta-заголовок';
$L['mstore_metadesc'] = 'Meta-опис';

$L['mstore_formhint'] = 'Після заповнення форми товар буде поміщений у чергу на затвердження і буде прихований до затвердження адміністратором.';

$L['mstore_pageid'] = 'ID товару';
$L['mstore_deletepage'] = 'Видалити товар';

$L['mstore_savedasdraft'] = 'Товар збережено як чернетку';

$L['mstore_status_draft'] = 'Чернетка';
$L['mstore_status_pending'] = 'На розгляді';
$L['mstore_status_approved'] = 'Затверджено';
$L['mstore_status_published'] = 'Опубліковано';
$L['mstore_status_expired'] = 'Прострочено';
$L['mstore_linesperpage'] = 'Записів на сторінку';
$L['mstore_linesinthissection'] = 'Записів у розділі';

$Ls['pages'] = "товар,товари,товарів";
$Ls['unvalidated_mstore'] = "непідтверджений товар,непідтверджені товари,непідтверджених товарів";
$Ls['pages_in_drafts'] = "товар у чернетці,товари у чернетках,товарів у чернетках";

$L['mstore_add_product'] = 'Додати товар';
$L['mstore_user_products'] = 'Товари користувача';
$L['mstore_no_products'] = 'Немає товарів';
$L['mstore_price'] = 'Ціна за замовчуванням';
