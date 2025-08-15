<?php
/**
 * [BEGIN_COT_EXT]
 * Hooks=mstore.list.loop
 * Tags=mstore.list.tpl:{LIST_ROW_FILTER_PARAMS_HTML}
 * [END_COT_EXT]
 */

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('mstore', 'module');
require_once cot_incfile('mstorefilter', 'plug');

global $L;

$filter_params_html = '';
if (!empty($item['msitem_id'])) {
    global $db, $db_x;
    $db_mstorefilter_params = $db_x . 'mstorefilter_params';
    $db_mstorefilter_params_values = $db_x . 'mstorefilter_params_values';

    // Получаем активные параметры, их типы и заголовки
    $params = $db->query("SELECT param_id, param_name, param_title, param_type FROM $db_mstorefilter_params WHERE param_active = 1")->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($params)) {
        // Получаем значения параметров для текущего товара
        $saved_values = $db->query("SELECT param_id, param_value FROM $db_mstorefilter_params_values WHERE msitem_id = ?", [(int)$item['msitem_id']])->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_COLUMN);
        $saved_values = array_map(function($values) { return (array)$values; }, $saved_values);

        $filter_params_html .= '<div class="mstore-filter-params">';
        foreach ($params as $param) {
            $param_id = (int)$param['param_id'];
            $param_name = $param['param_name'];
            $param_type = $param['param_type'];
            $param_values = $saved_values[$param_id] ?? [];
            $param_title = isset($L["mstorefilter_param_{$param_name}"]) ? $L["mstorefilter_param_{$param_name}"] : htmlspecialchars($param['param_title']);

            $formatted_value = mstorefilter_format_param_value($param_type, $param_values, $param_name);
            if ($formatted_value !== '') {
                $filter_params_html .= '
                    <div class="row mb-1">
                        <div class="col-5 fw-bold">' . $param_title . '</div>
                        <div class="col-7">' . htmlspecialchars($formatted_value) . '</div>
                    </div>';
            }
        }
        $filter_params_html .= '</div>';
    }
}
$t->assign(['LIST_ROW_FILTER_PARAMS_HTML' => $filter_params_html], 'LIST_ROW_');
?>