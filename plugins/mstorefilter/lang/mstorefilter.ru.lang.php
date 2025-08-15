<?php
/**
 * Russian Language File for the MstoreFilter Plugin
 *
 * @package MstoreFilter
 * @copyright (c) webitproff
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL');

$L['mstorefilter_admin_title'] = 'Управление параметрами фильтрации';
$L['mstorefilter_param_name'] = 'Код параметра';
$L['mstorefilter_add_param'] = 'Добавление параметра';
$L['mstorefilter_edit_param'] = 'Редактирование параметра';
$L['mstorefilter_param_name_hint'] = 'Уникальный код (например, power, battery_capacity)';
$L['mstorefilter_param_title'] = 'Название параметра';
$L['mstorefilter_param_type'] = 'Тип параметра';
$L['mstorefilter_param_values'] = 'Значения параметра (JSON)';
$L['mstorefilter_param_values_hint'] = 'Для диапазона: {"min":0,"max":100}; для списка/чекбоксов: ["значение1","значение2"]';
$L['mstorefilter_param_active'] = 'Активен';
$L['mstorefilter_existing_params'] = 'Существующие параметры';
$L['mstorefilter_id'] = 'ID';
$L['mstorefilter_actions'] = 'Действия';
$L['mstorefilter_confirm_delete'] = 'Вы уверены, что хотите удалить этот параметр?';
$L['mstorefilter_range'] = 'Диапазон';
$L['mstorefilter_radio'] = 'Радиокнопки';
$L['mstorefilter_select'] = 'Выпадающий список';
$L['mstorefilter_checkbox'] = 'Чекбоксы';
$L['mstorefilter_from'] = 'От';
$L['mstorefilter_to'] = 'До';
$L['mstorefilter_reset'] = 'Сбросить фильтры';
$L['mstorefilter_apply'] = 'Применить фильтры';
$L['mstorefilter_price'] = 'Диапазон цен';
$L['mstorefilter_cats'] = 'Категории';
$L['mstorefilter_sort'] = 'Сортировать по';
$L['mstorefilter_examples'] = 'Примеры';
$L['mstore_mostrelevant'] = 'Наиболее релевантные';
$L['mstore_costasc'] = 'Цена: по возрастанию';
$L['mstore_costdesc'] = 'Цена: по убыванию';
$L['mstorefilter_paramsItem'] = 'Характеристики и свойства';
$L['mstore_help'] = 'Подробная инструкция по заполнению полей параметров фильтра:
<ul>
<li><b>Код параметра</b> — уникальный системный идентификатор параметра. Используйте только латинские буквы, цифры и символ подчёркивания без пробелов. Например: <i>power</i>, <i>battery_capacity</i>. Этот код будет использоваться в базе данных и в коде, поэтому не должно быть дубликатов.</li>
<li><b>Название параметра</b> — читаемое название, которое увидят пользователи в интерфейсе сайта. Например: <i>Мощность</i>, <i>Ёмкость батареи</i>.</li>
<li><b>Тип параметра</b> — выберите тип значения:
    <ul>
        <li><i>Диапазон</i> — для числовых параметров с минимальным и максимальным значением, например, цена, вес;</li>
        <li><i>Выпадающий список</i> — для выбора одного варианта из списка фиксированных значений;</li>
        <li><i>Чекбоксы</i> — для выбора одного или нескольких вариантов из списка.</li>
    </ul>
</li>
<li><b>Значения параметра (JSON)</b> — в этом поле указываются допустимые значения параметра в формате JSON:
    <ul>
        <li>Для типа <i>Диапазон</i> необходимо указать объект с двумя свойствами <code>min</code> и <code>max</code>, например: <code>{"min":0,"max":100}</code>. Значения должны быть числами, <code>min</code> меньше или равно <code>max</code>.</li>
        <li>Для типов <i>Выпадающий список</i> и <i>Чекбоксы</i> укажите массив строк с возможными вариантами, например: <code>["Красный","Зелёный","Синий"]</code>. Каждый элемент массива — отдельное значение.</li>
    </ul>
    <p><b>Важно:</b> JSON должен быть строго корректным:
        <ul>
            <li>Используйте двойные кавычки для ключей и строковых значений;</li>
            <li>Не ставьте лишние запятые после последнего элемента;</li>
            <li>Структура должна точно соответствовать примерам выше.</li>
        </ul>
        Для проверки корректности JSON используйте онлайн-валидаторы, например <a href="https://jsonlint.com" target="_blank" rel="noopener noreferrer">jsonlint.com</a>. Некорректный JSON приведёт к ошибкам при сохранении или работе фильтра.</p>
</li>
<li><b>Активен</b> — переключатель, включающий или отключающий параметр фильтра на сайте. Если параметр неактивен, он не будет отображаться пользователям.</li>
</ul>';
$L['mstorefilter_param_battery_capacity'] = 'Ёмкость АКБ';
$L['mstorefilter_param_rama'] = 'Тип рамы';
$L['mstorefilter_param_power_motor'] = 'Мощность электропривода';
$L['mstorefilter_param_color'] = 'Цвет';
$L['mstorefilter_param_wheel_size'] = 'Размер колёс';

?>
