<!-- BEGIN: MAIN -->
<h2>{PHP.L.mstore}</h2>
<div class="wrapper">
	<ul class="std">
		<li><a href="{PHP|cot_url('admin','m=config&n=edit&o=module&p=mstore')}">{PHP.L.Configuration}</a></li>
		<li><a href="{ADMIN_HOME_URL}">{PHP.L.adm_valqueue}: {ADMIN_HOME_MSTOREQUEUED}</a></li>
		<li><a href="{PHP|cot_url('mstore','m=add')}">{PHP.L.Add}</a></li>
		<li><a href="{PHP.db_mstore|cot_url('admin','m=extrafields&n=$this')}">{PHP.L.home_extrafields_mstore}</a></li>
	</ul>
</div>
<!-- END: MAIN -->