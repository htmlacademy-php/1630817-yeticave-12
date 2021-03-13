<?php
require_once 'helpers.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'sql_requests.php';

$categories = get_categories($con);
$errors = [];

if (isset($_GET['lot_id']) ? is_numeric($_GET['lot_id']) : false) {
    $lot = get_lot_info($con, $_GET['lot_id']);
    if ( ! empty($lot)) {
        $min_cost =  (int)$lot[0]['current_price'] + (int)$lot[0]['bet_step'];
        if ($is_auth && $_SERVER['REQUEST_METHOD'] == 'POST' && ! empty($_POST['cost'])) {
            $errors['cost'] = validate_cost($_POST['cost'], $min_cost);
            $insert_bet = ! array_filter($errors) ? insert_new_bet($con, $_POST['cost'], $_GET['lot_id'], $_SESSION['id']) : false;
            $lot[0]['current_price'] = $insert_bet ? get_bet_by_id($con,mysqli_insert_id($con))[0]['bet_sum'] : $lot[0]['current_price'] ;
        }
        $bets = get_lot_bets($con, $_GET['lot_id']);
        $bets_count = count($bets);
        $page_content = include_template('lot.php', [
            'categories' => $categories,
            'lot' => $lot[0],
            'errors' => $errors,
            'bets_count' => $bets_count,
            'is_auth' => $is_auth,
            'bets' => $bets,
            'user_name' => isset($_SESSION['login']) ? $_SESSION['login'] : '',
        ]);
        print (include_template('layout.php', [
            'categories' => $categories,
            'user_name' => isset($_SESSION['login']) ? $_SESSION['login'] : '',
            'is_auth' => $is_auth,
            'title' => 'Лот',
            'main_content' => $page_content,
        ]));
    } else {
        http_response_code(404);
        print (include_template('layout.php', [
            'categories' => $categories,
            'user_name' => isset($_SESSION['login']) ? $_SESSION['login'] : '',
            'is_auth' => $is_auth,
            'title' => 'Ошибка 404',
            'main_content' => include_template('404.php', ['categories' => $categories]),
        ]));
    }
} else {
    http_response_code(404);
    print (include_template('layout.php', [
        'categories' => $categories,
        'user_name' => isset($_SESSION['login']) ? $_SESSION['login'] : '',
        'is_auth' => $is_auth,
        'title' => 'Ошибка 404',
        'main_content' => include_template('404.php', ['categories' => $categories]),
    ]));
}
