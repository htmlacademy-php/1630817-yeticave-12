<?php
/**
 * Запрос к бд на получение всех категорий
 * @param mysqli $con ресурс соединения
 *
 * @return array массив категорий
 */
function get_categories($con)
{
    $sql = 'SELECT title,translation,id FROM categories';
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
    $sql = 'SELECT  l.end_date,l.creation_date, l.id as lot_id, l.title ,l.first_price,l.image,b.bet_sum AS current_price, c.translation as category FROM lots AS l LEFT JOIN bets AS b ON b.lot_id = l.id  AND b.bet_sum = (SELECT MAX(b.bet_sum) FROM bets AS b  WHERE b.lot_id = l.id) JOIN categories AS c ON c.id = l.category_id   ORDER BY l.end_date ASC';
    $lots = sql_request($con, $sql);
    foreach ($lots as $key => &$lot) {
        if ($lot['current_price'] === null) {
            $lot['current_price'] = $lot['first_price'];
        }
        if (new DateTime($lot['end_date']) < new DateTime()) {
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
    $lot = sql_request($con, $sql, [$lot_id], true);
    if ($lot['current_price'] === null) {
        $lot['current_price'] = $lot['first_price'];
    }

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
 * @param mysqli $con ресурс соединения
 * @param string $title название лота
 * @param string $category категория
 * @param string $message описание
 * @param string $name имя фотографии
 * @param int $bet начальная ставка
 * @param int $step шаг ставки
 * @param int $author_id id автора поста
 * @param array $date дата окончания
 *
 * @return bool результат
 */
function insert_photo($con, $title, $category, $message, $name, $bet, $step, $date, $author_id)
{
    $result = sql_insert(
        $con,
        'INSERT INTO lots(title,category_id,description, image,first_price,end_date,bet_step,author_id) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)',
        [
            $title,
            $category,
            $message,
            $name,
            $bet,
            $date,
            $step,
            $author_id,
        ]
    );

    return $result;
}


/**
 * Запрос к бд на получение id категории по названию
 * @param mysqli $con ресурс соединения
 * @param string $title название категории
 * @return array id категории
 */
function get_category_id($con, $title)
{
    return sql_request($con, "SELECT id from categories WHERE translation = ?", [$title]);
}

/**
 * Запрос к бд на проверку, есть ли пользователь с таким email
 * @param mysqli $con ресурс соединения
 * @param string $email название категории
 * @return array id категории
 */
function is_email_exist($con, $email)
{
    return sql_request($con, "SELECT id from users WHERE email = ?", [$email]);
}


/**
 * Запрос к бд на добавление нового пользователя
 * @param mysqli $con ресурс соединения
 * @param string $email почта
 * @param string $login имя пользователя
 * @param string $password пароль
 * @param string $contact контактные данные
 * @return array результат
 */
function insert_new_user($con, $email, $login, $password, $contact)
{
    $result = sql_insert(
        $con,
        'INSERT INTO users(email, login, password, contacts) VALUES ( ?, ?, ?, ?)',
        [
            $email,
            $login,
            $password,
            $contact,
        ]
    );

    return $result;
}

/**
 * Запрос к бд на получени пароля по email
 * @param mysqli $con ресурс соединения
 * @param string $email почта
 * @return array password
 */
function get_password_by_email($con, $email)
{
    $sql = 'SELECT password FROM users WHERE email = ?';
    $password = sql_request($con, $sql, [$email]);

    return $password;
}

/**
 * Запрос к бд на получени пароля по email
 * @param mysqli $con ресурс соединения
 * @param string $email почта
 * @return array данные пользователя
 */
function get_user_info($con, $email)
{
    $sql = 'SELECT *  FROM users WHERE email = ?';
    $user_info = sql_request($con, $sql, [$email]);

    return $user_info;
}

/**
 * Запрос на поиск лотов из бд
 * @param mysqli $con ресурс соединения
 * @param string $search поисковый запрос
 * @param int $offset пагинация
 * @param int $limit количество
 * @return array лоты
 */
function get_lots_by_search($con, $search, $offset, $limit)
{
    $sql = 'SELECT * FROM  lots WHERE MATCH(description,title) AGAINST(?) LIMIT ? OFFSET ?';
    $lots = sql_request($con, $sql, [$search, $limit, $offset]);

    return $lots;
}

/**
 * Запрос на поиск лотов из бд
 * @param mysqli $con ресурс соединения
 * @param string $search поисковый запрос
 * @return array лоты
 */
function get_lots_count_by_search($con, $search)
{
    $sql = 'SELECT COUNT(id) as count  FROM  lots WHERE MATCH(description,title) AGAINST(?)';
    $count = sql_request($con, $sql, [$search]);

    return $count[0]['count'];
}

/**
 * Запрос на добавление ставки
 * @param mysqli $con ресурс соединения
 * @param string $sum сумма ставки
 * @param string $lot_id id лота
 * @param string $author_id автор ставки
 * @return array лоты
 */
function insert_new_bet($con, int $sum, int $lot_id, int $author_id)
{
    $sql = 'INSERT INTO bets(bet_sum, bet_author, lot_id ) VALUES (?, ?, ?)';
    $result = sql_insert($con, $sql, [$sum, $author_id, $lot_id]);

    return $result;
}

/**
 * Запрос на получение ставки по id
 * @param mysqli $con ресурс соединения
 * @param int $id id ставки
 * @return array ставку
 */
function get_bet_by_id($con, $id)
{
    $sql = 'SELECT b.*,users.login FROM bets AS b JOIN users ON users.id = b.bet_author  WHERE b.id = ? ';
    $bet = sql_request($con, $sql, [$id]);

    return $bet;
}

/**
 * Запрос на добавление ставки
 * @param mysqli $con ресурс соединения
 * @param int $my_id мой id
 * @return array мои ставки
 */
function get_my_bets($con, $my_id)
{
    $sql = 'SELECT b.*, l.title, l.end_date, l.image, c.translation as category FROM bets AS b JOIN lots as l ON l.id = b.lot_id JOIN categories as c ON c.id = l.category_id  WHERE b.bet_author = ? ORDER BY b.creation_date DESC';
    $my_bets = sql_request($con, $sql, [$my_id]);

    return $my_bets;
}

/**
 * Запрос получение лотов закончилось время, но нет без победителя
 * @param mysqli $con ресурс соединения
 * @return array список
 */
function get_lots_without_winners($con)
{
    $sql = 'SELECT l.id, l.title, max(b.id) as max_bet_id from lots as l  join bets as b on b.lot_id = l.id   where winner_id is null AND end_date < CURRENT_TIMESTAMP()  group by l.id';
    $lots = sql_request($con, $sql);

    return $lots;
}


/**
 * Запрос получение лотов закончилось время, но нет без победителя
 * @param mysqli $con ресурс соединения
 * @param mysqli $winner_id победитель торгов
 * @param mysqli $lot_id выигранный лот
 * @return bool результат выполнения
 */
function set_lot_winner($con, $winner_id, $lot_id)
{
    $sql = 'UPDATE lots SET winner_id = ? WHERE id = ? ';
    $update = sql_insert($con, $sql, [$winner_id, $lot_id]);

    return $update;
}

/**
 * Запрос на получение информации о пользователе по id
 * @param mysqli $con ресурс соединения
 * @param mysqli $id пользователя
 * @return array информация о пользователе
 */
function select_user_info_by_id($con, $id)
{
    $sql = 'SELECT *  from users WHERE id = ? ';
    $info = sql_request($con, $sql, [$id]);

    return $info[0];
}

/**
 * Запрос на получение лотов по id  категории
 * @param mysqli $con ресурс соединения
 * @param mysqli $category_id категория поста
 * @return array массив постов
 */
function get_lots_by_category($con, $category_id, $limit, $offset)
{
    $sql = 'SELECT  l.end_date,l.creation_date, l.id,  l.title ,l.first_price,l.image,b.bet_sum AS current_price, c.title as category FROM lots AS l LEFT JOIN bets AS b ON b.lot_id = l.id  AND b.bet_sum = (SELECT MAX(b.bet_sum) FROM bets AS b  WHERE b.lot_id = l.id) JOIN categories AS c ON c.id = l.category_id   WHERE category_id = ?  ORDER BY l.creation_date LIMIT ? OFFSET ? ';
    $lots = sql_request($con, $sql, [$category_id, $limit, $offset]);
    foreach ($lots as $key => &$lot) {
        if ($lot['current_price'] === null) {
            $lot['current_price'] = $lot['first_price'];
        }
    }

    return $lots;
}

/**
 * Запрос на получение информации о пользователе по id
 * @param mysqli $con ресурс соединения
 * @param mysqli $category_id категория поста
 * @return int кол-во постов
 */
function get_lots_count_sorted_by_category($con, $category_id)
{
    $sql = 'SELECT count(id) as count from lots WHERE category_id = ? ';
    $lots = sql_request($con, $sql, [$category_id]);

    return $lots[0]['count'];
}

/**
 * Запрос на получение названия категории по id
 * @param mysqli $con ресурс соединения
 * @param mysqli $category_id категория поста
 * @return string название
 */
function get_category_translation($con, $category_id)
{
    $sql = 'SELECT translation from categories WHERE id = ? ';
    $category_name = sql_request($con, $sql, [$category_id]);

    return $category_name[0]['translation'];
}