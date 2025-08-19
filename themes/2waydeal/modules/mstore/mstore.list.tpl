<!-- BEGIN: MAIN -->
<div class="border-bottom border-secondary py-3 px-3">
	<nav aria-label="breadcrumb">
		<div class="ps-container-breadcrumb">
			<ol class="breadcrumb d-flex mb-0">{LIST_BREADCRUMBS_FULL}</ol>
		</div>
	</nav>
</div>
<div class="min-vh-50 px-2 pb-4">
	<div class="px-0 m-0 row justify-content-center">
		<div class="col-12 container-3xl">
			<!-- IF {PHP|cot_plugin_active('mstorefilter')} -->
			<div class="alert {MSTOREFILTER_MESSAGE_CLASS}"> {MSTOREFILTER_MESSAGE} </div>
			<!-- ENDIF --> 
			{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"} 
			<div class="col-12">
				<div class="row align-items-center mb-2">
					<div class="col-md-8 col-lg-9 col-12 col-auto">
						<!-- IF {PHP.c} == '' -->
						<h1 class="h4 mt-0">{PHP.cfg.mstore.mstorelist_default_title}</h1>
						<!-- ENDIF -->
						<!-- IF {PHP.c} -->
						<div class="row align-items-center">
							<div class="col-auto">
								<div class="position-relative">
									<!-- IF {LIST_CAT_ICON} -->
									<img width="27" height="27" alt="{LIST_CAT_TITLE}" src="{LIST_CAT_ICON_SRC}">
									<!-- ELSE -->
									<img width="27" height="27" alt="{LIST_CAT_TITLE}" src="{PHP.R.mstore_icon_cat_default}">
									<!-- ENDIF -->
									<!-- IF {PHP.cat.count} > 0 -->
									<span class="position-absolute top-0 start-100 translate-middle badge text-bg-primary">{PHP.cat.count}</span>
									<!-- ENDIF -->
								</div>
							</div>
							<div class="col">
								<h1 class="h4 mb-0">{LIST_CAT_TITLE}</h1>
							</div>
						</div>
						<!-- ENDIF -->
					</div>
					<!-- IF {PHP|cot_auth('mstore', 'any', 'W')} -->
					<div class="col-md-4 col-lg-3 col-12 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0">
						<a class="btn btn-outline-success" href="{PHP|cot_url('mstore', 'm=add')}">{PHP.L.mstore_addtitle}</a>
					</div>
					<!-- ENDIF -->
				</div>
				<!-- IF {LIST_CAT_DESCRIPTION} -->
				<h2 class="h5 mb-4">{LIST_CAT_DESCRIPTION}</h2>
				<!-- ENDIF -->
			</div>
			<div class="row px-0">
				<div class="col-12 col-lg-4 col-xl-3">
					<div class="card mb-4 pb-4"> 
						{PHP|cot_build_structure_mstore_tree('', '')} 
					</div>
					<!-- IF {PHP|cot_plugin_active('mstorefilter')} --> 
					{MSTORE_FILTER_FORM}
					<!-- ENDIF -->
					<!-- IF {PHP.usr.maingrp} == 5 -->
					<div class="card mt-4 mb-4">
						<div class="card-header">
							<h2 class="h5 mb-0">{PHP.L.2wd_toolsAdmin}</h2>
						</div>
						<div class="card-body">
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
								<li class="list-group-item">{LIST_SUBMIT_NEW_ITEM}</li>
							</ul>
						</div>
					</div>
					<!-- ENDIF -->
					<!-- IF {PHP|cot_plugin_active('mstorereviews')} --> 
					{PHP|cot_mstorereviews_last(4)}
					<!-- ENDIF -->
					<!-- IF {PHP|cot_plugin_active('tags')} -->
					<div class="card mt-4 mb-4">
						<div class="card-header">
							<h2 class="h5 mb-0">{PHP.L.Tags}</h2>
						</div>
						<div class="card-body">{MSTORE_TAG_CLOUD}</div>
					</div>
					<!-- ENDIF -->
				</div>
				<div class="col-12 col-lg-8 col-xl-9">
				<div class="card card-body mb-3">
					<form action="{MSTORE_SEARCH_ACTION_URL}" method="get" class="row g-2">
						<input type="hidden" name="e" value="mstore">
						<input type="hidden" name="l" value="{PHP.lang}" />
						
						<div class="col-md-6">
							{MSTORE_SEARCH_SQ}
						</div>
						
						<div class="col-md-4">
							{MSTORE_SEARCH_CAT_SELECT2}
						</div>
						
						<div class="col-md-2">
							<button type="submit" class="btn btn-primary w-100">{PHP.L.Search}</button>
						</div>
						<!-- IF {MSTORE_SEARCH_RESULT_MSG} --> 
						<div class="alert alert-info" role="alert">
							{MSTORE_SEARCH_RESULT_MSG}
						</div>
						<!-- ENDIF -->
					</form>
					</div>
					<!-- BEGIN: LIST_ROW -->
					<div class="card mb-3">
						<div class="row g-0 align-items-stretch">
							<div class="col-md-5 col-lg-4 d-flex" style="background-color: var(--bs-card-cap-bg)">
								<a href="{LIST_ROW_URL}" class="attacherPicIntList-thumbnail-left-position-pict text-decoration-none flex-grow-1" title="{LIST_ROW_TITLE}">
									<!-- IF {PHP|cot_plugin_active('attacher')} -->
									<!-- IF {LIST_ROW_ID|att_count('mstore', $this, '', 'images')} > 0 --> 
									{LIST_ROW_ID|att_display('mstore', $this, '', 'attacher.display.mstorelist', 'images', 1)}
									<!-- ELSE -->
									<!-- IF {LIST_ROW_LINK_MAIN_IMAGE} -->
									<img src="{LIST_ROW_LINK_MAIN_IMAGE}" alt="{LIST_ROW_TITLE}" class="img-fluid object-fit-cover h-100 w-100">
									<!-- ELSE -->
									<img src="{PHP.R.mstore_default_image}" alt="{LIST_ROW_TITLE}" class="img-fluid object-fit-cover h-100 w-100">
									<!-- ENDIF -->
									<!-- ENDIF -->
									<!-- ENDIF -->
								</a>
							</div>
							<div class="col-md-7 col-lg-8">
								<div class="card position-relative h-100 border-0">
									<div class="card-header border-0 rounded-0">
										<h3 class="card-title mb-0 fs-6">
											<a href="{LIST_ROW_URL}" class="text-decoration-none" title="{LIST_ROW_TITLE}">{LIST_ROW_TITLE}</a>
										</h3>
										<!-- IF {PHP.usr.isadmin} OR {PHP.usr.id} == {LIST_ROW_OWNER_ID} -->
										<p class="mb-1">
											<strong>{PHP.L.Status}:</strong>
											<span class="badge bg-warning text-black">{LIST_ROW_LOCAL_STATUS}</span>
										</p>
										<!-- ENDIF -->
										<!-- IF {LIST_ROW_COSTDFLT} > 0 -->
										<span class="ms-2 text-success fw-bold">{LIST_ROW_COSTDFLT} {PHP.cfg.mstore.currency}</span>
										<!-- ENDIF -->
									</div>
									<div class="card-body border-top d-flex flex-column justify-content-center"> 
										{LIST_ROW_FILTER_PARAMS_HTML}
										<!-- IF {LIST_ROW_DESCRIPTION} -->
										<p class="text-muted">{LIST_ROW_DESCRIPTION}</p>
										<!-- ELSE -->
										<p class="text-secondary">{LIST_ROW_TEXT_CUT|strip_tags($this)|cot_string_truncate($this, '170')}</p>
										<!-- ENDIF --> {LIST_ROW_PRICE_ROWS_HTML} 
										<p class="card-text">
											<small class="text-body-secondary">{LIST_ROW_CREATED}</small>
										</p>
										<!-- IF {LIST_ROW_COMMENTS_COUNT} > 0 -->
										<div class="position-absolute top-0 end-0 mt-2 me-2" data-bs-toggle="tooltip" data-bs-title="{PHP.L.2wd_Comments}">
											<span class="badge bg-primary">{LIST_ROW_COMMENTS_COUNT}</span>
										</div>
										<!-- ENDIF -->
									</div>
									<div class="card-footer text-end border-top rounded-0">
										<div class="row">
											<!-- IF {LIST_ROW_ADMIN} -->
											<div class="col-12 col-md-auto my-1">
												<p class="my-0 list-row-admin-link">{LIST_ROW_ADMIN}, {LIST_ROW_ADMIN_DELETE}, </p>
											</div>
											<!-- ENDIF -->
											<div class="col-12 col-md-4 d-flex justify-content-end align-items-center">
												<span class="me-2">({LIST_ROW_HITS})</span>
												<a href="{LIST_ROW_URL}" class="btn btn-outline-primary btn-sm">{PHP.L.ReadMore}</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- END: LIST_ROW -->
					<!-- IF {PAGINATION} -->
					<nav aria-label="Mstore Pagination" class="mt-3">
						<div class="text-center mb-2">{PHP.L.Page} {CURRENT_PAGE} {PHP.L.Of} {TOTAL_PAGES}</div>
						<ul class="pagination justify-content-center">{PREVIOUS_PAGE} {PAGINATION} {NEXT_PAGE}</ul>
					</nav>
					<!-- ENDIF -->
				</div>
			</div>
			<!-- IF {PHP.c} -->
			<blockquote>
				<p>{PHP.cfg.mstore.mstorelist_default_title}</p>
				<p>{PHP.cfg.mstore.mstorelist_default_desc}</p>
			</blockquote>
			<!-- ENDIF -->
		</div>
	</div>
</div>
<!-- IF {PHP.usr.maingrp} == 5 AND {PHP.mskin} --> {FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/inc/mskin.tpl"}
<!-- ENDIF -->
<!-- END: MAIN -->