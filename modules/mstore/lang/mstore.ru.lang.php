<?php
/**
 * Russian Language File for the Mstore Module (mstore.ru.lang.php)
 *
 * @package Mstore
 * @copyright (c) webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');
global $cfg;
if (!isset($L['PFS'])) {
    $mainLangFile = cot_langfile('main', 'core');
    if (file_exists($mainLangFile)) {
        include $mainLangFile;
    }
}

$L['cfg_mstoremarkup'] = 'Разметка в описании товара';
$L['cfg_mstoremarkup_hint'] = 'Включить использование HTML или BBCode в описании товара';

$L['cfg_mstoreparser'] = 'Парсер описания';
$L['cfg_mstoreparser_hint'] = 'Выберите парсер для обработки описания товара (например, BBCode, HTML и т.д.)';

$L['cfg_mstorecount_admin'] = 'Считать посещения администраторов';
$L['cfg_mstorecount_admin_hint'] = 'Включить посещения администраторов в статистику посещаемости сайта';

$L['cfg_mstoreautovalidate'] = 'Автоматическое утверждение товаров';
$L['cfg_mstoreautovalidate_hint'] = 'Автоматически утверждать публикацию товаров, созданных пользователем с правом администрирования раздела';

$L['cfg_mstoremaxlistsperpage'] = 'Макс. количество категорий на странице';

$L['cfg_mstoretitle_page'] = 'Формат заголовка товара';
$L['cfg_mstoretitle_page_hint'] = 'Опции: {TITLE}, {CATEGORY}';

$L['cfg_mstoreblacktreecatspage'] = 'Черный список категорий';
$L['cfg_mstoreblacktreecatspage_hint'] = 'Коды категорий, исключенные из дерева категорий на страницах (например: system, unvalidated)';
$L['cfg_mstore_currency'] = 'Валюта по умолчанию';
$L['cfg_mstore_currency_hint'] = 'Ни на что не влияет, чисто для информации';

// === STRUCTURE ===
$L['cfg_mstoreorder'] = 'Поле сортировки';
$L['cfg_mstoreorder_params'] = [];

$L['cfg_mstoreway'] = 'Направление сортировки';
$L['cfg_mstoreway_params'] = [$L['Ascending'], $L['Descending']];

$L['cfg_maxrowsperpage'] = 'Макс. элементов на странице списка';

$L['cfg_mstoretruncatetext'] = 'Ограничить размер текста в списках товаров';
$L['cfg_mstoretruncatetext_hint'] = '0 для отключения';

$L['cfg_mstoreallowemptytext'] = 'Разрешить пустое описание товара';

$L['cfg_mstorekeywords'] = 'Ключевые слова';
$L['cfg_mstoremetatitle'] = 'Meta-заголовок';
$L['cfg_mstoremetadesc'] = 'Meta-описание';

$L['cfg_mstoremaxlistsperpage'] = 'Макс. количество категорий на странице'; // duplicated. It necessary for categories

$L['info_desc'] = 'Управление контентом: товары и категории товаров';



$L['mstore_Mstore'] = 'MultiStore';
$L['maintitle_in_list_c_empty'] = 'Товары и поставщики';

/**
 * переопределяем сетап конфигурации того, что у нас в админке модуля
 * Управление сайтом / Конфигурация / 
*/

$useCfgMstoreFromLang = true; // использовать значения конфигурации из файла локализации // Use configuration values from the localization file

if ($useCfgMstoreFromLang === true) {
    $cfg['mstore']['mstorelist_default_title'] = 'Витрина MultiStore';
    $cfg['mstore']['mstorelist_default_desc'] = '<span class="badge text-bg-primary">CMS</span>, <span class="badge text-bg-success">Скрипт</span> и <span class="badge text-bg-info">Движок</span> - веб сайта оптового интернет магазина для дропшиппинга и розницы. Разные цены в разных валютах на товар, создать витрину товаров для оптовых поставщиков';
}


$L['adm_valqueue'] = 'В очереди на утверждение';
$L['adm_validated'] = 'Утвержденные';
$L['adm_expired'] = 'С истекшим сроком';
$L['adm_structure'] = 'Структура товаров (категории)';
$L['adm_sort'] = 'Сортировать';
$L['adm_sortingorder'] = 'Порядок сортировки по умолчанию в категории';
$L['adm_showall'] = 'Показать все';
$L['adm_help_mstore'] = 'Товары категории «system» не отображаются в списках и являются самостоятельными записями';
$L['adm_fileyesno'] = 'Файл (да/нет)';
$L['adm_fileurl'] = 'URL файла';
$L['adm_filecount'] = 'Количество загрузок';
$L['adm_filesize'] = 'Размер файла';

$L['mstore_addtitle'] = 'Добавить товар';
$L['mstore_addsubtitle'] = 'Заполните необходимые поля и отправьте форму для продолжения';
$L['mstore_edittitle'] = 'Свойства товара';
$L['mstore_editsubtitle'] = 'Измените необходимые поля и нажмите "Отправить" для продолжения';
$L['mstore_addedit_seo'] = 'SEO-оптимизация. Заполняется по желанию';
$L['mstore_addedit_desc'] = 'Краткое описание для просмотра в списках товаров. Заполняется по желанию';
$L['mstore_addedit_text'] = 'Описание товара';
$L['mstore_addedit_text_hint'] = 'Без ссылок, спама и муссора';
$L['mstore_all_items'] = 'Все товары';
$L['mstore_all_items_desc'] = 'Все доступные товары магазина';


$L['mstore_aliascharacters'] = 'Недопустимо использование символов "+", "/", "?", "%", "#", "&" в алиасах';
$L['mstore_catmissing'] = 'Код категории отсутствует';
$L['mstore_clone'] = 'Клонировать товар';
$L['mstore_confirm_delete'] = 'Вы действительно хотите удалить этот товар?';
$L['mstore_confirm_validate'] = 'Хотите утвердить этот товар?';
$L['mstore_confirm_unvalidate'] = 'Вы действительно хотите отправить этот товар в очередь на утверждение?';
$L['mstore_date_now'] = 'Актуализировать дату товара';
$L['mstore_deleted'] = 'Товар удален';
$L['mstore_deletedToTrash'] = 'Товар удален в корзину';
$L['mstore_drafts'] = 'Черновики';
$L['mstore_drafts_desc'] = 'Товары, сохраненные в черновиках';
$L['mstore_notavailable'] = 'Товар будет опубликован через';
$L['mstore_textmissing'] = 'Описание товара не должно быть пустым';
$L['mstore_titletooshort'] = 'Название слишком короткое либо отсутствует';
$L['mstore_validation'] = 'Ожидают утверждения';
$L['mstore_validation_desc'] = 'Ваши товары, которые еще не утверждены администратором';

$L['mstore_file'] = 'Прикрепить файл';
$L['mstore_filehint'] = '(при включении загрузок заполните поля ниже)';
$L['mstore_urlhint'] = '(если прикреплен файл)';
$L['mstore_filesize'] = 'Размер файла, Кб';
$L['mstore_filesizehint'] = '(если прикреплен файл)';
$L['mstore_filehitcount'] = 'Загрузок';
$L['mstore_filehitcounthint'] = '(если прикреплен файл)';
$L['mstore_metakeywords'] = 'Ключевые слова';
$L['mstore_metatitle'] = 'Meta-заголовок';
$L['mstore_metadesc'] = 'Meta-описание';

$L['mstore_formhint'] = 'После заполнения формы товар будет помещён в очередь на утверждение и будет скрыт до утверждения администратором.';

$L['mstore_pageid'] = 'ID товара';
$L['mstore_deletepage'] = 'Удалить товар';

$L['mstore_savedasdraft'] = 'Товар сохранён в черновиках';

$L['mstore_status_draft'] = 'Черновик';
$L['mstore_status_pending'] = 'На рассмотрении';
$L['mstore_status_approved'] = 'Утверждён';
$L['mstore_status_published'] = 'Опубликован';
$L['mstore_status_expired'] = 'Устарел';
$L['mstore_linesperpage'] = 'Записей на страницу';
$L['mstore_linesinthissection'] = 'Записей в разделе';

$Ls['pages'] = "товар,товара,товаров";
$Ls['unvalidated_mstore'] = "неутверждённый товар,неутверждённые товары,неутверждённых товаров";
$Ls['pages_in_drafts'] = "товар в черновиках,товары в черновиках,товаров в черновиках";

// mstore.userdetails.php
$L['mstore_add_product'] = 'Добавить товар';
$L['mstore_user_products'] = 'Товары пользователя';
$L['mstore_no_products'] = 'Нет товаров';
$L['mstore_price'] = 'Цена по умолчанию';

