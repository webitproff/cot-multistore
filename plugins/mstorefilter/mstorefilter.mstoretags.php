<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=mstore.list.tags
 * Tags=mstore.list.tpl:{MSTORE_FILTER_FORM}
 * [END_COT_EXT]
 */
defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('mstore', 'module');
require_once cot_incfile('mstorefilter', 'plug', 'functions');

global $db, $db_x, $t, $items, $structure, $c, $L;

mstorefilter_log("mstorefilter.mstoretags.php started");

$db_mstorefilter_params = $db_x . 'mstorefilter_params';
$db_mstorefilter_params_values = $db_x . 'mstorefilter_params_values';

$t1 = new XTemplate(cot_tplfile(['mstorefilter', 'filterform'], 'plug'));
mstorefilter_log("XTemplate loaded for filterform.tpl");

$filter_params = $db->query("SELECT * FROM $db_mstorefilter_params WHERE param_active = 1 ORDER BY param_name ASC")->fetchAll();
mstorefilter_log("Loaded " . count($filter_params) . " filter parameters");

if (empty($filter_params)) {
    mstorefilter_log("Warning: No active filter parameters found");
    $t1->assign('FILTER_ERROR', 'Нет активных параметров фильтра');
    $t1->parse('FILTER_FORM.ERROR');
}

foreach ($filter_params as $param) {
    $param_name = $param['param_name'];
    $param_title = $param['param_title'];
    $param_type = $param['param_type'];
    $param_values = json_decode($param['param_values'], true, 512, JSON_UNESCAPED_UNICODE);

    if (empty($param_values)) {
        mstorefilter_log("Warning: param_values empty for $param_name");
        $t1->assign('FILTER_PARAM_ERROR', "Ошибка: пустые значения для $param_name");
        $t1->parse('FILTER_FORM.FILTER_PARAM.ERROR');
        continue;
    }

    mstorefilter_log("Processing param: $param_name, type: $param_type, values: " . implode(', ', $param_values));

    $input = cot_import("filter_$param_name", 'G', $param_type === 'range' ? 'TXT' : ($param_type === 'checkbox' ? 'ARR' : 'TXT'));
    if (!$input && isset($_GET["filter_$param_name"])) {
        $input = $_GET["filter_$param_name"];
    }

    $t1->assign([
        'FILTER_PARAM_NAME' => htmlspecialchars($param_name, ENT_QUOTES, 'UTF-8'),
        'FILTER_PARAM_TITLE' => htmlspecialchars($param_title, ENT_QUOTES, 'UTF-8'),
        'FILTER_PARAM_TYPE' => $param_type,
        'FILTER_PARAM_VALUES_DEBUG' => implode(', ', $param_values),
    ]);

    if ($param_type === 'range') {
        $value = $input ? floatval($input) : 0;
        $t1->assign([
            'FILTER_PARAM_VALUE_MAX' => $value,
            'FILTER_PARAM_MIN' => $param_values['min'],
            'FILTER_PARAM_MAX' => $param_values['max'],
        ]);
        $t1->parse('FILTER_FORM.FILTER_PARAM.RANGE');
        mstorefilter_log("Parsed range param: $param_name (value: $value)");
    } elseif (in_array($param_type, ['select', 'checkbox', 'radio'])) {
        $t1->reset("FILTER_FORM.FILTER_PARAM." . strtoupper($param_type) . "_LIST");
        foreach ($param_values as $value) {
            $is_selected = $input && ($param_type === 'checkbox' ? in_array($value, (array)$input, true) : ((string)$value === (string)$input));
            $t1->assign([
                'FILTER_PARAM_OPTION_VALUE' => htmlspecialchars($value, ENT_QUOTES, 'UTF-8'),
                'FILTER_PARAM_OPTION_SELECTED' => $is_selected && $param_type === 'select' ? 'selected' : '',
                'FILTER_PARAM_CHECKED' => $is_selected && in_array($param_type, ['checkbox', 'radio']) ? 'checked' : '',
            ]);
            $t1->parse("FILTER_FORM.FILTER_PARAM." . strtoupper($param_type) . "_LIST");
        }
        $t1->parse("FILTER_FORM.FILTER_PARAM." . strtoupper($param_type));
        mstorefilter_log("Parsed $param_type param: $param_name, options: " . implode(', ', $param_values));
    }

    $t1->parse('FILTER_FORM.FILTER_PARAM');
}

$params = [];
if (isset($c) && is_string($c) && trim($c) !== '') {
    $params['c'] = $c;
}

$t1->assign([
    'SEARCH_ACTION_URL' => cot_url('mstore', ['c' => $c], '', true),
    'FILTER_RESET_URL' => cot_url('mstore', ['c' => $c], '', true), // URL для сброса фильтров с сохранением категории
]);

foreach (cot_getextplugins('mstorefilter.list.tags') as $pl) {
    include $pl;
}

$t1->parse('FILTER_FORM');
$form_content = $t1->text('FILTER_FORM');
mstorefilter_log("Filter form parsed, content length: " . strlen($form_content));

// Передаем форму фильтра и сообщение о результатах фильтрации в шаблон
$t->assign([
    'MSTORE_FILTER_FORM' => $form_content,
    'MSTOREFILTER_MESSAGE' => $L['mstorefilter_message'],
    'MSTOREFILTER_MESSAGE_CLASS' => $L['mstorefilter_message_class'],
]);
?>

