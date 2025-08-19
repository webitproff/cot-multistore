<!-- BEGIN: MAIN -->
<div class="card mb-4">
	<div class="card-body">
		<h4 class="d-flex align-items-center mb-4">
			{PHP.L.mstore_user_products}
			<!-- IF {MSTORE_ADD_SHOWBUTTON} -->
			<a href="{MSTORE_ADD_URL}" class="btn btn-success ms-auto">
				{PHP.L.mstore_add_product}
			</a>
			<!-- ENDIF -->
		</h4>
		
		<ul class="nav nav-tabs mb-4">
			<li class="nav-item">
				<a class="nav-link" href="{PHP.urr.user_id|cot_url('users', 'm=details&id=$this&tab=mstore')}">
					{PHP.L.All}
				</a>
			</li>
			<!-- BEGIN: CAT_ROW -->
			<li class="nav-item <!-- IF {MSTORE_CAT_ROW_SELECT} -->active<!-- ENDIF -->">
				<a class="nav-link <!-- IF {MSTORE_CAT_ROW_SELECT} -->active<!-- ENDIF -->" href="{MSTORE_CAT_ROW_URL}">
					<!-- IF {MSTORE_CAT_ROW_ICON} -->
					<img src="{MSTORE_CAT_ROW_ICON}" alt="{MSTORE_CAT_ROW_TITLE}" class="me-1">
					<!-- ENDIF -->
					{MSTORE_CAT_ROW_TITLE}
					<span class="badge bg-dark ms-1">{MSTORE_CAT_ROW_COUNT_MSTORE}</span>
				</a>
			</li>
			<!-- END: CAT_ROW -->
		</ul>
		
		<hr>
		
		<div class="row g-4">
			<!-- BEGIN: MSTORE_ROWS -->
			<div class="col-12 col-md-6 col-xl-4">
				<div class="attacherPicIntList-card" style="background-color: var(--bs-sidebar-bg)">
					<a class="attacherPicIntList-thumbnail" data-fancybox="gallery" href="{MSTORE_ROW_URL}" data-caption="{MSTORE_ROW_TITLE}">
						<!-- IF {PHP|cot_plugin_active('attacher')} -->
						<!-- IF {MSTORE_ROW_ID|att_count('mstore', $this, '', 'images')} > 0 -->
						<div class="att-image">{MSTORE_ROW_ID|att_display('mstore',$this,'','attacher.display.indexmarketlist','images',1)}</div>
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
							<span class="ms-2 text-success fw-bold">{MSTORE_ROW_COSTDFLT} {PHP.cfg.mstore.currency}</span>
							<!-- ENDIF -->
						</div>
					</div>
				</div>
			</div>
			<!-- END: MSTORE_ROWS -->
		</div>
		
		<!-- IF {PAGENAV_COUNT} > 0 -->
		<nav aria-label="pagination" class="mt-4">
			<ul class="pagination justify-content-center">
				{PAGENAV_PAGES}
			</ul>
		</nav>
		<!-- ELSE -->
		<div class="alert alert-warning mt-4">
			{PHP.L.mstore_no_products}
		</div>
		<!-- ENDIF -->
	</div>
</div>
<!-- END: MAIN -->
