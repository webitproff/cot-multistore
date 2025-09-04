<?php
/**
 * MStore Email Order plugin: English language
 * Filename: mstoremailorder.en.lang.php
 * @package MStoreEmailOrder for CMF Cotonti Siena v.0.9.26 on PHP 8.4
 * Version=2.0.1
 * Date=2025-09-05
 * @author webitproff
 * @copyright Copyright (c) 2025 webitproff | https://github.com/webitproff
 * @license BSD License
 */
defined('COT_CODE') or die('Wrong URL.');

/**
 * Plugin Config
 */

$L['cfg_email_from'] = 'Email address used to send notifications to users';
$L['cfg_use_function_log'] = 'Enable logging';
$L['cfg_use_function_log_hint'] = 'Not recommended for production sites. Used for debugging';
$L['cfg_list_maxrowsperpage'] = 'Number of items in lists';
$L['cfg_list_maxrowsperpage_hint'] = 'Number of orders or complaints in lists, in admin panel, incoming and outgoing orders for users';
$L['adminHelpInfo'] = '
	<p>All help, documentation, and support for this plugin can be found on <a href="https://abuyfile.com/en/forums/mstore/mstoremailorder" target="_blank" rel="nofollow"><strong>the author\'s forum</strong></a></p>
	<p>Source code and latest updates are available <a href="https://github.com/webitproff/cot-multistore/tree/main/plugins/mstoremailorder" target="_blank" rel="nofollow"><strong>in the GitHub directory</strong></a></p>
	<p>To offer work, use the contact details <a href="https://github.com/webitproff" target="_blank" rel="nofollow"><strong>on the developer\'s projects page</strong></a></p>
';

/**
 * Plugin Info
 */
$L['info_name'] = 'MStore Email Order';
$L['info_desc'] = 'Management of product orders on the site, placed in the MStore module in catalog mode without online payments';
$L['info_notes'] = 'Requires the "phpmailer" plugin for email notifications. Tested on CMF Cotonti Siena v.0.9.26 PHP 8.4.';
$L['mstoremailorder_set'] = 'Info and settings';
$L['mstoremailorder_order_id'] = 'Order Number';

/* === General === */
$L['mstoremailorder'] = 'Email Orders for MStore';
$L['mstoremailorder_order_button'] = 'Order';
$L['mstoremailorder_incoming_orders'] = 'Incoming Orders';
$L['mstoremailorder_outgoing_orders'] = 'Outgoing Orders';
$L['mstoremailorder_form_title'] = 'Order Form';
$L['mstoremailorder_email'] = 'Email';
$L['mstoremailorder_phone'] = 'Phone';
$L['mstoremailorder_quantity'] = 'Quantity';
$L['mstoremailorder_comment'] = 'Comment';
$L['mstoremailorder_submit'] = 'Place Order';
$L['mstoremailorder_success'] = 'Order successfully placed!';
$L['mstoremailorder_error_email'] = 'Email is required';
$L['mstoremailorder_error_phone'] = 'Phone is required';
$L['mstoremailorder_error_phone_invalid'] = 'Invalid phone number format';
$L['mstoremailorder_error_quantity'] = 'Quantity must be greater than 0';
$L['mstoremailorder_status_new'] = 'New';
$L['mstoremailorder_status_processing'] = 'Processing';
$L['mstoremailorder_status_completed'] = 'Completed';
$L['mstoremailorder_status_canceled'] = 'Canceled';
$L['mstoremailorder_status_rejected'] = 'Rejected';
$L['mstoremailorder_update_status'] = 'Update Status';
$L['mstoremailorder_status_updated'] = 'Status successfully updated!';
$L['mstoremailorder_filter_status'] = 'Filter by Status';
$L['mstoremailorder_filter_search'] = 'Search Orders';
$L['mstoremailorder_item'] = 'Item';
$L['mstoremailorder_buyer'] = 'Buyer';
$L['mstoremailorder_date'] = 'Date';
$L['mstoremailorder_status'] = 'Status';
$L['mstoremailorder_history'] = 'History';
$L['mstoremailorder_actions'] = 'Actions';
$L['mstoremailorder_no_outgoing_orders'] = 'No outgoing orders';
$L['mstoremailorder_no_incoming_orders'] = 'No incoming orders';
$L['mstoremailorder_login_required'] = 'Login required';

/* === Complaints === */
$L['mstoremailorder_complaint'] = 'Complaint';
$L['mstoremailorder_complaints'] = 'Complaints';
$L['mstoremailorder_complaint_form_title'] = 'Submit a Complaint for an Order';
$L['mstoremailorder_complaint_title'] = 'Order Complaint';
$L['mstoremailorder_complaint_text'] = 'Complaint Text';
$L['mstoremailorder_complaint_submit'] = 'Submit Complaint';
$L['mstoremailorder_complaint_success'] = 'Your complaint has been successfully submitted.';
$L['mstoremailorder_complaint_error_text'] = 'Please provide the complaint text.';
$L['mstoremailorder_error_order_not_found'] = 'Order not found or you do not have permission to submit a complaint.';
$L['mstoremailorder_complaint_status_new'] = 'New';
$L['mstoremailorder_complaint_status_processing'] = 'Processing';
$L['mstoremailorder_complaint_status_resolved'] = 'Resolved';
$L['mstoremailorder_complaint_from'] = 'From User';

/* === Complaint Email === */
$L['mstoremailorder_complaint_email_subject'] = 'Complaint for Order #{ORDER_ID}';
$L['mstoremailorder_complaint_email_body'] = 'A complaint has been received for order #{ORDER_ID} regarding the item "{ITEM_TITLE}".<br /><br />
<strong>Complaint from:</strong> {USER_LOGIN} ({USER_NAME})<br />
<strong>Complaint Text:</strong><br />{COMPLAINT_TEXT}<br /><br />
View order: {ORDER_URL}';

/* === Complaint Status Change Notifications === */
$L['mstoremailorder_complaint_status_changed'] = 'Complaint Status Updated';
$L['mstoremailorder_complaint_status_updated'] = 'The status of your complaint has been updated';
$L['mstoremailorder_complaint_new'] = 'New Complaint';

$L['mstoremailorder_item_title'] = 'Item Title';
$L['mstoremailorder_details'] = 'Order Details';
$L['mstoremailorder_cost'] = 'Cost';
$L['ID'] = 'ID';
$L['Item'] = 'Item';
$L['Seller'] = 'Seller';
$L['Date'] = 'Date';
$L['Status'] = 'Status';
$L['History'] = 'History';
$L['Actions'] = 'Actions';
$L['Filter'] = 'Filter';
$L['All'] = 'All';
$L['mstoremailorder_email_subject'] = 'Order Notification';
$L['mstoremailorder_status_email_subject'] = 'Order Status Update';

/**
 * Email templates for orders
 */
$L['mstoremailorder_buyer_email_body'] = <<<HTML
Your order #{ORDER_ID} has been successfully placed!<br /><br />
<strong>Order Details:</strong> <a href="{ORDER_URL}">View Order</a><br />
<strong>Item:</strong> <a href="{ITEM_URL}">{ITEM_TITLE}</a><br />
<strong>Quantity:</strong> {QUANTITY}<br />
<strong>Phone:</strong> {PHONE}<br />
<strong>Date:</strong> {DATE}<br />
<strong>Comment:</strong> {COMMENT}<br />
<strong>Seller Email:</strong> {SELLER_EMAIL}<br /><br />
Seller: <a href="{SELLER_PROFILE_URL}">{SELLER_NICKNAME}</a><br />
Your Buyer Profile: <a href="{BUYER_PROFILE_URL}">{BUYER_NICKNAME}</a>
HTML;

$L['mstoremailorder_seller_email_body'] = <<<HTML
A new order #{ORDER_ID} has been received!<br /><br />
<strong>Order Details:</strong> <a href="{ORDER_URL}">View Order</a><br />
<strong>Item:</strong> <a href="{ITEM_URL}">{ITEM_TITLE}</a><br />
<strong>Quantity:</strong> {QUANTITY}<br />
<strong>Buyer Phone:</strong> {PHONE}<br />
<strong>Date:</strong> {DATE}<br />
<strong>Comment:</strong> {COMMENT}<br />
<strong>Buyer Email:</strong> {BUYER_EMAIL}<br /><br />
Buyer: <a href="{BUYER_PROFILE_URL}">{BUYER_NICKNAME}</a><br />
Your Seller Profile: <a href="{SELLER_PROFILE_URL}">{SELLER_NICKNAME}</a>
HTML;

$L['mstoremailorder_admin_email_body'] = <<<HTML
A new order #{ORDER_ID} has been received!<br /><br />
<strong>Order Details:</strong> <a href="{ORDER_URL}">View Order</a><br />
<strong>Item:</strong> <a href="{ITEM_URL}">{ITEM_TITLE}</a><br />
<strong>Quantity:</strong> {QUANTITY}<br />
<strong>Buyer Phone:</strong> {PHONE}<br />
<strong>Date:</strong> {DATE}<br />
<strong>Comment:</strong> {COMMENT}<br />
<strong>Buyer Email:</strong> {BUYER_EMAIL}<br />
<strong>Seller Email:</strong> {SELLER_EMAIL}<br /><br />
Buyer: <a href="{BUYER_PROFILE_URL}">{BUYER_NICKNAME}</a><br />
Seller: <a href="{SELLER_PROFILE_URL}">{SELLER_NICKNAME}</a>
HTML;

$L['mstoremailorder_status_email_body'] = <<<HTML
Your order status has been updated!<br /><br />
<strong>Item:</strong> <a href="{ITEM_URL}">{ITEM_TITLE}</a><br />
<strong>Quantity:</strong> {QUANTITY}<br />
<strong>Phone:</strong> {PHONE}<br />
<strong>Date:</strong> {DATE}<br />
<strong>Comment:</strong> {COMMENT}<br />
<strong>Status:</strong> {STATUS}
HTML;

?>