<?php
require_once 'helpers.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'sql_requests.php';
require_once 'get_winner.php';

$categories = get_categories($con);
$lots = get_lots($con);
$page_content = include_template('main.php', ['lots' => $lots]);
print (include_template('layout.php', [
    'categories' => $categories,
    'is_auth' => $is_auth,
    'main_content' => $page_content,
    'user_name' => $user_name,
    'title' => 'Главная',
    'welcome_page' => true
]));
