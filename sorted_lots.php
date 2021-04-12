<?php
require_once 'helpers.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'sql_requests.php';

$categories = get_categories($con);
$check_get_request = isset($_GET['category_id']) ? is_numeric($_GET['category_id']) : false;
if ($check_get_request) {
    $page = $_GET['page'] ?? 1;
    $limit = 6;
    $offset = ($page-1)*$limit;
    $lots_count = get_lots_count_sorted_by_category($con, $_GET['category_id']);
    $lots = get_lots_by_category($con, $_GET['category_id'], $limit, $offset);
    $page_count = ceil((int)$lots_count / $limit);
    $no_lots = (int)$lots_count === 0 ?? false;
    $page_content = include_template('sorted_lots.php', [
        'lots' => $lots,
        'page' => $page,
        'limit' => $limit,
        'page_count' => $page_count,
        'no_lots' => $no_lots,
        'category_id' => $_GET['category_id'],
        'category_name' => get_category_translation($con, $_GET['category_id'])
    ]);
    print (include_template('layout.php', [
        'categories' => $categories,
        'is_auth' => $is_auth,
        'main_content' => $page_content,
        'user_name' => $user_name,
        'title' => 'Поиск по категориям',

    ]));
} else {
    http_response_code(404);
    print (include_template('layout.php', [
        'categories' => $categories,
        'user_name' => $user_name,
        'is_auth' => $is_auth,
        'title' => 'Ошибка 404',
        'main_content' => include_template('404.php'),
    ]));
}
