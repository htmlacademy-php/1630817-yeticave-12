<?php
require_once 'helpers.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'dbConfig.php';
require_once 'sql_requests.php';

$is_auth = rand(0, 1);
$categories = get_categories($con);

if ( ! empty($_GET['lot_id']) && is_numeric($_GET['lot_id'])) {
    $lot = get_lot_info($con, $_GET['lot_id']);
    if ( ! empty($lot)) {
        $bets = get_lot_bets($con, $_GET['lot_id']);
        $page_content = include_template('lot.php', ['categories' => $categories, 'lot' => $lot[0], 'bets' => $bets]);
        print (include_template('layout.php', [
            'categories' => $categories,
            'user_name' => 'Mansur',
            'is_auth' => $is_auth,
            'title' => 'Лот',
            'main_content' => $page_content,
        ]));
    } else {
        http_response_code(404);
        print (include_template('layout.php', [
            'categories' => $categories,
            'user_name' => 'Mansur',
            'is_auth' => $is_auth,
            'title' => 'Ошибка 404',
            'main_content' => include_template('404.php', ['categories' => $categories]),
        ]));
    }
} else {
    http_response_code(404);
    print (include_template('layout.php', [
        'categories' => $categories,
        'user_name' => 'Mansur',
        'is_auth' => $is_auth,
        'title' => 'Ошибка 404',
        'main_content' => include_template('404.php', ['categories' => $categories]),
    ]));
}
