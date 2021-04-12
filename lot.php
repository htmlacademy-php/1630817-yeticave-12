<?php
require_once 'helpers.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'sql_requests.php';

$categories = get_categories($con);
$errors = [];

if ($is_auth && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = get_lot_info($con, $_POST['lot_id']);
    $setting_bets_is_not_active = $lot['end_date'] < date("Y-m-d H:i:s") || !empty($lot['winner_id']) ;
    if (! empty($lot) && !$setting_bets_is_not_active) {
        $min_cost = (int)$lot['current_price'] + (int)$lot['bet_step'];
        $errors['cost'] = validate_cost($_POST['cost'], $min_cost);
        $insert_bet = ! array_filter($errors) ? insert_new_bet($con, $_POST['cost'], $_GET['lot_id'], $_SESSION['id']) : false;
        $lot['current_price'] = $insert_bet ? get_bet_by_id($con, mysqli_insert_id($con))[0]['bet_sum'] : $lot['current_price'];
        if ($insert_bet && empty($errors)) {
            $url = './lot.php?lot_id='.mysqli_insert_id($con);
            header('Location: '.$url);
        }
    }
    $bets = get_lot_bets($con, $_POST['lot_id']);
    $bets_count = count($bets);
    $page_content = include_template('lot.php', [
        'lot' => $lot,
        'errors' => $errors,
        'bets_count' => $bets_count,
        'is_auth' => $is_auth,
        'bets' => $bets,
        'user_name' => $user_name,
        'setting_bets_is_not_active' => $setting_bets_is_not_active,
    ]);
}

$check_get_request = isset($_GET['lot_id']) ? is_numeric($_GET['lot_id']) : false;
if ($check_get_request) {
    $lot = get_lot_info($con, $_GET['lot_id']);
    $setting_bets_is_not_active = $lot['end_date'] < date("Y-m-d H:i:s") || !empty($lot['winner_id']);
    if (! empty($lot)) {
        $bets = get_lot_bets($con, $_GET['lot_id']);
        $bets_count = count($bets);
        $page_content = include_template('lot.php', [
            'lot' => $lot,
            'errors' => $errors,
            'bets_count' => $bets_count,
            'is_auth' => $is_auth,
            'bets' => $bets,
            'user_name' => $user_name,
            'setting_bets_is_not_active' => $setting_bets_is_not_active,
        ]);
        print (include_template('layout.php', [
            'categories' => $categories,
            'user_name' => $user_name,
            'is_auth' => $is_auth,
            'title' => 'Лот',
            'main_content' => $page_content,
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
