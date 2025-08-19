<!-- BEGIN: MAIN -->
<div class="border-bottom border-secondary py-3 px-3">
	<nav aria-label="breadcrumb">
		<div class="ps-container-breadcrumb">
			<ol class="breadcrumb d-flex mb-0">{MSTORE_BREADCRUMBS_ITEM}</ol>
		</div>
	</nav>
</div>
<div class="min-vh-50 px-0 py-4">
	<div class="px-0 m-0 row justify-content-center">
		<div class="col-12 container-3xl">
			{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"} 
			<div class="row align-items-center mb-4">
				<div class="col-md-8 col-lg-9 col-12">
					<h1 class="h4 mb-3">{MSTORE_TITLE}</h1>
				</div>
				<!-- IF {PHP|cot_auth('mstore', '{PHP.c}', 'W')} -->
				<div class="col-md-4 col-lg-3 col-12 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0">
					<a class="btn btn-outline-success" href="{PHP|cot_url('mstore', 'm=add', '&c={PHP.c}')}">{PHP.L.mstore_addtitle}</a>
				</div>
				<!-- ENDIF -->
			</div>
			<!-- IF {PHP.usr.isadmin} OR {PHP.usr.id} == {MSTORE_OWNER_ID} -->
			<p class="mb-1">
				<strong>{PHP.L.Status}:</strong>
				<span class="badge bg-warning text-black">{MSTORE_LOCAL_STATUS}</span>
			</p>
			<!-- ENDIF -->
			<!-- IF {MSTORE_DESCRIPTION} -->
			<h2 class="fs-6 mb-4">{MSTORE_DESCRIPTION}</h2>
			<!-- ENDIF -->
			<div class="main-content-container overflow-hidden">
				<div class="">
					<div class="">
						<div class="row">
							<div class="col-lg-6">
								<!-- IF {PHP|cot_plugin_active('attacher')} -->
								<!-- IF {MSTORE_ID|att_count('mstore', $this, '', 'images')} > 0 -->
								<div class="mb-5">{MSTORE_ID|att_display('mstore', $this, '', 'attacher.gallery.fancybox.mstore')}</div>
								<!-- ELSE -->
								<div class="mb-5">
									<div class="position-relative overflow-hidden rounded-5 shadow-bottom" style="aspect-ratio: 5 / 4; background-image: url('{PHP.R.mstore_default_image}'); background-size: cover; background-position: center;"></div>
								</div>
								<!-- ENDIF -->
								<!-- ELSE -->
								<div class="position-relative overflow-hidden rounded-5 shadow-bottom" style="aspect-ratio: 2 / 1;">
									<!-- IF {MSTORE_LINK_MAIN_IMAGE} -->
									<img src="{MSTORE_LINK_MAIN_IMAGE}" alt="{MSTORE_TITLE}" class="img-fluid object-fit-cover">
									<!-- ELSE -->
									<div class="position-relative overflow-hidden rounded-5 shadow-bottom" style="aspect-ratio: 2 / 1; background-image: url('{PHP.R.mstore_default_image}'); background-size: cover; background-position: center;"></div>
									<!-- ENDIF -->
								</div>
								<!-- ENDIF -->
							</div>
							<div class="col-lg-6">
								<div class="product-details-content ms-lg-4 ms-xl-3 ms-xxl-5 mt-4 mt-lg-0">
									<span class="bg-success bg-opacity-10 text-success d-inline-block py-1 px-3 rounded-3 mb-3">
										<i class="ri-check-line fs-16 position-relative top-1"></i>
										in stock
									</span>
									
									<!-- IF {PHP|cot_plugin_active('mstoreprice')} -->
									{MSTOREPRICE_PRICES}
									<hr>
									<!-- ENDIF -->
									
									<div class="d-flex gap-1 mb-3 pb-3 border-bottom">
										<i class="ri-star-fill fs-16 text-warning"></i>
										<i class="ri-star-fill fs-16 text-warning"></i>
										<i class="ri-star-fill fs-16 text-warning"></i>
										<i class="ri-star-fill fs-16 text-warning"></i>
										<i class="ri-star-fill fs-16 text-warning"></i>
										<span class="position-relative top-2 ms-2">(5 Reviews)</span>
									</div>
					<!-- IF {PHP.usr.maingrp} == 5 -->
					<!-- BEGIN: MSTORE_ADMIN -->
					<div class="card mb-4">
						<div class="card-header">
							<h2 class="h5 mb-0">{PHP.L.Adminpanel}</h2>
						</div>
						<div class="card-body p-0">
							<ul class="list-group list-group-flush">
								<!-- IF {PHP.usr.isadmin} -->
								<li class="list-group-item">
									<a href="{PHP|cot_url('admin')}">{PHP.L.Adminpanel}</a>
								</li>
								<!-- IF {PHP.structure.mstore.unvalidated.path} -->
								<li class="list-group-item">
									<a href="{PHP|cot_url('mstore', 'c=unvalidated')}" title="{PHP.structure.mstore.unvalidated.title}">{PHP.structure.mstore.unvalidated.title}</a>
								</li>
								<!-- ENDIF -->
								<!-- ENDIF -->
								<li class="list-group-item">
									<a href="{MSTORE_CAT|cot_url('mstore', 'm=add&c=$this')}">{PHP.L.mstore_addtitle}</a>
								</li>
								<li class="list-group-item">{MSTORE_ADMIN_UNVALIDATE}</li>
								<li class="list-group-item">{MSTORE_ADMIN_EDIT}</li>
								<!-- IF {I18N_LANG_ROW_CLASS} == "selected" -->
								<li class="list-group-item">
									<a href="{MSTORE_ADMIN_EDIT_URL}" class="btn btn-warning">{PHP.L.i18n_editing}</a>
								</li>
								<!-- ENDIF -->
								<li class="list-group-item">{MSTORE_ADMIN_CLONE}</li>
								<li class="list-group-item">{MSTORE_ADMIN_DELETE}</li>
								<!-- IF {MSTORE_I18N_TRANSLATE} -->
								<li class="list-group-item">{MSTORE_I18N_TRANSLATE}</li>
								<!-- ENDIF -->
								<!-- IF {MSTORE_I18N_DELETE} -->
								<li class="list-group-item">{MSTORE_I18N_DELETE}</li>
								<!-- ENDIF -->
							</ul>
						</div>
					</div>
					<!-- END: MSTORE_ADMIN -->
					<!-- ENDIF -->

						<div class="card">
							<div class="card-body">
								
								<div class="col-md-auto text-center text-md-start">
									<!-- IF {PHP|cot_plugin_active('userimages')} -->
									<!-- IF {MSTORE_OWNER_AVATAR_SRC} -->
									<img src="{MSTORE_OWNER_AVATAR_SRC}" alt="{MSTORE_OWNER_NICKNAME}" class="rounded-circle" width="50" height="50">
									<!-- ELSE -->
									<img src="{PHP.R.userimg_default_avatar}" alt="{MSTORE_OWNER_NICKNAME}" class="rounded-circle" width="50" height="50">
									<!-- ENDIF -->
									<!-- ENDIF -->
									<!-- IF {PHP|cot_plugin_active('whosonline')} -->
									<!-- IF {MSTORE_OWNER_ONLINE} -->
									<p class="my-2">
										<span class="badge text-bg-success">{PHP.L.Online}</span>
									</p>
									<!-- ELSE -->
									<p class="my-2">
										<span class="badge text-bg-secondary">{PHP.L.Offline}</span>
									</p>
									<!-- ENDIF -->
									<!-- ENDIF -->
								</div>
								<div class="col-md-auto text-center text-md-end">
									<h4 class="h5 mb-0">
										<!-- IF !{MSTORE_AUTHOR} OR {MSTORE_AUTHOR} == {MSTORE_OWNER_NAME} --> {MSTORE_OWNER}
										<!-- ELSE --> {MSTORE_AUTHOR}
										<!-- ENDIF -->
									</h4>
									<p class="small">{PHP.L.Lastlogged}: {MSTORE_OWNER_LASTLOG}</p>
								</div>
								
								<ul class="list-group list-group-flush">
									<!-- IF {PHP|cot_module_active('pm')} AND {PHP.usr.id} > 0 AND {PHP.usr.id} != {MSTORE_OWNER_ID} -->
									<li class="list-group-item px-0">
										<a href="{PHP.pag.user_id|cot_url('pm','m=send&to=$this', '', 1)}"><i class="fa-regular fa-envelope fa-xl me-3"></i> {PHP.L.users_sendpm}</a>
									</li>
									<!-- ENDIF -->
										<!-- IF {PHP.usr.id} == 0 -->
										<!-- Кнопка для гостей (перейти к логину) -->
										<a href="{PHP|cot_url('login')}"
										class="btn btn-outline-info"
										data-bs-toggle="tooltip"
										data-bs-placement="top"
										title="{PHP.L.pm_send_a_message_hint}">
											<i class="fa-regular fa-envelope"></i>
											<span>{PHP.L.pm_send_a_message}</span>
										</a>
										<!-- ENDIF -->
									<!-- IF {PHP.usr.id|cot_auth('mstore', '', 'W')} -->
									<!-- IF {PHP.usr.auth_write} -->
									<li class="list-group-item px-0">
										<a href="{MSTORE_CAT|cot_url('mstore', 'm=add&c=$this')}">{PHP.L.mstore_addtitle}</a>
									</li>
									<!-- ENDIF -->
									<!-- IF {PHP.usr.id} == {MSTORE_OWNER_ID} -->
									<li class="list-group-item px-0">
										<a href="{MSTORE_ID|cot_url('mstore', 'm=edit&id=$this')}">{PHP.L.Edit}</a>
									</li>
									<!-- ENDIF -->
									<!-- IF {I18N_LANG_ROW_CLASS} == "selected" -->
									<li class="list-group-item">
										<a href="{MSTORE_ADMIN_EDIT_URL}" class="btn btn-warning">{PHP.L.i18n_editing}</a>
									</li>
									<!-- ENDIF -->
									<!-- IF {MSTORE_I18N_TRANSLATE} -->
									<li class="list-group-item px-0">
										<a href="{PHP.url_i18n}">{PHP.L.i18n_translate}</a>
									</li>
									<!-- ENDIF -->
									<!-- ENDIF -->
									<!-- IF {MSTORE_CREATED} -->
									<li class="list-group-item px-0">
										<strong>{PHP.L.2wd_mstore_published}</strong> {MSTORE_CREATED}
									</li>
									<!-- ENDIF -->
									<!-- IF {MSTORE_UPDATED} -->
									<li class="list-group-item px-0">
										<strong>{PHP.L.2wd_mstore_latest_update}</strong> {MSTORE_UPDATED}
									</li>
									<!-- ENDIF -->
									<!-- IF {PHP.pag_i18n_locales} > 1 -->
									<!-- BEGIN: I18N_LANG -->
									<li class="list-group-item px-0">
										<strong>{PHP.L.Language}:</strong>
										<ul class="list-inline mt-1">
											<!-- BEGIN: I18N_LANG_ROW -->
											<!-- IF {PHP.i18n_locale} != {I18N_LANG_ROW_CODE} -->
											<li class="list-inline-item">
												<a href="{I18N_LANG_ROW_URL}">
													<!-- IF {I18N_LANG_ROW_CODE|is_file('images/flags/$this.png')} -->
													<img src="images/flags/{I18N_LANG_ROW_CODE}.png" alt="{I18N_LANG_ROW_CODE}" class="me-1" style="width: 16px;">
													<!-- ENDIF --> {I18N_LANG_ROW_TITLE}
												</a>
											</li>
											<!-- ENDIF -->
											<!-- END: I18N_LANG_ROW -->
										</ul>
									</li>
									<!-- END: I18N_LANG -->
									<!-- ENDIF -->
								</ul>
								
							</div>
						</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<ul class="nav nav-tabs review-tabs gap-3 border-0 bg-transparent" id="myTab" role="tablist">
					<li class="nav-item" role="presentation">
						<button class="nav-link border-0 fs-16 fw-medium active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description-tab-pane" type="button" role="tab" aria-controls="description-tab-pane" aria-selected="true">Description</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link border-0 fs-16 fw-medium" id="specification-tab" data-bs-toggle="tab" data-bs-target="#specification-tab-pane" type="button" role="tab" aria-controls="specification-tab-pane" aria-selected="false">Specification</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link border-0 fs-16 fw-medium" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews-tab-pane" type="button" role="tab" aria-controls="reviews-tab-pane" aria-selected="false">
									<!-- IF {MSTORE_COMMENTS_COUNT} > 0 -->
									{PHP.L.2wd_Comments}: {MSTORE_COMMENTS_COUNT}
									<!-- ENDIF -->
						</button>
					</li>
					<li class="nav-item" role="presentation">
						<button class="nav-link border-0 fs-16 fw-medium" id="seller-tab" data-bs-toggle="tab" data-bs-target="#seller-tab-pane" type="button" role="tab" aria-controls="seller-tab-pane" aria-selected="false">Seller</button>
					</li>
				</ul>
				
				<div class="tab-content" id="myTabContent">
					<div class="tab-pane fade show active" id="description-tab-pane" role="tabpanel" aria-labelledby="description-tab" tabindex="0">
						<div class="card border-0 rounded-3 mb-4 rounded-top-0">
							<div class="card-body">
								{MSTORE_TEXT}
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="specification-tab-pane" role="tabpanel" aria-labelledby="specification-tab" tabindex="0">
						<div class="card border-0 rounded-3 mb-4 rounded-top-0">
							<div class="card-body">
								<!-- IF {PHP|cot_plugin_active('mstorefilter')} -->
								<h3>{PHP.L.mstorefilter_paramsItem}</h3>
								<dl class="row">
									<!-- BEGIN: MSTORE_FILTER_PARAMS -->
									<dt class="col-sm-4">{PARAM_TITLE}</dt>
									<dd class="col-sm-8">{PARAM_VALUE}</dd>
									<!-- END: MSTORE_FILTER_PARAMS -->
								</dl>
								<!-- ENDIF -->
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="reviews-tab-pane" role="tabpanel" aria-labelledby="reviews-tab" tabindex="0">
						<div class="card border-0 rounded-3 mb-4 rounded-top-0">
						пока пусто
						</div>
					</div>
					<div class="tab-pane fade" id="seller-tab-pane" role="tabpanel" aria-labelledby="seller-tab" tabindex="0">

					</div>
				</div>
			</div>
			
			<div class="flex-grow-1"></div>
			<div class="row pt-5">
				<div class="col-12 col-md-8 mx-auto pb-5">
					<!-- IF {PHP|cot_plugin_active('attacher')} -->
					<!-- IF {MSTORE_ID|att_count('mstore', $this, '', 'files')} > 0 -->
					<div class="mb-4" data-att-downloads="download">
						<h5>{PHP.L.att_attachments} {PHP.L.att_downloads}</h5> 
						{MSTORE_ID|att_downloads('mstore', $this)}
					</div>
					<!-- ENDIF -->
					<!-- ENDIF -->
					
				</div>
				<div class="col-12 col-md-4">
					
					<!-- BEGIN: MSTORE_MULTI -->
					<div class="card mb-4">
						<div class="card-header">
							<h2 class="h5 mb-0">{PHP.L.Summary}</h2>
						</div>
						<div class="card-body"> 
							{MSTORE_MULTI_TABTITLES} 
							<p class="mb-0">{MSTORE_MULTI_TABNAV}</p>
						</div>
					</div>
					<!-- END: MSTORE_MULTI -->


						
						<!-- IF {PHP|cot_plugin_active('similar')} --> 
						{MSTORE_SIMILAR}
						<!-- ENDIF -->

				</div>
				<blockquote>
					<p>{PHP.cfg.mstore.mstorelist_default_title}</p>
					<p>{PHP.cfg.mstore.mstorelist_default_desc}</p>
				</blockquote>
				
			</div>
		</div>
	</div>
	<!-- IF {PHP.usr.maingrp} == 5 AND {PHP.mskin} --> 
	{FILE "{PHP.cfg.themes_dir}/{PHP.usr.theme}/inc/mskin.tpl"}
	<!-- ENDIF -->
<!-- END: MAIN -->