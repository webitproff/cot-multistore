<?php
defined('COT_CODE') or die('Wrong URL');

/**
 * Plugin Info
 */

$L['info_name'] = 'Mstore User Products';
$L['info_desc'] = 'The plugin displays a list of users with the number of their products and a detailed list of products for each user';
$L['info_notes'] = 'Tested on Cotonti 0.9.26 under PHP 8.2';

/**
 * Plugin Title & Subtitle
 */
/**
$L['plu_title'] = 'User Products'; // see class ExtensionsHelper
$L['mstoreuserproducts_title'] = 'User Products'; // see class ExtensionsHelper
 */
$L['cfg_mstore_max_rows_per_pages'] = 'Maximum number of products per page (in the template mstoreuserproducts.details.tpl)';
$L['cfg_mstore_max_rows_per_users'] = 'Maximum number of users per page (in the template mstoreuserproducts.tpl)';

$L['mstoreuserproducts_title'] = 'User Products';

$L['mstoreuserproducts_list_title'] = 'List of Users and Their Products';
$L['mstoreuserproducts_details_title'] = 'User\'s Products';
$L['mstoreuserproducts_user_not_found'] = 'User not found';
$L['mstoreuserproducts_no_users'] = 'No users found';
$L['mstoreuserproducts_no_articles'] = 'This user has no published products';
$L['mstoreuserproducts_username'] = 'Username';
$L['mstoreuserproducts_article_count'] = 'Number of Products';
$L['mstoreuserproducts_category'] = 'Category';
$L['mstoreuserproducts_title_page'] = 'Title';
$L['mstoreuserproducts_date'] = 'Publication Date';
$L['mstoreuserproducts_updated'] = 'Update Date';
$L['mstoreuserproducts_views'] = 'Views';

// New strings for displaying counts
$L['mstoreuserproducts_total_users'] = 'Total Users';
$L['mstoreuserproducts_users_on_page'] = 'Users on This Page';
$L['mstoreuserproducts_total_articles'] = 'Total Products of This User';
$L['mstoreuserproducts_articles_on_page'] = 'Products on This Page';

$L['mstoreuserproducts_search_label'] = 'Search by Username';
$L['mstoreuserproducts_search_placeholder'] = 'Enter username';
$L['mstoreuserproducts_search_button'] = 'Search';

$L['mstoreuserproducts_all_categories'] = 'All Categories';
$L['mstoreuserproducts_category_filter_label'] = 'Category Filter';

$L['mstoreuserproducts_posted_on_website'] = 'posted on the community website';