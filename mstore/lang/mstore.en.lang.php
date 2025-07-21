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

$L['cfg_autovalidate'] = 'Automatic Item Approval';
$L['cfg_autovalidate_hint'] = 'Automatically approve item publications created by users with administrative rights for the section';
$L['cfg_count_admin'] = 'Count Admin Visits';
$L['cfg_count_admin_hint'] = 'Include admin visits in the site visit statistics';
$L['cfg_maxlistsperpage'] = 'Max Categories per Page';
$L['cfg_order'] = 'Sort Field';
$L['cfg_title_page'] = 'Item Title Format';
$L['cfg_title_page_hint'] = 'Options: {TITLE}, {CATEGORY}';
$L['cfg_way'] = 'Sort Direction';
$L['cfg_truncatetext'] = 'Limit Text Size in Item Lists';
$L['cfg_truncatetext_hint'] = '0 to disable';
$L['cfg_allowemptytext'] = 'Allow Empty Item Description';
$L['cfg_keywords'] = 'Keywords';

$L['info_desc'] = 'Content Management: Items and Item Categories';

$L['cfg_order_params'] = [];
$L['cfg_way_params'] = array($L['Ascending'], $L['Descending']);
$L['cfg_metatitle'] = 'Meta Title';
$L['cfg_metadesc'] = 'Meta Description';

$L['Mstore'] = 'Mstore';
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