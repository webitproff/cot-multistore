<?php
defined('COT_CODE') or die('Wrong URL');


/**
 * Plugin Info
 */

$L['info_name'] = 'Mstore товары пользователей';
$L['info_desc'] = 'Плагин отображает список пользователей с количеством их товаров и подробный список товаров для каждого продавца';
$L['info_notes'] = 'Тестировалось на Cotonti 0.9.26 под PHP 8.2';

/**
 * Plugin Title & Subtitle
 */
/**
$L['plu_title'] = 'товары пользователей'; смотреть class ExtensionsHelper
$L['mstoreuserproducts_title'] = 'товары пользователей';  смотреть class ExtensionsHelper
 */
$L['cfg_mstore_max_rows_per_pages'] = 'Максимальное число товаров на страницу (в шаблоне mstoreuserproducts.details.tpl)';
$L['cfg_mstore_max_rows_per_users'] = 'Максимальное число пользователей на страницу (в шаблоне mstoreuserproducts.tpl)';

$L['mstoreuserproducts_title'] = 'Tовары по продавцам';

$L['mstoreuserproducts_list_title'] = 'Список продавцов и их товаров';
$L['mstoreuserproducts_details_title'] = 'Товары продавца';
$L['mstoreuserproducts_user_not_found'] = 'Пользователь не найден';
$L['mstoreuserproducts_no_users'] = 'Пользователи не найдены';
$L['mstoreuserproducts_no_articles'] = 'У этого продавца нет опубликованных товаров';
$L['mstoreuserproducts_username'] = 'Имя продавца';
$L['mstoreuserproducts_article_count'] = 'Количество товаров';
$L['mstoreuserproducts_category'] = 'Категория';
$L['mstoreuserproducts_title_page'] = 'Заголовок';
$L['mstoreuserproducts_date'] = 'Дата публикации';
$L['mstoreuserproducts_updated'] = 'Дата обновления';
$L['mstoreuserproducts_views'] = 'Просмотры';

// Новые строки для вывода количества
$L['mstoreuserproducts_total_users'] = 'Пользователей всего';
$L['mstoreuserproducts_users_on_page'] = 'Пользователей на этой странице';
$L['mstoreuserproducts_total_articles'] = 'товаров данного продавца всего';
$L['mstoreuserproducts_articles_on_page'] = 'товаров на этой странице';

$L['mstoreuserproducts_search_label'] = 'Поиск по имени продавца';
$L['mstoreuserproducts_search_placeholder'] = 'Введите имя продавца';
$L['mstoreuserproducts_search_button'] = 'Найти';

$L['mstoreuserproducts_all_categories'] = 'Все категории';
$L['mstoreuserproducts_category_filter_label'] = 'Фильтр по категории';

$L['mstoreuserproducts_posted_on_website'] = 'опубликовал на сайте сообщества';