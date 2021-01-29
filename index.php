<?php
require_once 'helpers.php';
require_once 'config.php';
require_once 'data.php';
require_once 'functions.php';

$is_auth = rand(0, 1);

$page_content = include_template('main.php', ['categories' => $categories, 'lots' =>$lots]);
print (include_template('layout.php', ['categories' => $categories, 'is_auth' =>$is_auth, 'main_content' =>$page_content, 'user_name' => $user_name, 'title' => 'Главная']));

