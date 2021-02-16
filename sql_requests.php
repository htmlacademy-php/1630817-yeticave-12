<?php
/**
 * Запрос к бд на получение всех категорий
 * @param mysqli $con ресурс соединения
 *
 * @return array массив категорий
 */
function get_categories()
{
    global $con;
    $sql = 'SELECT title,translation FROM categories';
    $categories = sql_request($con, $sql);
    return $categories;
}

/**
 * Запрос к бд на получение всех открытых лотов с фильтрацией по цене и дате публикации
 * @param mysqli $con ресурс соединения
 *
 * @return array массив лотов
 */
function get_lots($con)
{
    $sql = 'SELECT  l.end_date,l.creation_date, l.id as lot_id, l.title ,l.first_price,l.image,b.bet_sum AS current_price, c.title as category FROM lots AS l LEFT JOIN bets AS b ON b.lot_id = l.id  AND b.bet_sum = (SELECT MAX(b.bet_sum) FROM bets AS b  WHERE b.lot_id = l.id) JOIN categories AS c ON c.id = l.category_id   ORDER BY l.creation_date';
    $lots = sql_request($con, $sql);
    foreach ($lots as $key => &$lot) {
        if ($lot['current_price'] === null) {
            $lot['current_price'] = $lot['first_price'];
        }
        if ((date_diff(new DateTime($lot['end_date']),new DateTime())->days >= 1 ) || new DateTime($lot['end_date']) < new DateTime()) {
            unset($lots[$key]);
        }
    }

    return $lots;
}

/**
 * Запрос к бд на получение информации о лоте по его id
 * @param $con mysqli соединения
 *
 * @return array массив данных о лоте
 */
function get_lot_info($con, $lot_id)
{
    $sql = 'SELECT  l.*, b.bet_sum AS current_price, c.translation as category FROM lots AS l LEFT JOIN bets AS b ON b.lot_id = l.id  AND b.bet_sum = (SELECT MAX(b.bet_sum) FROM bets AS b  WHERE b.lot_id = l.id) LEFT JOIN categories AS c ON c.id = l.category_id WHERE l.id = ? ORDER BY  b.bet_sum  DESC;';
    $lot = sql_request($con, $sql, [$lot_id]);
    return $lot;
}


/**
 * Запрос к бд на получение информации  ставках на лот по его id
 * @param mysqli $con ресурс соединения
 *
 * @return array массив ставок
 */
function get_lot_bets($con, $lot_id)
{
    $sql = 'SELECT b.*,users.login FROM bets AS b JOIN users ON users.id = b.bet_author  WHERE lot_id = ?  ORDER BY creation_date DESC ';
    $bets = sql_request($con, $sql, [$lot_id]);
    return $bets;
}

/**
 * Запрос к бд на добавление фото
 * @param  mysqli $con ресурс соединения
 * @param string $title название лота
 * @param string $category категория
 * @param string $message описание
 * @param string $name ресурс имя фотографии
 * @param int $bet ресурс начальная ставка
 * @param int $step ресурс шаг ставки
 * @param string $date ресурс дата окончания
 *
 * @return array результат
 */
function insert_photo($con, $title, $category, $message, $name, $bet, $step, $date)
{
    $result = sql_insert($con,
        'INSERT INTO lots(title,category_id,description, image,first_price,end_date,bet_step,author_id) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)',
        [
            $title,
            $category,
            $message,
            $name,
            $bet,
            $date,
            $step,
            1
        ]);
    return $result;
}


/**
 * Запрос к бд на получение id категории по названию
 * @param mysqli $con ресурс соединения
 * @param string $title название категории

 * @return array id категории
 */
function get_category_id($con, $title){
   return sql_request($con, "SELECT id from categories WHERE translation = ?", [$title]);
}

/**
 * Запрос к бд на проверку, есть ли пользователь с таким email
 * @param mysqli $con ресурс соединения
 * @param string $email название категории

 * @return array id категории
 */
function is_email_exist($con, $email){
    return sql_request($con, "SELECT id from users WHERE email = ?", [$email]);
}


/**
 * Запрос к бд на добавление нового пользователя
 * @param  mysqli $con ресурс соединения
 * @param string $email почта
 * @param string $login имя пользователя
 * @param string $password пароль
 * @param string $contact контактные данные
 * @return array результат
 */
function insert_new_user($con, $email, $login, $password, $contact)
{
    $result = sql_insert($con,
        'INSERT INTO users(email, login, password, contacts) VALUES ( ?, ?, ?, ?)',
        [
            $email,
            $login,
            $password,
            $contact,
        ]);
    return $result;
}

/**
 * Запрос к бд на получени пароля по email
 * @param  mysqli $con ресурс соединения
 * @param string $email почта
 * @return array password
 */
function get_password_by_email($con, $email)
{
    $sql = 'SELECT password FROM users WHERE email = ?';
    $password = sql_request($con, $sql, [$email]);
    return $password;
}
