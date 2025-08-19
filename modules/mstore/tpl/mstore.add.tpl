<!-- BEGIN: MAIN -->
<div class="border-bottom border-secondary py-3 px-3">
	<nav aria-label="breadcrumb">
		<div class="ps-container-breadcrumb">
			<ol class="breadcrumb d-flex mb-0">{MSTOREADD_BREADCRUMBS}</ol>
		</div>
	</nav>
</div>
<div class="min-vh-50 px-2 px-md-3 py-5">
	<!-- IF !{PHP.usr_can_publish} -->
	<div class="mb-3 mt-3">
		<div class="alert alert-info" role="alert">{PHP.L.mstore_formhint}</div>
	</div>
	<!-- ENDIF -->
	<div class="row justify-content-center">
		<div class="col-12 col-md-10 mx-auto">
			<div class="card mt-4 mb-4">
				<div class="card-header">
					<h2 class="h5 mb-0">{MSTOREADD_PAGETITLE}</h2>
				</div>
				<div class="card-body">
					{FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
					<form action="{MSTOREADD_FORM_SEND}" enctype="multipart/form-data" method="post" name="mstoreform" class="needs-validation" novalidate>
						<div class="row g-3">
							<div class="col-12">
								<label for="mstoreCat" class="form-label fw-semibold">{PHP.L.Category}</label>
								<div class="input-group has-validation">{MSTOREADD_FORM_CAT_S2}</div>
							</div>
							<div class="col-12">
								<label for="mstoreTitle" class="form-label fw-semibold">{PHP.L.Title}</label>
								<div class="input-group has-validation">{MSTOREADD_FORM_TITLE}</div>
							</div>
							<div class="col-12">
								<label for="mstoreDesc" class="form-label fw-semibold mb-0">{PHP.L.Description}</label>
								<div class="mb-3"><small class="form-text text-muted">{PHP.L.mstore_addedit_desc}</small></div>
								<div class="input-group has-validation">{MSTOREADD_FORM_DESCRIPTION}</div>
							</div>
							<div class="col-12">
								<label for="mstoreMetaTitle" class="form-label fw-semibold mb-0">{PHP.L.mstore_metatitle}</label>
								<div class="mb-3"><small class="form-text text-muted">{PHP.L.mstore_addedit_seo}</small></div>
								<div class="input-group has-validation">{MSTOREADD_FORM_METATITLE}</div>
							</div>
							<div class="col-12">
								<label for="mstoreMetaDesc" class="form-label fw-semibold mb-0">{PHP.L.mstore_metadesc}</label>
								<div class="mb-3"><small class="form-text text-muted">{PHP.L.mstore_addedit_seo}</small></div>
								<div class="input-group has-validation">{MSTOREADD_FORM_METADESC}</div>
							</div>
							<!-- BEGIN: TAGS -->
							<div class="col-12">
								<label for="mstoreTags" class="form-label fw-semibold">{MSTOREADD_TOP_TAGS}</label>
								<div class="input-group has-validation">{MSTOREADD_FORM_TAGS}</div>
								<small class="form-text text-muted">{MSTOREADD_TOP_TAGS_HINT}</small>
							</div>
							<!-- END: TAGS -->
							<!-- IF {PHP.usr.isadmin} -->
							<div class="col-12">
								<label for="mstoreOwner" class="form-label fw-semibold">{PHP.L.Owner}</label>
								<div class="input-group has-validation">{MSTOREADD_FORM_OWNER}</div>
							</div>
							<div class="col-12">
								<label for="mstoreAlias" class="form-label fw-semibold">{PHP.L.Alias}</label>
								<div class="input-group has-validation">{MSTOREADD_FORM_ALIAS}</div>
							</div>
							<div class="col-12">
								<label for="mstoreParser" class="form-label fw-semibold">{PHP.L.Parser}</label>
								<div class="input-group has-validation">{MSTOREADD_FORM_PARSER}</div>
							</div>
							<!-- ENDIF -->
							<div class="mb-3 col-12">
								<label for="mstoreText" class="form-label fw-semibold mb-0">{PHP.L.mstore_addedit_text}</label>
								<div class="mb-3"><small class="form-text text-muted">{PHP.L.mstore_addedit_text_hint}</small></div>
								{MSTOREADD_FORM_TEXT}
							</div>
							<div class="mb-3 row">
								<label class="col-sm-3 col-form-label fw-semibold">{PHP.L.mstore_price}</label>
								<div class="col-sm-9">
									<div class="input-group">
										{MSTOREADD_FORM_COSTDFLT}
										<span class="input-group-text">
											<!-- IF {PHP.cfg.payments.valuta} -->
											{PHP.cfg.payments.valuta}
											<!-- ELSE -->
											{PHP.cfg.mstore.mstore_currency}
											<!-- ENDIF -->
										</span>
									</div>
								</div>
							</div>
							<!-- IF {PHP|cot_plugin_active('attacher')} -->
							<!-- IF {PHP|cot_auth('plug', 'attacher', 'W')} -->
							<div class="col-12">
								<label class="form-label fw-semibold">{PHP.L.att_add_pict_files}</label>
								<div class="input-group">{PHP|att_filebox('mstore', 0)}</div>
							</div>
							<!-- ENDIF -->
							<!-- ENDIF -->
							<div class="col-12">
								<div class="d-grid gap-2 d-md-flex justify-content-md-end">
									<!-- IF {PHP.usr_can_publish} -->
									<button type="submit" name="rmsitemstate" value="0" class="btn btn-success">{PHP.L.Publish}</button>
									<!-- ENDIF -->
									<button type="submit" name="rmsitemstate" value="2" class="btn btn-secondary">{PHP.L.Saveasdraft}</button>
									<button type="submit" name="rmsitemstate" value="1" class="btn btn-warning">{PHP.L.Submitforapproval}</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- This is the name of the template for informing the administrator -->
<!-- IF {PHP.usr.maingrp} == 5 AND {PHP.mskin} --> {FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/inc/mskin.tpl"}
<!-- ENDIF -->
<!-- END: MAIN -->