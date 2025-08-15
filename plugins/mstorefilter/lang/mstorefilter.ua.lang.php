<?php
/**
 * Ukrainian Language File for the MstoreFilter Plugin
 *
 * @package MstoreFilter
 * @author webitproff
 * @license BSD
 */
defined('COT_CODE') or die('Wrong URL');

$L['mstorefilter_admin_title'] = 'Управління параметрами фільтрації';
$L['mstorefilter_param_name'] = 'Код параметра';
$L['mstorefilter_add_param'] = 'Додати параметр';
$L['mstorefilter_edit_param'] = 'Редагування параметра';
$L['mstorefilter_param_name_hint'] = 'Унікальний код (наприклад, power, battery_capacity)';
$L['mstorefilter_param_title'] = 'Назва параметра';
$L['mstorefilter_param_type'] = 'Тип параметра';
$L['mstorefilter_param_values'] = 'Значення параметра (JSON)';
$L['mstorefilter_param_values_hint'] = 'Для діапазону: {"min":0,"max":100}; для списку/чекбоксів: ["значення1","значення2"]';
$L['mstorefilter_param_active'] = 'Активний';
$L['mstorefilter_existing_params'] = 'Існуючі параметри';
$L['mstorefilter_id'] = 'ID';
$L['mstorefilter_actions'] = 'Дії';
$L['mstorefilter_confirm_delete'] = 'Ви впевнені, що хочете видалити цей параметр?';
$L['mstorefilter_range'] = 'Діапазон';
$L['mstorefilter_radio'] = 'Радіокнопки';
$L['mstorefilter_select'] = 'Випадаючий список';
$L['mstorefilter_checkbox'] = 'Чекбокси';
$L['mstorefilter_from'] = 'Від';
$L['mstorefilter_to'] = 'До';
$L['mstorefilter_reset'] = 'Скинути фільтри';
$L['mstorefilter_apply'] = 'Застосувати фільтри';
$L['mstorefilter_price'] = 'Діапазон цін';
$L['mstorefilter_cats'] = 'Категорії';
$L['mstorefilter_sort'] = 'Сортувати за';
$L['mstorefilter_examples'] = 'Приклади';
$L['mstore_mostrelevant'] = 'Найбільш релевантні';
$L['mstore_costasc'] = 'Ціна: за зростанням';
$L['mstore_costdesc'] = 'Ціна: за спаданням';
$L['mstorefilter_paramsItem'] = 'Характеристики та властивості';
$L['mstore_help'] = 'Детальна інструкція щодо заповнення полів параметрів фільтра:
<ul>
<li><b>Код параметра</b> — унікальний системний ідентифікатор. Використовуйте лише латинські літери, цифри та підкреслення без пробілів. Наприклад: <i>power</i>, <i>battery_capacity</i>. Код використовується в базі даних та коді, не має дублюватися.</li>
<li><b>Назва параметра</b> — зрозуміла назва, яку побачать користувачі. Наприклад: <i>Потужність</i>, <i>Ємність батареї</i>.</li>
<li><b>Тип параметра</b> — оберіть тип значення:
    <ul>
        <li><i>Діапазон</i> — для числових параметрів із мінімальним та максимальним значенням, наприклад, ціна, вага;</li>
        <li><i>Випадаючий список</i> — для вибору одного варіанта зі списку;</li>
        <li><i>Чекбокси</i> — для вибору одного або декількох варіантів зі списку.</li>
    </ul>
</li>
<li><b>Значення параметра (JSON)</b> — допустимі значення у форматі JSON:
    <ul>
        <li>Для типу <i>Діапазон</i> — об’єкт з властивостями <code>min</code> та <code>max</code>, наприклад: <code>{"min":0,"max":100}</code>. Значення мають бути числами, <code>min</code> ≤ <code>max</code>.</li>
        <li>Для типів <i>Випадаючий список</i> і <i>Чекбокси</i> — масив рядків, наприклад: <code>["Червоний","Зелений","Синій"]</code>. Кожен елемент — окреме значення.</li>
    </ul>
    <p><b>Важливо:</b> JSON має бути строго валідним:
        <ul>
            <li>Використовуйте подвійні лапки для ключів та значень;</li>
            <li>Не ставте кому після останнього елемента;</li>
            <li>Структура має точно відповідати прикладам.</li>
        </ul>
        Перевіряйте валідність JSON через онлайн-інструменти, наприклад <a href="https://jsonlint.com" target="_blank" rel="noopener noreferrer">jsonlint.com</a>. Невалідний JSON спричинить помилки при збереженні або роботі фільтра.</p>
</li>
<li><b>Активний</b> — перемикач, який вмикає або вимикає параметр на сайті. Неактивні параметри не відображатимуться користувачам.</li>
</ul>';

?>
