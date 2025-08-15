<?php
defined('COT_CODE') or die('Wrong URL');
// Подключаем файл локализации
require_once cot_langfile('mstoreuserproducts', 'plug');
// Здесь будут функции 

/**
 * Получение пользователей с количеством их товаров, с возможной фильтрацией по имени пользователя
 *
 * @param string|null $search Поисковый запрос для имени пользователя (опционально)
 * @param int $offset Смещение для пагинации
 * @param int $limit Количество пользователей на страницу
 * @return array [total_users, users]
 */
function mstoreuserproducts_get_users($search = null, $offset = 0, $limit = 20)
{
    global $db, $db_users, $db_mstore;

    $where = ' WHERE u.user_maingrp = 4'; // Ограничиваем выборку пользователями с user_maingrp = 4
    $params = [];
    if (!empty($search)) {
        $where .= " AND u.user_name LIKE ?";
        $params[] = '%' . $db->prep($search) . '%';
    }

    // Подсчёт общего количества пользователей с user_maingrp = 4
    $total_users = $db->query("
        SELECT COUNT(DISTINCT u.user_id)
        FROM $db_users AS u
        LEFT JOIN $db_mstore AS p ON p.msitem_ownerid = u.user_id AND p.msitem_state = 0
        $where
    ", $params)->fetchColumn();

    // Получение списка пользователей с user_maingrp = 4
    $params = array_merge($params, [$offset, $limit]);
    $sql = $db->query("
        SELECT u.user_id, u.user_name, COUNT(p.msitem_id) AS article_count
        FROM $db_users AS u
        LEFT JOIN $db_mstore AS p ON p.msitem_ownerid = u.user_id AND p.msitem_state = 0
        $where
        GROUP BY u.user_id, u.user_name
        ORDER BY article_count DESC
        LIMIT ?, ?
    ", $params);

    $users = $sql->fetchAll();

    return [$total_users, $users];
}