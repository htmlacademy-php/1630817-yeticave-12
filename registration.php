<?php
require_once 'helpers.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'sql_requests.php';

$is_auth = rand(0, 1);
$categories = get_categories();
$errors = [];
$result = 0;

if ( ! empty($_POST)) {
    foreach ($_POST as $key => $field) {
        if (empty($_POST[$key])) {
            $errors[$key] = 'Поле не заполнено';
        } else {
            $errors[$key] = null;
        }
    }
    $errors['email'] = email_validation($con,$_POST['email']);
    if (empty(array_filter($errors))) {
          $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
          $result = insert_new_user($con,$_POST['email'], $_POST['name'], $password, $_POST['message']);
    }
}
if((int)$result === 1){
    header(sprintf('Location: ../login.php' ));
    exit;
}

$page_content = include_template('registration.php', ['categories' => $categories, 'errors' => $errors]);
print (include_template('layout.php', [
    'categories' => $categories,
    'user_name' => 'Mansur',
    'is_auth' => $is_auth,
    'title' => 'Лот',
    'main_content' => $page_content,
]));
