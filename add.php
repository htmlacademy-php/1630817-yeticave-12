<?php
require_once 'helpers.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'dbConfig.php';
require_once 'sql_requests.php';

$is_auth = rand(0, 1);
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
    $errors['category'] = (string)$_POST['category'] ===  "Выберите категорию" ? "Выберите категорию" : null;
    $errors['lot-rate'] = first_price_validation($_POST['lot-rate']);
    $errors['lot-step'] = bet_step_validation($_POST['lot-step']);
    $errors['lot-date'] = date_validation($_POST['lot-date']);
    $errors['photo'] = photo_validation($_FILES['lot_img']);
    if (empty(array_filter($errors))) {
        save_photo($_FILES['lot_img']);
        $category_id = get_category_id($con, $_POST['category']);
        $result = insert_photo( $con, $_POST['lot-name'], $category_id[0]['id'], $_POST['message'],  '../uploads/'.$_FILES['lot_img']['name'], $_POST['lot-rate'], $_POST['lot-step'], $_POST['lot-date'] );
    }
}
//echo '<pre>';
//var_dump($errors);
if((int)$result === 1){
    $url = "/lot.php?lot_id=" . mysqli_insert_id($con);
    header(sprintf("Location: %s", $url));
    exit;
}


$page_content = include_template('add_lot.php', ['categories' => get_categories($con), 'errors' =>$errors]);
print (include_template('layout.php', [
    'categories' => get_categories($con),
    'user_name' => 'Mansur',
    'is_auth' => $is_auth,
    'title' => 'Добавить лот',
    'main_content' => $page_content,
]));

echo '<pre>';
var_dump($errors);
