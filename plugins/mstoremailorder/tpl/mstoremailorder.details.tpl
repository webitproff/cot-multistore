<!-- 
/**
 * MStore Email Order plugin: Страница уже созданного заказа со всеми деталями
 * Filename: mstoremailorder.details.tpl
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
            <ol class="breadcrumb d-flex mb-0">{PHP.L.mstoremailorder_details}</ol>
		</div>
	</nav>
</div>

<div class="min-vh-50 px-2 px-md-3 py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 mx-auto">
            <div class="card mt-4 mb-4">
                <div class="card-header">
                    <h2 class="h5 mb-0">{PHP.L.mstoremailorder_details} #{ORDER_ID}</h2>
				</div>
                <div class="card-body">
                    {FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"} 
                    <!-- IF {ORDER_ID} -->
                    <div class="row">
                        <div class="col-12">
                            <h4>{PHP.L.mstoremailorder_item}</h4>
                            <p><strong>{PHP.L.Item}:</strong> <a href="{ORDER_ITEM_URL}">{ORDER_ITEM_TITLE}</a></p>
                            <p><strong>{PHP.L.mstoremailorder_cost}:</strong> {ORDER_COST}</p>
                            <p><strong>{PHP.L.mstoremailorder_quantity}:</strong> {ORDER_QUANTITY}</p>
                            <p><strong>{PHP.L.mstoremailorder_comment}:</strong> {ORDER_COMMENT}</p>
                            <p><strong>{PHP.L.mstoremailorder_date}:</strong> {ORDER_DATE}</p>
                            <p><strong>{PHP.L.mstoremailorder_status}:</strong> {ORDER_STATUS_TEXT}</p>
						</div>
                        <div class="col-12 col-md-6">
                            <h4>{PHP.L.mstoremailorder_buyer}</h4>
                            <p><strong>{PHP.L.Name}:</strong> {ORDER_BUYER_NAME}</p>
                            <p><strong>{PHP.L.mstoremailorder_email}:</strong> {ORDER_BUYER_EMAIL}</p>
                            <p><strong>{PHP.L.mstoremailorder_phone}:</strong> {ORDER_PHONE}</p>
						</div>
                        <div class="col-12 col-md-6">
                            <h4>{PHP.L.Seller}</h4>
                            <p><strong>{PHP.L.Name}:</strong> <a href="{ORDER_SELLER_PROFILE_URL}">{ORDER_SELLER_NAME}</a></p>
                            <p><strong>{PHP.L.mstoremailorder_email}:</strong> {ORDER_SELLER_EMAIL}</p>
						</div>
                        <!-- IF {PHP.usr.id} == {ORDER_SELLER_ID} -->
                        <div class="col-12 mt-3">
                            <h4>{PHP.L.mstoremailorder_update_status}</h4>
                            <form action="{ORDER_UPDATE_URL}" method="post" class="d-flex gap-2">
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
                        <!-- ENDIF -->
                        <div class="col-12 mt-3">
                            <h4>{PHP.L.mstoremailorder_history}</h4>
                            <!-- BEGIN: HISTORY -->
                            <p class="mb-1">{HISTORY_STATUS_TEXT} ({HISTORY_DATE})</p>
                            <!-- END: HISTORY -->
						</div>
                        <div class="col-12 mt-3">
                            <h4>{PHP.L.mstoremailorder_complaint}</h4>{COMPLAINTS_COUNT}
                            <!-- BEGIN: COMPLAINTS -->
                            <p class="mb-1">{COMPLAINT_TEXT} ({COMPLAINT_DATE}, {COMPLAINT_STATUS_TEXT})</p>
                            <!-- END: COMPLAINTS -->
                            <!-- IF !{COMPLAINTS_COUNT} -->
                            <p class="text-muted">{PHP.L.None}</p>
                            <!-- ENDIF -->
                            <a href="{COMPLAINT_URL}" class="btn btn-secondary btn-sm mt-2">{PHP.L.mstoremailorder_complaint}</a>
						</div>
					</div>
                    <!-- ELSE -->
                    <div class="alert alert-danger" role="alert">Order not found or you are not authorized.</div>
                    <!-- ENDIF -->
				</div>
			</div>
		</div>
	</div>
</div>
<!-- END: MAIN -->