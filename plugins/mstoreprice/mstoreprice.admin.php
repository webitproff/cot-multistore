<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=tools
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Неверный URL');

require_once cot_incfile('mstore', 'module');
require_once cot_incfile('mstoreprice', 'plug');
require_once cot_incfile('forms');

global $db, $db_x, $L;
cot_block(Cot::$usr['isadmin']);
$adminHelp = Cot::$L['mstoreprice'];
$db_mstore_currency = $db_x . 'mstore_currency';
$db_mstore_price_types = $db_x . 'mstore_price_types';

$action = cot_import('a', 'G', 'ALP');
$id = cot_import('id', 'G', 'INT');

$t = new XTemplate(cot_tplfile('mstoreprice.admin', 'plug'));

// Форма для валют
$form_currency_values = [
    'id' => '',
    'code' => '',
    'title' => '',
];

// Форма для типов цен
$form_price_type_values = [
    'id' => '',
    'code' => '',
    'title' => '',
];

// Импорт id как можно раньше
$id = cot_import('id', 'G', 'INT');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = cot_import('id', 'P', 'INT');
}

// Обработка формы добавления/редактирования валюты
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['currency_add', 'currency_edit'])) {
    $code = cot_import('code', 'P', 'TXT');
    $title = cot_import('title', 'P', 'TXT');

    $validation = mstoreprice_validate_currency($code, $title, $action === 'currency_edit' ? $id : null);
    if ($validation === true) {
        $data = [
            'code' => $code,
            'title' => $title,
        ];

        if ($action === 'currency_add') {
            $db->insert($db_mstore_currency, $data);
            cot_message($L['mstoreprice_currency_added']);
        } elseif ($action === 'currency_edit' && $id > 0) {
            $db->update($db_mstore_currency, $data, 'id = ?', [$id]);
            cot_message($L['mstoreprice_currency_updated']);
        }

        cot_redirect(cot_url('admin', 'm=other&p=mstoreprice', '', true));
        exit;
    } else {
        cot_error($L[$validation]);
    }
}

// Обработка формы добавления/редактирования типа цены
if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($action, ['price_type_add', 'price_type_edit'])) {
    $code = cot_import('code', 'P', 'TXT');
    $title = cot_import('title', 'P', 'TXT');

    $validation = mstoreprice_validate_price_type($code, $title, $action === 'price_type_edit' ? $id : null);
    if ($validation === true) {
        $data = [
            'code' => $code,
            'title' => $title,
        ];

        if ($action === 'price_type_add') {
            $db->insert($db_mstore_price_types, $data);
            cot_message($L['mstoreprice_price_type_added']);
        } elseif ($action === 'price_type_edit' && $id > 0) {
            $db->update($db_mstore_price_types, $data, 'id = ?', [$id]);
            cot_message('mstoreprice_price_type_updated');
        }

        cot_redirect(cot_url('admin', 'm=other&p=mstoreprice', '', true));
        exit;
    } else {
        cot_error($L[$validation]);
    }
}

// Обработка удаления валюты
if ($action === 'currency_delete' && $id > 0) {
    $db->delete($db_mstore_currency, 'id = ?', [$id]);
    cot_message($L['mstoreprice_currency_deleted']);
    cot_redirect(cot_url('admin', 'm=other&p=mstoreprice', '', true));
    exit;
}

// Обработка удаления типа цены
if ($action === 'price_type_delete' && $id > 0) {
    $db->delete($db_mstore_price_types, 'id = ?', [$id]);
    cot_message($L['mstoreprice_price_type_deleted']);
    cot_redirect(cot_url('admin', 'm=other&p=mstoreprice', '', true));
    exit;
}

// Получаем данные для редактирования валюты
if ($action === 'currency_edit' && $id > 0) {
    $row = $db->query("SELECT * FROM $db_mstore_currency WHERE id = ?", [$id])->fetch();
    if ($row) {
        $form_currency_values = $row;
    }
}

// Получаем данные для редактирования типа цены
if ($action === 'price_type_edit' && $id > 0) {
    $row = $db->query("SELECT * FROM $db_mstore_price_types WHERE id = ?", [$id])->fetch();
    if ($row) {
        $form_price_type_values = $row;
    }
}

// Получаем списки валют и типов цен
$currencies = mstoreprice_get_currencies();
$price_types = mstoreprice_get_price_types();

// Присваиваем переменные для списка валют
foreach ($currencies as $currency) {
    $t->assign([
        'CURRENCY_ID' => $currency['id'],
        'CURRENCY_CODE' => htmlspecialchars($currency['code']),
        'CURRENCY_TITLE' => htmlspecialchars($currency['title']),
        'CURRENCY_EDIT_URL' => cot_url('admin', 'm=other&p=mstoreprice&a=currency_edit&id=' . $currency['id']),
        'CURRENCY_DELETE_URL' => cot_confirm_url(cot_url('admin', 'm=other&p=mstoreprice&a=currency_delete&id=' . $currency['id'])),
    ]);
    $t->parse('MAIN.CURRENCY_ROW');
}

// Присваиваем переменные для списка типов цен
foreach ($price_types as $price_type) {
    $t->assign([
        'PRICE_TYPE_ID' => $price_type['id'],
        'PRICE_TYPE_CODE' => htmlspecialchars($price_type['code']),
        'PRICE_TYPE_TITLE' => htmlspecialchars($price_type['title']),
        'PRICE_TYPE_EDIT_URL' => cot_url('admin', 'm=other&p=mstoreprice&a=price_type_edit&id=' . $price_type['id']),
        'PRICE_TYPE_DELETE_URL' => cot_confirm_url(cot_url('admin', 'm=other&p=mstoreprice&a=price_type_delete&id=' . $price_type['id'])),
    ]);
    $t->parse('MAIN.PRICE_TYPE_ROW');
}

// Присваиваем переменные для форм
$t->assign([
    'CURRENCY_FORM_ACTION' => cot_url('admin', 'm=other&p=mstoreprice&a=currency_' . ($action === 'currency_edit' ? 'edit' : 'add')),
    'CURRENCY_FORM_ID' => htmlspecialchars($form_currency_values['id']),
    'CURRENCY_FORM_CODE' => htmlspecialchars($form_currency_values['code']),
    'CURRENCY_FORM_TITLE' => htmlspecialchars($form_currency_values['title']),
    'CURRENCY_CANCEL_URL' => cot_url('admin', 'm=other&p=mstoreprice'),
    'PRICE_TYPE_FORM_ACTION' => cot_url('admin', 'm=other&p=mstoreprice&a=price_type_' . ($action === 'price_type_edit' ? 'edit' : 'add')),
    'PRICE_TYPE_FORM_ID' => htmlspecialchars($form_price_type_values['id']),
    'PRICE_TYPE_FORM_CODE' => htmlspecialchars($form_price_type_values['code']),
    'PRICE_TYPE_FORM_TITLE' => htmlspecialchars($form_price_type_values['title']),
    'PRICE_TYPE_CANCEL_URL' => cot_url('admin', 'm=other&p=mstoreprice'),
]);

// Управление отображением форм
if ($action === 'currency_add') {
    $t->parse('MAIN.CURRENCY_ADD_FORM');
} elseif ($action === 'currency_edit') {
    $t->parse('MAIN.CURRENCY_EDIT_FORM');
} elseif ($action === 'price_type_add') {
    $t->parse('MAIN.PRICE_TYPE_ADD_FORM');
} elseif ($action === 'price_type_edit') {
    $t->parse('MAIN.PRICE_TYPE_EDIT_FORM');
} else {
    $t->parse('MAIN.CURRENCY_ADD_FORM');
    $t->parse('MAIN.PRICE_TYPE_ADD_FORM');
}
cot_display_messages($t);
$t->parse('MAIN');
$adminMain = $t->text('MAIN');
?>