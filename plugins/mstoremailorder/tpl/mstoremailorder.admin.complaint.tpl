<!-- 
/**
 * MStore Email Order plugin: Администрирование жалоб
 * Filename: mstoremailorder.admin.complaint.tpl
 * @package MStoreEmailOrder for CMF Cotonti Siena v.0.9.26 on PHP 8.4
 * Version=2.0.1
 * Date=2025-09-05
 * @author webitproff
 * @copyright Copyright (c) 2025 webitproff | https://github.com/webitproff
 * @license BSD License
 */
 -->
<!-- BEGIN: MAIN -->
<div class="border-bottom border-secondary py-3 px-3">
	<div class="container">
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
</div>

<div class="min-vh-50 px-2 py-4">
	<div class="px-0 m-0 row justify-content-center">
		<div class="col-12 container-3xl">
			<h2 class="mb-4">{PHP.L.mstoremailorder_complaints}</h2>
			{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
			
			<form action="{FORM_URL}" method="get" class="mb-4">
				<input type="hidden" name="m" value="other" />
				<input type="hidden" name="p" value="mstoremailorder" />
				<input type="hidden" name="sp" value="complaint" />
				
				<div class="row g-3 align-items-end">
					<div class="col-md-4">
						<label for="filter_status" class="form-label">{PHP.L.mstoremailorder_filter_status}</label>
						<select id="filter_status" name="filter_status" class="form-select">
							<option value="" {FILTER_STATUS_0_SELECTED}>{PHP.L.All}</option>
							<option value="0" {FILTER_STATUS_0_SELECTED}>{PHP.L.mstoremailorder_complaint_status_new}</option>
							<option value="1" {FILTER_STATUS_1_SELECTED}>{PHP.L.mstoremailorder_complaint_status_processing}</option>
							<option value="2" {FILTER_STATUS_2_SELECTED}>{PHP.L.mstoremailorder_complaint_status_resolved}</option>
						</select>
					</div>
					
					<div class="col-md-4">
						<label for="order_id" class="form-label">{PHP.L.mstoremailorder_order_id}</label>
						<input type="text" id="order_id" name="order_id" value="{ORDER_ID}" placeholder="Order ID" class="form-control" />
					</div>
					
					<div class="col-md-4">
						<button type="submit" class="btn btn-primary w-100">{PHP.L.Filter}</button>
					</div>
				</div>
			</form>
			
			<!-- IF {PHP.usr.isadmin} -->
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead class="table-light">
						<tr>
							<th scope="col">{PHP.L.mstoremailorder_complaint_id}</th>
							<th scope="col">{PHP.L.mstoremailorder_order_id}</th>
							<th scope="col">{PHP.L.mstoremailorder_item}</th>
							<th scope="col">{PHP.L.mstoremailorder_buyer}</th>
							<th scope="col">{PHP.L.Seller}</th>
							<th scope="col">{PHP.L.mstoremailorder_quantity}</th>
							<th scope="col">{PHP.L.mstoremailorder_cost}</th>
							<th scope="col">{PHP.L.mstoremailorder_complaint_text}</th>
							<th scope="col">{PHP.L.mstoremailorder_complaint_date}</th>
							<th scope="col">{PHP.L.mstoremailorder_complaint_status}</th>
							<th scope="col">{PHP.L.mstoremailorder_actions}</th>
						</tr>
					</thead>
					
					<tbody>
						<!-- BEGIN: COMPLAINTS -->
						<tr class="{COMPLAINT_ODDEVEN}">
							<td>{COMPLAINT_I}</td>
							<td><a href="{ORDER_DETAILS_URL}">{ORDER_ID}</a></td>
							<td><a href="{ORDER_ITEM_URL}">{ORDER_ITEM_TITLE}</a></td>
							<td>{ORDER_BUYER_NAME}</td>
							<td><a href="{ORDER_SELLER_PROFILE_URL}">{ORDER_SELLER_NAME}</a></td>
							<td>{ORDER_QUANTITY}</td>
							<td>{ORDER_COST}</td>
							<td>{COMPLAINT_TEXT}</td>
							<td>{COMPLAINT_DATE}</td>
							<td>{COMPLAINT_STATUS_TEXT}</td>
							<td>
								<form action="{COMPLAINT_UPDATE_URL}" method="post" class="d-flex gap-2">
									<select name="new_status" class="form-select form-select-sm">
										<option value="0" {COMPLAINT_STATUS_0_SELECTED}>{PHP.L.mstoremailorder_complaint_status_new}</option>
										<option value="1" {COMPLAINT_STATUS_1_SELECTED}>{PHP.L.mstoremailorder_complaint_status_processing}</option>
										<option value="2" {COMPLAINT_STATUS_2_SELECTED}>{PHP.L.mstoremailorder_complaint_status_resolved}</option>
									</select>
									<button type="submit" class="btn btn-primary btn-sm">{PHP.L.mstoremailorder_update_status}</button>
								</form>
							</td>
						</tr>
						<!-- END: COMPLAINTS -->
					</tbody>
				</table>
			</div>
			<!-- ENDIF -->
			
			<!-- IF !{PHP.usr.isadmin} -->
			<div class="alert alert-danger" role="alert">{PHP.L.mstoremailorder_access_denied}</div>
			<!-- ENDIF -->
			
			<!-- IF {PAGINATION} -->
			<div class="d-flex justify-content-between align-items-center mt-4">
				<div class="pagination">
					{PAGINATION}
				</div>
				<span>{PHP.L.Page} {CURRENT_PAGE} {PHP.L.Of} {TOTAL_PAGES}</span>
			</div>
			<!-- ENDIF -->
			
		</div>
	</div>
</div>
<!-- END: MAIN -->
