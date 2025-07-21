<?php
/**
 * Russian Language File for the Mstore Module (mstore.ru.lang.php)
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

$L['cfg_autovalidate'] = 'Автоматическое утверждение товаров';
$L['cfg_autovalidate_hint'] = 'Автоматически утверждать публикацию товаров, созданных пользователем с правом администрирования раздела';
$L['cfg_count_admin'] = 'Считать посещения администраторов';
$L['cfg_count_admin_hint'] = 'Включить посещения администраторов в статистику посещаемости сайта';
$L['cfg_maxlistsperpage'] = 'Макс. количество категорий на странице';
$L['cfg_order'] = 'Поле сортировки';
$L['cfg_title_page'] = 'Формат заголовка товара';
$L['cfg_title_page_hint'] = 'Опции: {TITLE}, {CATEGORY}';
$L['cfg_way'] = 'Направление сортировки';
$L['cfg_truncatetext'] = 'Ограничить размер текста в списках товаров';
$L['cfg_truncatetext_hint'] = '0 для отключения';
$L['cfg_allowemptytext'] = 'Разрешить пустое описание товара';
$L['cfg_keywords'] = 'Ключевые слова';

$L['info_desc'] = 'Управление контентом: товары и категории товаров';

$L['cfg_order_params'] = [];
$L['cfg_way_params'] = array($L['Ascending'], $L['Descending']);
$L['cfg_metatitle'] = 'Meta-заголовок';
$L['cfg_metadesc'] = 'Meta-описание';


$L['Mstore'] = 'Mstore';
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

$L['mstore_all_items'] = 'All Items';
$L['mstore_all_items_desc'] = 'All available store items';

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
