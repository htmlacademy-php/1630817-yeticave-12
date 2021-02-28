<?php
session_start();
require_once 'helpers.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'sql_requests.php';


$categories = get_categories($con);
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors['email'] = login_email_validation($con, $_POST['email']);
    $password_from_db = get_password_by_email($con, $_POST['email']);
    $errors['password'] = password_validation($_POST['password'], $password_from_db);
    if (empty(array_filter($errors))) {
        $user_info  =  get_user_info($con,$_POST['email'])[0];
        $_SESSION['id'] = $user_info['id'];
        $_SESSION['login'] = $user_info['login'];
        header("Location: ./index.php");
    }
}


$page_content = include_template('login.php', ['categories' => $categories, 'errors' => $errors]);
print (include_template('layout.php', [
    'categories' => $categories,
    'user_name' => '',
    'is_auth' => 0,
    'title' => 'Лот',
    'main_content' => $page_content,
]));
