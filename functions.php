<?php

/**
 * Возвращает цену отформатированную цену для удобства чтения, разделяя по тысячам
 * @param string $price цена
 * @return string
 */
function format_price($price)
{
    return number_format($price, 0, ',', ' ').' <b class="rub">р</b>';
}

/**
 * Возвращает время, в течении которого лот будет активен
 * @param string $date время публикации поста
 * @return string разница с текущим временем
 */
function timer($date)
{
    $timer_end = new DateTime($date);
    $current_time = new DateTime();
    $diff = $current_time->diff($timer_end);
    if ($timer_end < $current_time) {
        return array('status' => true, 'remaining_time' => '00:00');
    }
    if ($diff->format("%d") >= 1) {
        return array('status' => true, 'remaining_time' =>'До начала торгов еще ' . seePlural($diff -> format('%d'), 'день', 'дня', 'дней','')  );
    }
    if ($diff->format("%H") < 1) {
        return array('status' => true, 'remaining_time' => $diff->format("%H:%I"));
    }

    return array('status' => false, 'remaining_time' => $diff->format("%H:%I"));
}

/**
 * Возвращает разницу во времени публикации тавки с текущим временем
 * @param string $data значение даты, с которой хотите сравнить текущее время
 * @return string разница с текущим временем
 */
function elapsed_time($data)
{
    $posting_date = new DateTime($data);
    $current_time = new DateTime();
    $date = $current_time->diff($posting_date);

    if ($date->format('%y') >= 1) {
        return $posting_date->format("d-M-Y");
    }
    if (1.25 <= ((int)$date->format('%m')) + ($date->format('%d')) / 31) {
        return seePlural($date->format('%m'), 'месяц', 'месяца', 'месяцев');
    }
    if (7 <= (int)$date->format('%m') * 31 + (int)$date->format('%d')) {
        return seePlural(
            floor(($date->format('%d')) / 7) + (int)$date->format('%m') * 4,
            'неделю',
            'недели',
            'недель'
        );
    }
    if ((1 <= $date->format('%d'))) {
        return seePlural($date->format('%d'), 'день', 'дня', 'дней');
    }
    if (1 <= $date->format('%h')) {
        return seePlural($date->format('%h'), 'час', 'часа', 'часов');
    }

    return seePlural($date->format('%i'), 'минута', 'минуты', 'минут');

}

/**
 * Вспомогательная функция, помогающая определить множественную форму существительного и объединить в предложение
 * @param string $str дата
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 * @param string $ending окончанае предложения
 * @return string
 */
function seePlural($str, $one, $two, $many, $ending = ' назад')
{
    return $str.' '.get_noun_plural_form($str, $one, $two, $many).$ending;
}


/**
 * Функция для получения данных из бд
 * @param mysqli $con подключение к бд
 * @param string $sql запрос sql
 * @param array $data массив с данными для вставки в sql
 * @return array массив данных из бд
 */
function sql_request($con, $sql, $data = [])
{
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

/**
 * Функция для записи данных в бд
 * @param mysqli $con подключение к бд
 * @param string $sql запрос sql
 * @param array $data массив с данными для вставки в sql
 * @return array массив данных из бд
 */
function sql_insert($con, $sql, $data = [])
{
    $stmt = db_get_prepare_stmt($con, $sql, $data);
    $res = mysqli_stmt_execute($stmt);
    return $res;
}

/**
 * Функция валидации начальной цены
 * @param int $price начальная цена
 * @return string Ошибка
 */

function first_price_validation($price)
{
    if ( ! empty($price)) {
        if ($price < 0) {
            return "Некорректная начальная цена";
        }
    } else {
        return "Поле не заполнено";
    }

    return null;
}

/**
 * Функция валидации даты окончания торгов
 * @param int $date дата окончания торгов
 * @return string Ошибка
 */
function date_validation($date)
{
    if ( ! empty($date)) {
        if ( ! (new DateTime($date) > new DateTime())) {
            return "Дата заверешния должна быть больше текущей даты";
        }
        if ( ! is_date_valid($date)) {
            return "Неверный формат даты";
        }
    } else {
        return "Поле не заполнено";
    }

    return null;
}

/**
 * Функция валидации шага ставки
 * @param int $step шаг ставки
 * @return string Ошибка
 */
function bet_step_validation($step)
{
    if ( ! empty($step)) {
        if ( ! (is_numeric($step) && $step > 0)) {
            return "Неверный шаг ставки";
        }
    } else {
        return "Поле не заполнено";
    }

    return null;
}

/**
 * Функция проводящая валидацию фотографии
 * @param array $photo получает картинку на вход
 * @return string   ошибка полученная при валидации
 */
function photo_validation($photo)
{
    if ( ! empty($photo['name'])) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $photo['tmp_name']);
        $extension = array("image/jpg", "image/png", "image/jpeg");
        if ( ! in_array($file_type, $extension)) {
            return 'Неверный формат';
        }
    } else {
        return 'Поле не заполнено';
    }

    return null;
}


/**
 * Функция сохранающая фотографию
 * @param array $photo получает картинку на вход
 */
function save_photo($photo)
{
    $file_name = $photo['name'];
    $file_path = __DIR__.'/uploads/';
    move_uploaded_file($photo['tmp_name'], $file_path.$file_name);
}