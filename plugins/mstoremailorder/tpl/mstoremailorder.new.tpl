<!-- 
/**
 * MStore Email Order plugin: Страница с формой создания нового заказа
 * Filename: mstoremailorder.new.tpl
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
				<li class="breadcrumb-item"><a href="{PHP|cot_url('mstore')}">{PHP.L.mstore}</a></li>
				<li class="breadcrumb-item"><a href="{ITEM_URL}">{ITEM_TITLE}</a></li>
				<li class="breadcrumb-item active">{PHP.L.mstoremailorder_new_order}</li>
			</ol>
		</div>
	</nav>
</div>
<div class="min-vh-50 px-2 py-4">
	<div class="px-0 m-0 row justify-content-center">
		<div class="col-12 container-3xl">
			<h2 class="mb-4">{PHP.L.mstoremailorder_new_order}</h2>
			{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
			<form action="{PHP|cot_url('plug', 'e=mstoremailorder&m=new&item_id={ITEM_ID}')}" method="post" class="mb-4">
				<input type="hidden" name="item_id" value="{ITEM_ID}" />
				<div class="row g-3">
					<div class="col-md-6">
						<label for="email" class="form-label">{PHP.L.mstoremailorder_email}</label>
						<input type="email" id="email" name="email" value="{EMAIL}" class="form-control" required />
					</div>
					<div class="col-md-6">
						<label for="phone" class="form-label">{PHP.L.mstoremailorder_phone}</label>
						<input type="text" id="phone" name="phone" value="{PHONE}" class="form-control" required />
					</div>
					<div class="col-md-6">
						<label for="quantity" class="form-label">{PHP.L.mstoremailorder_quantity}</label>
						<input type="number" id="quantity" name="quantity" value="1" min="1" class="form-control" required />
					</div>
					<div class="col-md-6">
						<label for="comment" class="form-label">{PHP.L.mstoremailorder_comment}</label>
						<textarea id="comment" name="comment" class="form-control" rows="4"></textarea>
					</div>
					<!-- BEGIN: CAPTCHA -->
					<div class="col-md-6">
						<div>{MSTOREEMAILORDER_FORM_VERIFY_IMG}</div>
						<div>{MSTOREEMAILORDER_FORM_VERIFY_INPUT}</div>
					</div>
					<!-- END: CAPTCHA -->
					<div class="col-12">
						<button type="submit" name="submit" value="submit" class="btn btn-primary">{PHP.L.mstoremailorder_submit}</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- END: MAIN -->