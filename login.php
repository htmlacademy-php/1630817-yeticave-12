<?php
require_once 'helpers.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'sql_requests.php';


$is_auth = rand(0, 1);
$categories = get_categories($con);

if (! empty($_POST)){
    foreach ($_POST as $key => $field) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Поле не заполнено';
        } else {
            $errors[$key] = null;
        }
    }
    $password_from_db = get_password_by_email($con,$_POST['email']);
    $errors['password'] = password_validation($_POST['password'],$password_from_db[0]['password']);
    if (empty(array_filter($errors))) {
        header("Location: ./index.php");
    }
}


$page_content = include_template('login.php', ['categories' => $categories]);
print (include_template('layout.php', [
    'categories' => $categories,
    'user_name' => 'Mansur',
    'is_auth' => $is_auth,
    'title' => 'Лот',
    'main_content' => $page_content,
]));
