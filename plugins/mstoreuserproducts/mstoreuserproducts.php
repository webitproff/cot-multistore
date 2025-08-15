<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=standalone
[END_COT_EXT]
==================== */

defined('COT_CODE') or die('Wrong URL');

// Подключаем файлы
require_once cot_langfile('mstoreuserproducts', 'plug');
require_once cot_incfile('mstoreuserproducts', 'plug', 'functions');
require_once cot_incfile('forms'); // Подключаем forms.php для cot_selectbox

// Регистрируем таблицы
global $db_mstore, $db_users, $db_structure;
cot::$db->registerTable('mstore');
cot::$db->registerTable('users');
cot::$db->registerTable('structure');

// Определяем параметры
$action = cot_import('action', 'G', 'ALP', 16);
$user_id = cot_import('uid', 'G', 'INT');
$page = cot_import('d', 'G', 'INT') ?: 1;
$search = cot_import('search', 'G', 'TXT');
$cat = cot_import('cat', 'G', 'TXT'); // Новый параметр для категории

// Получаем настройки из конфига
$max_users = (int) Cot::$cfg['plugin']['mstoreuserproducts']['mstore_max_rows_per_users'] ?: 20;
$max_articles = (int) Cot::$cfg['plugin']['mstoreuserproducts']['mstore_max_rows_per_pages'] ?: 25;

// Основной список пользователей
if (empty($action) || $action == 'list') {
    Cot::$out['subtitle'] = Cot::$L['mstoreuserproducts_list_title'];

    $t = new XTemplate(cot_tplfile('mstoreuserproducts', 'plug'));
    $t->assign('SEARCH', htmlspecialchars($search ?? ''));

    $offset = ($page - 1) * $max_users;
    list($total_users, $users) = mstoreuserproducts_get_users($search, $offset, $max_users);

    if (count($users) > 0) {
        foreach ($users as $row) {
            $t->assign([
                'USER_ID' => $row['user_id'],
                'USER_NAME' => htmlspecialchars($row['user_name']),
                'USER_ARTICLE_COUNT' => $row['article_count'],
                'USER_URL' => cot_url('plug', 'e=mstoreuserproducts&action=details&uid=' . $row['user_id'])
            ]);
            $t->parse('MAIN.USER_LIST.USER');
        }
        $t->parse('MAIN.USER_LIST');
    } else {
        $t->parse('MAIN.NO_USERS');
    }

    $pagination_params = ['e' => 'mstoreuserproducts'];
    if (!empty($search)) {
        $pagination_params['search'] = $search;
    }
    $pagination = cot_pagenav('plug', $pagination_params, $offset, $total_users, $max_users, 'd');
    $t->assign(cot_generatePaginationTags($pagination));
}

// Детальный список товаров пользователя
elseif ($action == 'details' && $user_id > 0) {
    $user = cot::$db->query("SELECT user_name FROM $db_users WHERE user_id = ?", [$user_id])->fetch();
    if (!$user) {
        cot_die_message(404, Cot::$L['mstoreuserproducts_user_not_found']);
    }

    Cot::$out['subtitle'] = Cot::$L['mstoreuserproducts_details_title'] . ' ' . htmlspecialchars($user['user_name']);

    $t = new XTemplate(cot_tplfile('mstoreuserproducts.details', 'plug'));

    $t->assign([
        'USER_NAME' => htmlspecialchars($user['user_name']),
        'USER_PROFILE_URL' => cot_url('users', 'm=details&id=' . $user_id),
        'USER_ID' => $user_id // Явно передаём user_id в шаблон
    ]);

    // Получаем категории из cot_structure для товаров пользователя с иерархией
    $categories_raw = cot::$db->query("
        SELECT DISTINCT s.structure_code, s.structure_title, s.structure_path
        FROM $db_structure AS s
        INNER JOIN $db_mstore AS p ON p.msitem_cat = s.structure_code
        WHERE s.structure_area = 'mstore' AND p.msitem_ownerid = ? AND p.msitem_state = 0
        ORDER BY s.structure_path, s.structure_title
    ", [$user_id])->fetchAll();

    // Строим иерархический массив категорий
    $categories = ['' => Cot::$L['mstoreuserproducts_all_categories']]; // "Все категории" в корне
    foreach ($categories_raw as $cat_row) {
        $path_parts = explode('.', $cat_row['structure_path']);
        $level = count($path_parts) - 1; // Уровень вложенности
        $prefix = str_repeat('    ', $level); // Отступы для визуальной иерархии (4 пробела на уровень)
        $categories[$cat_row['structure_code']] = $prefix . $cat_row['structure_title'];
    }

    // Создаём выпадающий список с автоматической отправкой формы (Bootstrap-стиль)
    $t->assign('CATEGORY_FILTER', cot_selectbox($cat, 'cat', array_keys($categories), array_values($categories), true, 'class="form-control" onchange="this.form.submit()"'));

    // Подсчёт общего количества товаров с учётом фильтра категории
    $where = "p.msitem_ownerid = ? AND p.msitem_state = 0";
    $params = [$user_id];
    if (!empty($cat)) {
        $where .= " AND p.msitem_cat = ?";
        $params[] = $cat;
    }
    $total_articles = cot::$db->query("SELECT COUNT(msitem_id) FROM $db_mstore AS p WHERE $where", $params)->fetchColumn();

    // Получение товаров с учётом фильтра категории
    $offset = ($page - 1) * $max_articles;
    $params = array_merge($params, [$offset, $max_articles]);
    $sql = cot::$db->query("
        SELECT p.msitem_id, p.msitem_alias, p.msitem_cat, p.msitem_title, p.msitem_date, p.msitem_updated, p.msitem_count
        FROM $db_mstore AS p
        WHERE $where
        ORDER BY p.msitem_date DESC
        LIMIT ?, ?
    ", $params);

    $articles = $sql->fetchAll();
    if (count($articles) > 0) {
        foreach ($articles as $row) {
            $urlParams = ['c' => $row['msitem_cat']];
            if (!empty($row['msitem_alias'])) {
                $urlParams['al'] = $row['msitem_alias'];
            } else {
                $urlParams['id'] = $row['msitem_id'];
            }

            $categoryTitle = isset(Cot::$structure['mstore'][$row['msitem_cat']]['title']) 
                ? Cot::$structure['mstore'][$row['msitem_cat']]['title'] 
                : $row['msitem_cat'];

            $t->assign([
                'ARTICLE_CATEGORY' => htmlspecialchars($categoryTitle),
                'ARTICLE_TITLE' => htmlspecialchars($row['msitem_title']),
                'ARTICLE_URL' => cot_url('mstore', $urlParams),
                'ARTICLE_DATE' => cot_date('d.m.Y', $row['msitem_date']),
                'ARTICLE_UPDATED' => cot_date('d.m.Y', $row['msitem_updated']),
                'ARTICLE_VIEWS' => $row['msitem_count']
            ]);
            $t->parse('MAIN.ARTICLE_LIST.ARTICLE');
        }
        $t->parse('MAIN.ARTICLE_LIST');
    } else {
        $t->parse('MAIN.NO_ARTICLES');
    }

    // Пагинация для товаров с учётом категории
    $pagination_params = ['e' => 'mstoreuserproducts', 'action' => 'details', 'uid' => $user_id];
    if (!empty($cat)) {
        $pagination_params['cat'] = $cat;
    }
    $pagination = cot_pagenav('plug', $pagination_params, $offset, $total_articles, $max_articles, 'd');
    $t->assign(cot_generatePaginationTags($pagination));
} else {
    cot_die_message(404);
}