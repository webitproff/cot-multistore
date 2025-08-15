<?php
defined('COT_CODE') or die('Wrong URL');

/**
 * Інформація про плагін
 */

$L['info_name'] = 'Mstore Товари користувачів';
$L['info_desc'] = 'Плагін відображає список користувачів із кількістю їхніх товарів та детальний список товарів для кожного користувача';
$L['info_notes'] = 'Тестувалося на Cotonti 0.9.26 під PHP 8.2';

/**
 * Заголовок і підзаголовок плагіна
 */
/**
$L['plu_title'] = 'Товари користувачів'; // див. class ExtensionsHelper
$L['mstoreuserproducts_title'] = 'Товари користувачів'; // див. class ExtensionsHelper
 */
$L['cfg_mstore_max_rows_per_pages'] = 'Максимальна кількість товарів на сторінку (у шаблоні mstoreuserproducts.details.tpl)';
$L['cfg_mstore_max_rows_per_users'] = 'Максимальна кількість користувачів на сторінку (у шаблоні mstoreuserproducts.tpl)';

$L['mstoreuserproducts_title'] = 'Товари користувачів';

$L['mstoreussrproducts_list_title'] = 'Список користувачів та їхніх товарів';
$L['mstoreuserproducts_details_title'] = 'Товари користувача';
$L['mstoreuserproducts_user_not_found'] = 'Користувача не знайдено';
$L['mstoreuserproducts_no_users'] = 'Користувачів не знайдено';
$L['mstoreuserproducts_no_articles'] = 'У цього користувача немає опублікованих товарів';
$L['mstoreuserproducts_username'] = 'Ім\'я користувача';
$L['mstoreuserproducts_article_count'] = 'Кількість товарів';
$L['mstoreuserproducts_category'] = 'Категорія';
$L['mstoreuserproducts_title_page'] = 'Заголовок';
$L['mstoreuserproducts_date'] = 'Дата публікації';
$L['mstoreuserproducts_updated'] = 'Дата оновлення';
$L['mstoreuserproducts_views'] = 'Перегляди';

// Нові рядки для виведення кількості
$L['mstoreuserproducts_total_users'] = 'Користувачів усього';
$L['mstoreuserproducts_users_on_page'] = 'Користувачів на цій сторінці';
$L['mstoreuserproducts_total_articles'] = 'Товарів цього користувача усього';
$L['mstoreuserproducts_articles_on_page'] = 'Товарів на цій сторінці';

$L['mstoreuserproducts_search_label'] = 'Пошук за ім\'ям користувача';
$L['mstoreuserproducts_search_placeholder'] = 'Введіть ім\'я користувача';
$L['mstoreuserproducts_search_button'] = 'Знайти';

$L['mstoreuserproducts_all_categories'] = 'Усі категорії';
$L['mstoreuserproducts_category_filter_label'] = 'Фільтр за категорією';

$L['mstoreuserproducts_posted_on_website'] = 'опублікував на сайті спільноти';