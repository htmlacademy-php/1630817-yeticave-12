<?php
require_once 'helpers.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'dbConfig.php';

$is_auth = rand(0, 1);
$sql = 'SELECT title,translation FROM categories';
$categories = sql_request($con,$sql);

if(isset($_GET['lot_id']) && !empty($_GET['lot_id']) && is_numeric($_GET['lot_id'])){
    $sql = 'SELECT  l.*, b.bet_sum AS current_price, c.translation as category FROM lots AS l  JOIN bets AS b ON b.lot_id = l.id JOIN categories AS c ON c.id = l.category_id WHERE b.bet_sum = (SELECT MAX(b.bet_sum) FROM bets AS b  WHERE b.lot_id = l.id) AND l.id = ?';
    $lot = sql_request($con,$sql, [$_GET['lot_id']]);
    if(!empty($lot)){
        $sql = 'SELECT b.*,users.login FROM bets AS b JOIN users ON users.id = b.bet_author  WHERE lot_id = ?  ORDER BY creation_date DESC ';
        $bets = sql_request($con,$sql,[$_GET['lot_id']]);
        $page_content = include_template('lot.php', ['categories' => $categories, 'lot' => $lot[0], 'bets' =>$bets]);
        print (include_template('layout.php', ['categories' => $categories,'user_name' => 'Mansur', 'is_auth' =>$is_auth,'title' => 'Лот', 'main_content' =>$page_content]));
    }
    else{
        print (include_template('layout.php', ['categories' => $categories,'user_name' => 'Mansur', 'is_auth' =>$is_auth,'title' => 'Лот', 'main_content' => include_template('404.php')]));
    }
}
else{
    print (include_template('layout.php', ['categories' => $categories,'user_name' => 'Mansur', 'is_auth' =>$is_auth,'title' => 'Лот', 'main_content' =>include_template('404.php')]));
}
