<?php
require_once 'helpers.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'dbConfig.php';

$is_auth = rand(0, 1);
$sql = 'SELECT  l.creation_date, b.lot_id, l.title ,l.first_price,l.image,b.bet_sum AS current_price, c.title as category FROM lots AS l  JOIN bets AS b ON b.lot_id = l.id JOIN categories AS c ON c.id = l.category_id WHERE b.bet_sum = (SELECT MAX(b.bet_sum) FROM bets AS b  WHERE b.lot_id = l.id) ORDER BY l.creation_date';
$lots = mysqli_fetch_all(mysqli_query($con, $sql), MYSQLI_ASSOC);

$sql = 'SELECT title,translation FROM categories';
$categories = mysqli_fetch_all(mysqli_query($con, $sql), MYSQLI_ASSOC);

$page_content = include_template('main.php', ['categories' => $categories, 'lots' =>$lots]);
print (include_template('layout.php', ['categories' => $categories, 'is_auth' =>$is_auth, 'main_content' =>$page_content, 'user_name' => 'Mansur', 'title' => 'Главная']));

