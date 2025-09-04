<!-- 
/**
 * MStore Email Order plugin: Страница списка исходящих заказов
 * Filename: mstoremailorder.outgoing.tpl
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
	<nav aria-label="breadcrumb">
		<div class="ps-container-breadcrumb">
			<ol class="breadcrumb d-flex mb-0">
				<li class="breadcrumb-item"><a href="{PHP|cot_url('users', 'm=details')}">{PHP.L.Profile}</a></li>
				<li class="breadcrumb-item active">{PHP.L.mstoremailorder_outgoing_orders}</li>
			</ol>
		</div>
	</nav>
</div>
<div class="min-vh-50 px-2 py-4">
	<div class="px-0 m-0 row justify-content-center">
		<div class="col-12 container-3xl">
			<h2 class="mb-4">{PHP.L.mstoremailorder_outgoing_orders}</h2>
			{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
			<!-- IF {PHP.usr.id} == 0 -->
			<div class="alert alert-danger" role="alert">{PHP.L.mstoremailorder_login_required}</div>
			<!-- ELSE -->
			<form action="{FORM_URL}" method="get" class="mb-4">
				<input type="hidden" name="e" value="mstoremailorder" />
				<input type="hidden" name="m" value="outgoing" />
				<div class="row g-3 align-items-end">
					<div class="col-md-4">
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
					<div class="col-md-4">
						<label for="search" class="form-label">{PHP.L.mstoremailorder_filter_search}</label>
						<input type="text" id="search" name="search" value="{SEARCH}" placeholder="Email или название товара" class="form-control" />
					</div>
					<div class="col-md-4">
						<button type="submit" class="btn btn-primary w-100">{PHP.L.Filter}</button>
					</div>
				</div>
			</form>
			<!-- IF {OUTGOING_COUNT} > 0 -->
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead class="table-light">
						<tr>
							<th scope="col">{PHP.L.mstoremailorder_id}</th>
							<th scope="col">{PHP.L.mstoremailorder_item}</th>
							<th scope="col">{PHP.L.mstoremailorder_quantity}</th>
							<th scope="col">{PHP.L.mstoremailorder_cost}</th>
							<th scope="col">{PHP.L.Seller}</th>
							<th scope="col">{PHP.L.mstoremailorder_comment}</th>
							<th scope="col">{PHP.L.mstoremailorder_date}</th>
							<th scope="col">{PHP.L.mstoremailorder_status}</th>
							<th scope="col">{PHP.L.mstoremailorder_history}</th>
							<th scope="col">{PHP.L.mstoremailorder_complaint}</th>
						</tr>
					</thead>
					<tbody>
						<!-- BEGIN: OUTGOING -->
						<tr class="{ORDER_ODDEVEN}">
							<td><a href="{ORDER_DETAILS_URL}">{ORDER_ID}</a></td>
							<td><a href="{ORDER_ITEM_URL}">{ORDER_ITEM_TITLE}</a></td>
							<td>{ORDER_QUANTITY}</td>
							<td>{ORDER_COST}</td>
							<td>{ORDER_SELLER_NAME}</td>
							<td>{ORDER_COMMENT}</td>
							<td>{ORDER_DATE}</td>
							<td>{ORDER_STATUS_TEXT}</td>
							<td>
								<!-- BEGIN: HISTORY -->
								<p class="mb-1">{HISTORY_STATUS_TEXT} ({HISTORY_DATE})</p>
								<!-- END: HISTORY -->
							</td>
							<td><a href="{COMPLAINT_URL}" class="btn btn-secondary btn-sm">{PHP.L.mstoremailorder_complaint}</a></td>
						</tr>
						<!-- END: OUTGOING -->
					</tbody>
				</table>
			</div>
			<!-- ELSE -->
			<p class="text-muted">{PHP.L.mstoremailorder_no_outgoing_orders}</p>
			<!-- ENDIF -->
			<!-- IF {PAGINATION} -->
			<div class="d-flex justify-content-between align-items-center mt-4">
				<div class="pagination">
					{PAGINATION}
				</div>
				<span>{PHP.L.Page} {CURRENT_PAGE} {PHP.L.Of} {TOTAL_PAGES}</span>
			</div>
			<!-- ENDIF -->
			<!-- ENDIF -->
		</div>
	</div>
</div>
<!-- END: MAIN -->