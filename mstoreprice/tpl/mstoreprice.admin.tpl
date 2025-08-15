<!-- BEGIN: MAIN -->
<div class="container-fluid">
    <h2>{PHP.L.mstoreprice}</h2>

    <!-- Вывод сообщений об успехе или ошибках -->
    {FILE "{PHP.cfg.themes_dir}/admin/{PHP.cfg.admintheme}/warnings.tpl"}

    <!-- Список валют -->
    <h3>{PHP.L.mstoreprice_currencies}</h3>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>{PHP.L.mstoreprice_code}</th>
                <th>{PHP.L.mstoreprice_title}</th>
                <th>{PHP.L.mstoreprice_edit}</th>
                <th>{PHP.L.mstoreprice_delete}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: CURRENCY_ROW -->
            <tr>
                <td>{CURRENCY_CODE}</td>
                <td>{CURRENCY_TITLE}</td>
                <td><a href="{CURRENCY_EDIT_URL}" class="btn btn-sm btn-primary">{PHP.L.mstoreprice_edit}</a></td>
                <td><a href="{CURRENCY_DELETE_URL}" class="btn btn-sm btn-danger" onclick="return confirm('{PHP.L.mstoreprice_confirm_delete}')">{PHP.L.mstoreprice_delete}</a></td>
            </tr>
            <!-- END: CURRENCY_ROW -->
            <tr>
                <td colspan="4"><a href="{CURRENCY_FORM_ACTION}" class="btn btn-sm btn-success">{PHP.L.mstoreprice_add_currency}</a></td>
            </tr>
        </tbody>
    </table>

    <!-- Список типов цен -->
    <h3>{PHP.L.mstoreprice_price_types}</h3>
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>{PHP.L.mstoreprice_code}</th>
                <th>{PHP.L.mstoreprice_title}</th>
                <th>{PHP.L.mstoreprice_edit}</th>
                <th>{PHP.L.mstoreprice_delete}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: PRICE_TYPE_ROW -->
            <tr>
                <td>{PRICE_TYPE_CODE}</td>
                <td>{PRICE_TYPE_TITLE}</td>
                <td><a href="{PRICE_TYPE_EDIT_URL}" class="btn btn-sm btn-primary">{PHP.L.mstoreprice_edit}</a></td>
                <td><a href="{PRICE_TYPE_DELETE_URL}" class="btn btn-sm btn-danger" onclick="return confirm('{PHP.L.mstoreprice_confirm_delete}')">{PHP.L.mstoreprice_delete}</a></td>
            </tr>
            <!-- END: PRICE_TYPE_ROW -->
            <tr>
                <td colspan="4"><a href="{PRICE_TYPE_FORM_ACTION}" class="btn btn-sm btn-success">{PHP.L.mstoreprice_add_price_type}</a></td>
            </tr>
        </tbody>
    </table>

    <!-- Форма добавления валюты -->
    <!-- BEGIN: CURRENCY_ADD_FORM -->
    <div class="p-3 mb-4 bg-primary-subtle text-primary-emphasis">
        <h3>{PHP.L.mstoreprice_add_currency}</h3>
        <form action="{CURRENCY_FORM_ACTION}" method="post">
            <div class="mb-2">
                <label>{PHP.L.mstoreprice_code}</label>
                <input type="text" name="code" value="{CURRENCY_FORM_CODE}" maxlength="10" class="form-control" />
            </div>
            <div class="mb-2">
                <label>{PHP.L.mstoreprice_title}</label>
                <input type="text" name="title" value="{CURRENCY_FORM_TITLE}" maxlength="50" class="form-control" />
            </div>
            <button type="submit" class="btn btn-success">{PHP.L.mstoreprice_save}</button>
            <a href="{CURRENCY_CANCEL_URL}" class="btn btn-secondary">{PHP.L.mstoreprice_cancel}</a>
        </form>
    </div>
    <!-- END: CURRENCY_ADD_FORM -->

    <!-- Форма добавления типа цены -->
    <!-- BEGIN: PRICE_TYPE_ADD_FORM -->
    <div class="p-3 mb-4 bg-primary-subtle text-primary-emphasis">
        <h3>{PHP.L.mstoreprice_add_price_type}</h3>
        <form action="{PRICE_TYPE_FORM_ACTION}" method="post">
            <div class="mb-2">
                <label>{PHP.L.mstoreprice_code}</label>
                <input type="text" name="code" value="{PRICE_TYPE_FORM_CODE}" maxlength="20" class="form-control" />
            </div>
            <div class="mb-2">
                <label>{PHP.L.mstoreprice_title}</label>
                <input type="text" name="title" value="{PRICE_TYPE_FORM_TITLE}" maxlength="50" class="form-control" />
            </div>
            <button type="submit" class="btn btn-success">{PHP.L.mstoreprice_save}</button>
            <a href="{PRICE_TYPE_CANCEL_URL}" class="btn btn-secondary">{PHP.L.mstoreprice_cancel}</a>
        </form>
    </div>
    <!-- END: PRICE_TYPE_ADD_FORM -->

    <!-- Форма редактирования валюты -->
    <!-- BEGIN: CURRENCY_EDIT_FORM -->
    <div class="p-3 mb-4 bg-warning-subtle text-warning-emphasis">
        <h3>{PHP.L.mstoreprice_edit} {PHP.L.mstoreprice_currency}</h3>
        <form action="{CURRENCY_FORM_ACTION}" method="post">
            <input type="hidden" name="id" value="{CURRENCY_FORM_ID}" />
            <div class="mb-2">
                <label>{PHP.L.mstoreprice_code}</label>
                <input type="text" name="code" value="{CURRENCY_FORM_CODE}" maxlength="10" class="form-control" />
            </div>
            <div class="mb-2">
                <label>{PHP.L.mstoreprice_title}</label>
                <input type="text" name="title" value="{CURRENCY_FORM_TITLE}" maxlength="50" class="form-control" />
            </div>
            <button type="submit" class="btn btn-primary">{PHP.L.mstoreprice_save}</button>
            <a href="{CURRENCY_CANCEL_URL}" class="btn btn-secondary">{PHP.L.mstoreprice_cancel}</a>
        </form>
    </div>
    <!-- END: CURRENCY_EDIT_FORM -->

    <!-- Форма редактирования типа цены -->
    <!-- BEGIN: PRICE_TYPE_EDIT_FORM -->
    <div class="p-3 mb-4 bg-warning-subtle text-warning-emphasis">
        <h3>{PHP.L.mstoreprice_edit} {PHP.L.mstoreprice_price_type}</h3>
        <form action="{PRICE_TYPE_FORM_ACTION}" method="post">
            <input type="hidden" name="id" value="{PRICE_TYPE_FORM_ID}" />
            <div class="mb-2">
                <label>{PHP.L.mstoreprice_code}</label>
                <input type="text" name="code" value="{PRICE_TYPE_FORM_CODE}" maxlength="20" class="form-control" />
            </div>
            <div class="mb-2">
                <label>{PHP.L.mstoreprice_title}</label>
                <input type="text" name="title" value="{PRICE_TYPE_FORM_TITLE}" maxlength="50" class="form-control" />
            </div>
            <button type="submit" class="btn btn-primary">{PHP.L.mstoreprice_save}</button>
            <a href="{PRICE_TYPE_CANCEL_URL}" class="btn btn-secondary">{PHP.L.mstoreprice_cancel}</a>
        </form>
    </div>
    <!-- END: PRICE_TYPE_EDIT_FORM -->
</div>
<!-- END: MAIN -->