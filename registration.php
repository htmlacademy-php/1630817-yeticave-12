<?php
require_once 'helpers.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'sql_requests.php';

$categories = get_categories($con);
$errors = [];
$result = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $key => $field) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Поле не заполнено';
        } else {
            $errors[$key] = null;
        }
    }
    $errors['email'] = email_validation($con, $_POST['email']);
    if (empty(array_filter($errors))) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $result = insert_new_user($con, $_POST['email'], $_POST['name'], $password, $_POST['message']);
    }
}
if ((int)$result === 1) {
    header('Location: ./login.php');
    exit;
}

$page_content = include_template('registration.php', ['categories' => $categories, 'errors' => $errors]);
print (include_template('layout.php', [
    'categories' => $categories,
    'user_name' => $user_name,
    'is_auth' => $is_auth,
    'title' => 'Лот',
    'main_content' => $page_content,
]));
