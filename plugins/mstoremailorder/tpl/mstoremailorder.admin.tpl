<!-- 
/**
 * MStore Email Order plugin: Администрирование заказов
 * Filename: mstoremailorder.admin.tpl
 * @package MStoreEmailOrder for CMF Cotonti Siena v.0.9.26 on PHP 8.4
 * Version=2.0.1
 * Date=2025-09-05
 * @author webitproff
 * @copyright Copyright (c) 2025 webitproff | https://github.com/webitproff
 * @license BSD License
 */
 -->
<!-- BEGIN: MAIN -->
<div class="container my-3">
	<div class="row g-3 text-center">
		<div class="col-12 col-md-4">
			<a href="{PHP|cot_url('admin', 'm=extensions&a=details&pl=mstoremailorder')}" 
			class="btn btn-outline-info w-100">
				{PHP.L.mstoremailorder_set}
			</a>
		</div>
		<div class="col-12 col-md-4">
			<a href="{PHP|cot_url('admin', 'm=other&p=mstoremailorder')}" 
			class="btn btn-outline-success w-100">
				{PHP.L.mstoremailorder}
			</a>
		</div>
		<div class="col-12 col-md-4">
			<a href="{ADMIN_COMPLAINTS_URL}" 
			class="btn btn-outline-danger w-100">
				{PHP.L.mstoremailorder_complaints}
			</a>
		</div>
	</div>
</div>

<div class="min-vh-50 px-2 py-4">
	<div class="px-0 m-0 row justify-content-center">
		<div class="col-12 container-3xl">
			<h2 class="mb-4">{PHP.L.mstoremailorder}</h2>
			
			{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
			
			<form action="{FORM_URL}" method="get" class="mb-4">
				<input type="hidden" name="m" value="other" />
				<input type="hidden" name="p" value="mstoremailorder" />
				<div class="row g-2 align-items-end">
					<div class="col-md-3">
						<label for="filter_status" class="form-label">{PHP.L.mstoremailorder_filter_status}</label>
						<select id="filter_status" name="filter_status" class="form-select">
							<option value="" {FILTER_STATUS_0_SELECTED}>{PHP.L.All}</option>
							<option value="0" {FILTER_STATUS_0_SELECTED}>{PHP.L.mstoremailorder_status_new}</option>
							<option value="1" {FILTER_STATUS_1_SELECTED}>{PHP.L.mstoremailorder_status_processing}</option>
							<option value="2" {FILTER_STATUS_2_SELECTED}>{PHP.L.mstoremailorder_status_completed}</option>
							<option value="3" {FILTER_STATUS_3_SELECTED}>{PHP.L.mstoremailorder_status_canceled}</option>
							<option value="4" {FILTER_STATUS_4_SELECTED}>{PHP.L.mstoremailorder_status_rejected}</option>
						</select>
					</div>
					<div class="col-md-3">
						<label for="order_id" class="form-label">{PHP.L.mstoremailorder_order_id}</label>
						<input type="text" id="order_id" name="order_id" value="{FILTER_ORDER_ID}" placeholder="12345" class="form-control" />
					</div>
					<div class="col-md-3">
						<label for="search" class="form-label">{PHP.L.mstoremailorder_filter_search}</label>
						<input type="text" id="search" name="search" value="{SEARCH}" placeholder="Email or Item Title" class="form-control" />
					</div>
					<div class="col-md-3 d-flex gap-2">
						<button type="submit" class="btn btn-primary w-100">{PHP.L.Filter}</button>
						<a href="{PHP|cot_url('admin', 'm=other&p=mstoremailorder')}" class="btn btn-secondary w-100">{PHP.L.Reset}</a>
					</div>
				</div>
			</form>
			
			<div class="list-group list-group-striped list-group-flush">
				<!-- BEGIN: ORDERS -->
				<li class="list-group-item {ORDER_ODDEVEN} py-2">
					<div class="row g-2 align-items-start">
						<div class="col-12 col-md-8">
							<h6 class="mb-1 fw-semibold">
								<a class="text-reset" href="{ORDER_DETAILS_URL}">#{ORDER_ID} - {ORDER_ITEM_TITLE}</a>
							</h6>
							<small class="d-block"><strong>{PHP.L.mstoremailorder_buyer}:</strong> {ORDER_BUYER_NAME} ({ORDER_EMAIL})</small>
							<small class="d-block"><strong>{PHP.L.Seller}:</strong> <a href="{ORDER_SELLER_PROFILE_URL}">{ORDER_SELLER_NAME}</a></small>
							<small class="d-block"><strong>{PHP.L.mstoremailorder_quantity}:</strong> {ORDER_QUANTITY} | <strong>{PHP.L.mstoremailorder_cost}:</strong> {ORDER_COST}</small>
							<small class="d-block"><strong>{PHP.L.mstoremailorder_phone}:</strong> {ORDER_PHONE}</small>
							<small class="d-block"><strong>{PHP.L.mstoremailorder_comment}:</strong> {ORDER_COMMENT}</small>
							<small class="d-block"><strong>{PHP.L.mstoremailorder_date}:</strong> {ORDER_DATE}</small>
							<small class="d-block"><strong>{PHP.L.mstoremailorder_status}:</strong> {ORDER_STATUS_TEXT}</small>
							<small class="d-block"><strong>{PHP.L.mstoremailorder_history}:</strong>
								<!-- BEGIN: HISTORY -->
								{HISTORY_STATUS_TEXT} ({HISTORY_DATE})<br>
								<!-- END: HISTORY -->
							</small>
						</div>
						
						<div class="col-12 col-md-4 text-center">
							<form action="{ORDER_UPDATE_URL}" method="post" class="d-flex flex-column gap-1 mb-1">
								<select name="new_status" class="form-select form-select-sm">
									<option value="0" {ORDER_STATUS_0_SELECTED}>{PHP.L.mstoremailorder_status_new}</option>
									<option value="1" {ORDER_STATUS_1_SELECTED}>{PHP.L.mstoremailorder_status_processing}</option>
									<option value="2" {ORDER_STATUS_2_SELECTED}>{PHP.L.mstoremailorder_status_completed}</option>
									<option value="3" {ORDER_STATUS_3_SELECTED}>{PHP.L.mstoremailorder_status_canceled}</option>
									<option value="4" {ORDER_STATUS_4_SELECTED}>{PHP.L.mstoremailorder_status_rejected}</option>
								</select>
								<button type="submit" class="btn btn-primary btn-sm">{PHP.L.mstoremailorder_update_status}</button>
							</form>
						</div>
					</div>
				</li>
				<!-- END: ORDERS -->
				
				<!-- BEGIN: NO_ORDERS -->
				<div class="list-group-item text-center text-muted py-3">{PHP.L.mstoremailorder_no_orders}</div>
				<!-- END: NO_ORDERS -->
			</div>
			
			<!-- IF {PAGINATION} -->
			<div class="d-flex justify-content-between align-items-center mt-4">
				<div class="pagination">{PAGINATION}</div>
				<span>{PHP.L.Page} {CURRENT_PAGE} {PHP.L.Of} {TOTAL_PAGES}</span>
			</div>
			<!-- ENDIF -->
			
		</div>
	</div>
</div>
<!-- END: MAIN -->
