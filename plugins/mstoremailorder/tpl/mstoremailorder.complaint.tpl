<!-- 
/**
 * MStore Email Order plugin: complaint form template
 * Filename: mstoremailorder.complaint.tpl
 * @package MStoreEmailOrder for CMF Cotonti Siena v.0.9.26 on PHP 8.4
 * Version=2.0.1
 * Date=2025-09-05
 * @author webitproff
 * @copyright Copyright (c) 2025 webitproff | https://github.com/webitproff
 * @license BSD License
 */
 -->
<!-- BEGIN: MAIN -->
<div class="container">
    <h2>{PHP.L.mstoremailorder_complaint_title}</h2>
    {FILE "{PHP.cfg.themes_dir}/{PHP.cfg.defaulttheme}/warnings.tpl"}
    <form action="{PHP|cot_url('plug', 'e=mstoremailorder&m=complaint')}" method="post" class="form-horizontal">
        <input type="hidden" name="order_id" value="{ORDER_ID}" />
        
        <div class="form-group">
            <label class="col-sm-3 control-label">{PHP.L.mstoremailorder_order_id}:</label>
            <div class="col-sm-9">
                <p class="form-control-static">{ORDER_ID}</p>
			</div>
		</div>
		
        <div class="form-group">
            <label class="col-sm-3 control-label">{PHP.L.mstoremailorder_item_title}:</label>
            <div class="col-sm-9">
                <p class="form-control-static">{ORDER_ITEM_TITLE}</p>
			</div>
		</div>
		
        <div class="form-group">
            <label class="col-sm-3 control-label">{PHP.L.mstoremailorder_complaint_text}:</label>
            <div class="col-sm-9">
                <textarea name="complaint_text" class="form-control" rows="6" required>{COMPLAINT_TEXT}</textarea>
			</div>
		</div>
		
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-9">
                <button type="submit" name="submit" value="submit" class="btn btn-primary">{PHP.L.Submit}</button>
                <a href="{PHP|cot_url('plug', 'e=mstoremailorder&m=outgoing')}" class="btn btn-default">{PHP.L.Cancel}</a>
			</div>
		</div>
	</form>
</div>
<!-- END: MAIN -->