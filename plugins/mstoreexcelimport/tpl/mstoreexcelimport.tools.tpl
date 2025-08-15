<!-- BEGIN: MAIN -->
<div class="block">
    <h2>{PHP.L.mstoreexcelimport_title}</h2>
    {FILE "{PHP.cfg.system_dir}/admin/tpl/warnings.tpl"}
    
    <!-- Форма загрузки файла -->
    <!-- BEGIN: UPLOAD -->
    <p>Форма загрузки</p>
    <form id="uploadForm" action="{EXCELIMPORT_FORM_ACTION}" method="post" enctype="{EXCELIMPORT_FORM_ENCTYPE}">
        <table class="cells">
            <tr>
                <td>{PHP.L.mstoreexcelimport_select_file}:</td>
                <td><input type="file" name="excel_file" accept=".xlsx,.csv" required /></td>
            </tr>
            <tr>
                <td>{PHP.L.mstoreexcelimport_max_rows_label}:</td>
                <td>{EXCELIMPORT_MAX_ROWS}</td>
            </tr>
            <tr>
                <td>{PHP.L.mstoreexcelimport_allowed_formats_label}:</td>
                <td>{EXCELIMPORT_ALLOWED_FORMATS}</td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit">{PHP.L.mstoreexcelimport_upload}</button>
                </td>
            </tr>
        </table>
    </form>
    <!-- END: UPLOAD -->

    <!-- Форма маппинга полей -->
    <!-- BEGIN: MAPPING -->
    <p>Форма маппинга</p>
    <p>{PHP.L.mstoreexcelimport_headers}: {EXCELIMPORT_HEADERS}</p>
    <form id="importForm" action="{EXCELIMPORT_MAPPING_ACTION}" method="post">
        <table class="cells">
            <tr>
                <th>{PHP.L.mstoreexcelimport_field_table}</th>
                <th>{PHP.L.mstoreexcelimport_field_excel}</th>
            </tr>
            <!-- BEGIN: FIELDS -->
            <tr>
                <td>{FIELD_LABEL}</td>
                <td>{FIELD_INPUT}</td>
            </tr>
            <!-- END: FIELDS -->
            <tr>
                <td colspan="2">
                    <button type="submit" id="startImport">{PHP.L.mstoreexcelimport_import}</button>
                    <a href="{EXCELIMPORT_RESET_URL}" class="button">{PHP.L.mstoreexcelimport_reset}</a>
                </td>
            </tr>
        </table>
    </form>
    <!-- END: MAPPING -->

    {MESSAGES}
</div>
<!-- END: MAIN -->