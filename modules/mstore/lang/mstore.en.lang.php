<?php
/**
 * English Language File for the Mstore Module (mstore.en.lang.php)
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

$L['cfg_mstoremarkup'] = 'Markup in product description';
$L['cfg_mstoremarkup_hint'] = 'Enable the use of HTML or BBCode in product descriptions';

$L['cfg_mstoreparser'] = 'Description parser';
$L['cfg_mstoreparser_hint'] = 'Select the parser for processing product descriptions (e.g., BBCode, HTML, etc.)';

$L['cfg_mstorecount_admin'] = 'Count admin visits';
$L['cfg_mstorecount_admin_hint'] = 'Include admin visits in site traffic statistics';

$L['cfg_mstoreautovalidate'] = 'Automatic product approval';
$L['cfg_mstoreautovalidate_hint'] = 'Automatically approve products created by users with section administration rights';

$L['cfg_mstoremaxlistsperpage'] = 'Max number of categories per page';

$L['cfg_mstoretitle_page'] = 'Product title format';
$L['cfg_mstoretitle_page_hint'] = 'Options: {TITLE}, {CATEGORY}';

$L['cfg_mstoreblacktreecatspage'] = 'Category blacklist';
$L['cfg_mstoreblacktreecatspage_hint'] = 'Category codes excluded from the category tree on pages (e.g., system, unvalidated)';
$L['cfg_mstore_currency'] = 'Default currency';
$L['cfg_mstore_currency_hint'] = 'For informational purposes only, does not affect functionality';

// === STRUCTURE ===
$L['cfg_mstoreorder'] = 'Sort field';
$L['cfg_mstoreorder_params'] = [];

$L['cfg_mstoreway'] = 'Sort direction';
$L['cfg_mstoreway_params'] = [$L['Ascending'], $L['Descending']];

$L['cfg_maxrowsperpage'] = 'Max items per list page';

$L['cfg_mstoretruncatetext'] = 'Limit text size in product lists';
$L['cfg_mstoretruncatetext_hint'] = '0 to disable';

$L['cfg_mstoreallowemptytext'] = 'Allow empty product description';

$L['cfg_mstorekeywords'] = 'Keywords';
$L['cfg_mstoremetatitle'] = 'Meta title';
$L['cfg_mstoremetadesc'] = 'Meta description';

$L['cfg_mstoremaxlistsperpage'] = 'Max number of categories per page'; // duplicated. Necessary for categories

$L['info_desc'] = 'Content management: products and product categories';

$L['mstore_Mstore'] = 'MultiStore';
$L['maintitle_in_list_c_empty'] = 'Products and Suppliers';

/**
 * Override configuration setup for the module in the admin panel
 * Site Management / Configuration /
 */

$useCfgMstoreFromLang = true; // Use configuration values from the localization file

if ($useCfgMstoreFromLang === true) {
    $cfg['mstore']['mstorelist_default_title'] = 'MultiStore Showcase';
	$cfg['mstore']['mstorelist_default_desc'] = '<span class="badge text-bg-primary">CMS</span>, <span class="badge text-bg-success">Script</span> and <span class="badge text-bg-info">Engine</span> - a website for a wholesale online store for dropshipping and retail. Different prices in different currencies for products, create a product showcase for wholesale suppliers';

}

$L['adm_valqueue'] = 'Pending approval';
$L['adm_validated'] = 'Approved';
$L['adm_expired'] = 'Expired';
$L['adm_structure'] = 'Product structure (categories)';
$L['adm_sort'] = 'Sort';
$L['adm_sortingorder'] = 'Default sorting order in category';
$L['adm_showall'] = 'Show all';
$L['adm_help_mstore'] = 'Products in the "system" category are not displayed in lists and are standalone entries';
$L['adm_fileyesno'] = 'File (yes/no)';
$L['adm_fileurl'] = 'File URL';
$L['adm_filecount'] = 'Download count';
$L['adm_filesize'] = 'File size';

$L['mstore_addtitle'] = 'Add product';
$L['mstore_addsubtitle'] = 'Fill in the required fields and submit the form to continue';
$L['mstore_edittitle'] = 'Product properties';
$L['mstore_editsubtitle'] = 'Edit the necessary fields and click "Submit" to continue';
$L['mstore_addedit_seo'] = 'SEO optimization. Optional';
$L['mstore_addedit_desc'] = 'Short description for display in product lists. Optional';
$L['mstore_addedit_text'] = 'Product description';
$L['mstore_addedit_text_hint'] = 'No links, spam, or irrelevant content';
$L['mstore_all_items'] = 'All products';
$L['mstore_all_items_desc'] = 'All available products in the store';

$L['mstore_aliascharacters'] = 'Use of characters "+", "/", "?", "%", "#", "&" in aliases is not allowed';
$L['mstore_catmissing'] = 'Category code is missing';
$L['mstore_clone'] = 'Clone product';
$L['mstore_confirm_delete'] = 'Are you sure you want to delete this product?';
$L['mstore_confirm_validate'] = 'Do you want to approve this product?';
$L['mstore_confirm_unvalidate'] = 'Are you sure you want to send this product to the approval queue?';
$L['mstore_date_now'] = 'Update product date';
$L['mstore_deleted'] = 'Product deleted';
$L['mstore_deletedToTrash'] = 'Product moved to trash';
$L['mstore_drafts'] = 'Drafts';
$L['mstore_drafts_desc'] = 'Products saved as drafts';
$L['mstore_notavailable'] = 'Product will be published in';
$L['mstore_textmissing'] = 'Product description cannot be empty';
$L['mstore_titletooshort'] = 'Title is too short or missing';
$L['mstore_validation'] = 'Pending approval';
$L['mstore_validation_desc'] = 'Your products that are not yet approved by the administrator';

$L['mstore_file'] = 'Attach file';
$L['mstore_filehint'] = '(if file uploads are enabled, fill in the fields below)';
$L['mstore_urlhint'] = '(if a file is attached)';
$L['mstore_filesize'] = 'File size, KB';
$L['mstore_filesizehint'] = '(if a file is attached)';
$L['mstore_filehitcount'] = 'Downloads';
$L['mstore_filehitcounthint'] = '(if a file is attached)';
$L['mstore_metakeywords'] = 'Keywords';
$L['mstore_metatitle'] = 'Meta title';
$L['mstore_metadesc'] = 'Meta description';

$L['mstore_formhint'] = 'After filling out the form, the product will be placed in the approval queue and will remain hidden until approved by the administrator.';

$L['mstore_pageid'] = 'Product ID';
$L['mstore_deletepage'] = 'Delete product';

$L['mstore_savedasdraft'] = 'Product saved as draft';

$L['mstore_status_draft'] = 'Draft';
$L['mstore_status_pending'] = 'Pending';
$L['mstore_status_approved'] = 'Approved';
$L['mstore_status_published'] = 'Published';
$L['mstore_status_expired'] = 'Expired';
$L['mstore_linesperpage'] = 'Entries per page';
$L['mstore_linesinthissection'] = 'Entries in this section';

$Ls['pages'] = "product,products";
$Ls['unvalidated_mstore'] = "unapproved product,unapproved products";
$Ls['pages_in_drafts'] = "product in drafts,products in drafts";

// mstore.userdetails.php
$L['mstore_add_product'] = 'Add product';
$L['mstore_user_products'] = 'User products';
$L['mstore_no_products'] = 'No products';
$L['mstore_price'] = 'Default price';