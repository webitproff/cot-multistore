<!-- BEGIN: MSTORE -->
<h3 class="text-success mb-4">{PHP.L.mstore_Mstore}</h3>
<div id="listmstore">
  <div class="row">
    <!-- BEGIN: MSTORE_ROW -->
    <div class="col-12 col-md-6 col-xl-4">
      <div class="attacherPicIntList-card" style="background-color: var(--bs-sidebar-bg)">
        <a class="attacherPicIntList-thumbnail" data-fancybox="gallery" href="{MSTORE_ROW_URL}" data-caption="{MSTORE_ROW_TITLE}">
          <!-- IF {PHP|cot_plugin_active('attacher')} -->
          <!-- IF {MSTORE_ROW_ID|att_count('mstore', $this, '', 'images')} > 0 -->
          <div class="att-image">{MSTORE_ROW_ID|att_display('mstore',$this,'','attacher.display.mstorelist','images',1)}</div>
          <!-- ELSE -->
          <img src="{PHP.R.page_default_image}" alt="{MSTORE_ROW_TITLE}">
          <!-- ENDIF -->
          <!-- ELSE -->
          <img src="{PHP.R.page_default_image}" alt="{MSTORE_ROW_TITLE}">
          <!-- ENDIF -->
        </a>
        <div class="attacherPicIntList-card-body">
          <div class="attacherPicIntList-title">
            <a href="{MSTORE_ROW_URL}" class="text-decoration-none" title="{MSTORE_ROW_TITLE}">{MSTORE_ROW_TITLE|cot_string_truncate($this, '64')}</a>
          </div>
          <div class="attacherPicIntList-desc">{MSTORE_ROW_CAT_TITLE}
            <!-- IF {MSTORE_ROW_COSTDFLT} > 0 -->
            <span class="ms-2 text-success fw-bold">{MSTORE_ROW_COSTDFLT} {PHP.cfg.mstore.mstore_currency}</span>
            <!-- ENDIF -->
          </div>
        </div>
      </div>
    </div>
    <!-- END: MSTORE_ROW -->
  </div>
</div>
<!-- END: MSTORE -->

