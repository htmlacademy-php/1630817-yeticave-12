<?php
require_once 'helpers.php';
require_once 'config.php';
require_once 'functions.php';
require_once 'sql_requests.php';

$categories = get_categories($con);

if ($is_auth) {
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
        $errors['category'] = $errors['category'] = (string)$_POST['category'] === 'Выберите категорию' ? 'Выберите категорию' : null;
        $errors['lot-rate'] = first_price_validation($_POST['lot-rate']);
        $errors['lot-step'] = bet_step_validation($_POST['lot-step']);
        $errors['lot-date'] = date_validation($_POST['lot-date']);
        $errors['photo'] = photo_validation($_FILES['lot_img']);
        if (empty(array_filter($errors))) {
            save_photo($_FILES['lot_img']);
            $category_id = get_category_id($con, $_POST['category']);
            $result = insert_photo(
                $con,
                $_POST['lot-name'],
                $category_id[0]['id'],
                $_POST['message'],
                '../uploads/'.$_FILES['lot_img']['name'],
                $_POST['lot-rate'],
                $_POST['lot-step'],
                $_POST['lot-date'],
                $_SESSION['id']
            );
        }
    }

    if ((int)$result === 1) {
        $url = './lot.php?lot_id='.mysqli_insert_id($con);
        header('Location: '.$url);
        exit;
    }


    $page_content = include_template('add_lot.php', ['categories' => $categories, 'errors' => $errors]);
    print (include_template('layout.php', [
        'categories' => $categories,
        'user_name' => $user_name,
        'is_auth' => $is_auth,
        'title' => 'Добавить лот',
        'main_content' => $page_content,
    ]));
} else {
    http_response_code(403);
    print (include_template('layout.php', [
        'categories' => $categories,
        'user_name' => $user_name,
        'is_auth' => $is_auth,
        'title' => 'Ошибка 403',
        'main_content' => include_template('403.php', ['categories' => $categories, 'message' => 'Добавление лота доступно только зарегестрированным пользователям.']),
    ]));
}
