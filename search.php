<?php
require_once 'helpers.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'sql_requests.php';

$categories = get_categories($con);
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$page = $_GET['page'] ?? 1;
$limit = 6;
$offset = ($page-1)*$limit;
$lots_count = get_lots_count_by_search($con, $search);
$lots = get_lots_by_search($con, $search, $offset, $limit);
$page_count = ceil((int)$lots_count / $limit);

$page_content = include_template('search.php', [
    'lots' => $lots,
    'page' => $page,
    'search' => $search,
    'limit' => $limit,
    'page_count' => $page_count
]);
print (include_template('layout.php', [
    'categories' => $categories,
    'is_auth' => $is_auth,
    'main_content' => $page_content,
    'user_name' => $user_name,
    'title' => 'Поиск',
]));
