<?php
/**
 * English Language File for the Mstore Module (mstore.en.lang.php)
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

$L['cfg_mstoremarkup'] = 'Product description markup';
$L['cfg_mstoremarkup_hint'] = 'Enable the use of HTML or BBCode in the product description';

$L['cfg_mstoreparser'] = 'Description parser';
$L['cfg_mstoreparser_hint'] = 'Choose a parser for processing product descriptions (e.g., BBCode, HTML, etc.)';

$L['cfg_mstorecount_admin'] = 'Count admin visits';
$L['cfg_mstorecount_admin_hint'] = 'Include admin visits in site traffic statistics';

$L['cfg_mstoreautovalidate'] = 'Auto-approve products';
$L['cfg_mstoreautovalidate_hint'] = 'Automatically approve products submitted by users with category admin rights';

$L['cfg_mstoremaxlistsperpage'] = 'Max. categories per page';
$L['cfg_mstoremaxlistsperpage_hint'] = 'to do';


$L['cfg_mstoretitle_page'] = 'Product title format';
$L['cfg_mstoretitle_page_hint'] = 'Options: {TITLE}, {CATEGORY}';

$L['cfg_mstoreblacktreecatspage'] = 'Category blacklist';
$L['cfg_mstoreblacktreecatspage_hint'] = 'Category codes excluded from the category tree on pages (e.g.: system, unvalidated)';

// === STRUCTURE ===
$L['cfg_mstoreorder'] = 'Sorting field';
$L['cfg_mstoreorder_params'] = [];

$L['cfg_mstoreway'] = 'Sorting direction';
$L['cfg_mstoreway_params'] = [$L['Ascending'], $L['Descending']];

$L['cfg_maxrowsperpage'] = 'Max. items per list page';

$L['cfg_mstoretruncatetext'] = 'Truncate text length in product lists';
$L['cfg_mstoretruncatetext_hint'] = '0 to disable truncation';

$L['cfg_mstoreallowemptytext'] = 'Allow empty product description';

$L['cfg_mstorekeywords'] = 'Meta keywords';
$L['cfg_mstoremetatitle'] = 'Meta title';
$L['cfg_mstoremetadesc'] = 'Meta description';

$L['cfg_mstoremaxlistsperpage'] = 'Max. categories per page'; // duplicated. It necessary for categories

$L['info_desc'] = 'Content management: products and product categories';


$L['mstore_Mstore'] = 'MultiStore';

$L['adm_valqueue'] = 'In Approval Queue';
$L['adm_validated'] = 'Approved';
$L['adm_expired'] = 'Expired';
$L['adm_structure'] = 'Item Structure (Categories)';
$L['adm_sort'] = 'Sort';
$L['adm_sortingorder'] = 'Default Sorting Order in Category';
$L['adm_showall'] = 'Show All';
$L['adm_help_mstore'] = 'Items in the "system" category are not displayed in lists and are standalone entries';
$L['adm_fileyesno'] = 'File (Yes/No)';
$L['adm_fileurl'] = 'File URL';
$L['adm_filecount'] = 'Download Count';
$L['adm_filesize'] = 'File Size';

$L['mstore_addtitle'] = 'Add Item';
$L['mstore_addsubtitle'] = 'Fill in the required fields and submit the form to continue';
$L['mstore_edittitle'] = 'Item Properties';
$L['mstore_editsubtitle'] = 'Modify the necessary fields and click "Submit" to continue';

$L['mstore_all_items'] = 'All Items';
$L['mstore_all_items_desc'] = 'All available store items';

$L['mstore_aliascharacters'] = 'The use of characters "+", "/", "?", "%", "#", "&" in aliases is not allowed';
$L['mstore_catmissing'] = 'Category code is missing';
$L['mstore_clone'] = 'Clone Item';
$L['mstore_confirm_delete'] = 'Do you really want to delete this item?';
$L['mstore_confirm_validate'] = 'Do you want to approve this item?';
$L['mstore_confirm_unvalidate'] = 'Do you really want to send this item to the approval queue?';
$L['mstore_date_now'] = 'Update Item Date';
$L['mstore_deleted'] = 'Item deleted';
$L['mstore_deletedToTrash'] = 'Item moved to trash';
$L['mstore_drafts'] = 'Drafts';
$L['mstore_drafts_desc'] = 'Items saved as drafts';
$L['mstore_notavailable'] = 'Item will be published in';
$L['mstore_textmissing'] = 'Item description must not be empty';
$L['mstore_titletooshort'] = 'Title is too short or missing';
$L['mstore_validation'] = 'Pending Approval';
$L['mstore_validation_desc'] = 'Your items that have not yet been approved by the administrator';

$L['mstore_file'] = 'Attach File';
$L['mstore_filehint'] = '(if downloads are enabled, fill in the fields below)';
$L['mstore_urlhint'] = '(if a file is attached)';
$L['mstore_filesize'] = 'File Size, KB';
$L['mstore_filesizehint'] = '(if a file is attached)';
$L['mstore_filehitcount'] = 'Downloads';
$L['mstore_filehitcounthint'] = '(if a file is attached)';
$L['mstore_metakeywords'] = 'Keywords';
$L['mstore_metatitle'] = 'Meta Title';
$L['mstore_metadesc'] = 'Meta Description';

$L['mstore_formhint'] = 'After filling out the form, the item will be placed in the approval queue and will remain hidden until approved by an administrator.';

$L['mstore_pageid'] = 'Item ID';
$L['mstore_deletepage'] = 'Delete Item';

$L['mstore_savedasdraft'] = 'Item saved as draft';

$L['mstore_status_draft'] = 'Draft';
$L['mstore_status_pending'] = 'Pending';
$L['mstore_status_approved'] = 'Approved';
$L['mstore_status_published'] = 'Published';
$L['mstore_status_expired'] = 'Expired';
$L['mstore_linesperpage'] = 'Items per Page';
$L['mstore_linesinthissection'] = 'Items in Section';

$Ls['pages'] = "item,items";
$Ls['unvalidated_mstore'] = "unapproved item,unapproved items";
$Ls['pages_in_drafts'] = "item in drafts,items in drafts";
?>