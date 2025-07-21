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
                <div class="input-group has-validation">{MSTOREADD_FORM_CAT}</div>
              </div>
              <div class="col-12">
                <label for="mstoreTitle" class="form-label fw-semibold">{PHP.L.Title}</label>
                <div class="input-group has-validation">{MSTOREADD_FORM_TITLE}</div>
              </div>
              <div class="col-12">
                <label for="mstoreDesc" class="form-label fw-semibold">{PHP.L.Description}</label>
                <div class="input-group has-validation">{MSTOREADD_FORM_DESCRIPTION}</div>
              </div>
              <div class="col-12">
                <label for="mstoreAuthor" class="form-label fw-semibold">{PHP.L.Author}</label>
                <div class="input-group has-validation">{MSTOREADD_FORM_AUTHOR}</div>
              </div>
              <div class="col-12">
                <label for="mstoreAlias" class="form-label fw-semibold">{PHP.L.Alias}</label>
                <div class="input-group has-validation">{MSTOREADD_FORM_ALIAS}</div>
              </div>
              <div class="col-12">
                <label for="mstoreKeywords" class="form-label fw-semibold">{PHP.L.mstore_metakeywords}</label>
                <div class="input-group has-validation">{MSTOREADD_FORM_KEYWORDS}</div>
              </div>
              <div class="col-12">
                <label for="mstoreMetaTitle" class="form-label fw-semibold">{PHP.L.mstore_metatitle}</label>
                <div class="input-group has-validation">{MSTOREADD_FORM_METATITLE}</div>
              </div>
              <div class="col-12">
                <label for="mstoreMetaDesc" class="form-label fw-semibold">{PHP.L.mstore_metadesc}</label>
                <div class="input-group has-validation">{MSTOREADD_FORM_METADESC}</div>
              </div>
              <!-- BEGIN: TAGS -->
              <div class="col-12">
                <label for="mstoreTags" class="form-label fw-semibold">{MSTOREADD_TOP_TAGS}</label>
                <div class="input-group has-validation">{MSTOREADD_FORM_TAGS}</div>
                <small class="form-text text-muted">{MSTOREADD_TOP_TAGS_HINT}</small>
              </div>
              <!-- END: TAGS -->
              <div class="col-12">
                <label for="mstoreOwner" class="form-label fw-semibold">{PHP.L.Owner}</label>
                <div class="input-group has-validation">{MSTOREADD_FORM_OWNER}</div>
              </div>
              <div class="col-12">
                <label for="mstoreBegin" class="form-label fw-semibold">{PHP.L.Begin}</label>
                <div>{MSTOREADD_FORM_BEGIN}</div>
              </div>
              <div class="col-12">
                <label for="mstoreExpire" class="form-label fw-semibold">{PHP.L.Expire}</label>
                <div>{MSTOREADD_FORM_EXPIRE}</div>
              </div>
              <div class="col-12">
                <label for="mstoreParser" class="form-label fw-semibold">{PHP.L.Parser}</label>
                <div class="input-group has-validation">{MSTOREADD_FORM_PARSER}</div>
              </div>
              <div class="col-12">
                <label for="mstoreText" class="form-label fw-semibold">{PHP.L.Text}</label>
                {MSTOREADD_FORM_TEXT}
              </div>
              <!-- IF {MSTOREADD_FORM_LINK_MAIN_IMAGE} -->
              <div class="col-12">
                <label for="mstoreLinkMainImage" class="form-label fw-semibold">{MSTOREADD_FORM_LINK_MAIN_IMAGE_TITLE}</label>
                <div class="input-group has-validation">{MSTOREADD_FORM_LINK_MAIN_IMAGE}</div>
                <small class="form-text text-muted">{PHP.L.2wd_mstore_LinkMainImage_hint}</small>
              </div>
              <!-- ENDIF -->
              <!-- IF {PHP|cot_module_active('pfs')} -->
              <div class="col-12">
                <label for="mstoreText" class="form-label fw-semibold">{PHP.L.2wd_PFS}</label>
                <div class="mt-2">
                  <!-- IF {MSTOREADD_FORM_PFS} -->{MSTOREADD_FORM_PFS}
                  <!-- ENDIF -->
                  <!-- IF {MSTOREADD_FORM_SFS} -->
                  <span class="me-2">{PHP.cfg.separator}</span>{MSTOREADD_FORM_SFS}
                  <!-- ENDIF -->
                </div>
              </div>
              <div class="col-12">
                <label for="mstoreFile" class="form-label fw-semibold">{PHP.L.mstore_file}</label>
                <div class="input-group has-validation">{MSTOREADD_FORM_FILE}</div>
                <small class="form-text text-muted">{PHP.L.mstore_filehint}</small>
              </div>
              <div class="col-12">
                <label for="mstoreUrl" class="form-label fw-semibold">{PHP.L.URL}</label>
                <div class="input-group has-validation">{MSTOREADD_FORM_URL}</div>
                <div class="mt-2">{MSTOREADD_FORM_URL_PFS}   {MSTOREADD_FORM_URL_SFS}</div>
                <small class="form-text text-muted">{PHP.L.mstore_urlhint}</small>
              </div>
              <div class="col-12">
                <label for="mstoreSize" class="form-label fw-semibold">{PHP.L.Filesize}</label>
                <div class="input-group has-validation">{MSTOREADD_FORM_SIZE}</div>
                <small class="form-text text-muted">{PHP.L.mstore_filesizehint}</small>
              </div>
              <!-- ENDIF -->
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