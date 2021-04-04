<?php
require_once 'helpers.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'sql_requests.php';

$categories = get_categories($con);
if ($is_auth) {
    $my_bets = get_my_bets($con, $_SESSION['id']);
    $page_content = include_template('my_bets.php', [
        'categories' => $categories,
        'my_bets' => $my_bets
    ]);
    print (include_template('layout.php', [
        'categories' => $categories,
        'is_auth' => $is_auth,
        'main_content' => $page_content,
        'user_name' => $user_name,
        'title' => 'Мои ставки',
    ]));
} else {
    http_response_code(403);
    print (include_template('layout.php', [
        'categories' => $categories,
        'user_name' => $user_name,
        'is_auth' => $is_auth,
        'title' => 'Ошибка 403',
        'main_content' => include_template('403.php', ['categories' => $categories, 'message' => 'Страница "Мои ставки" доступна только зарегестрированным пользователям']),
    ]));
}
