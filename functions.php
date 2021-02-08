<?php

/**
 * Возвращает цену отформатированную цену для удобства чтения, разделяя по тысячам
 * @param string $price цена
 * @return string
 */

function format_price($price)
{
    return  number_format($price, 0, ',', ' ') . ' <b class="rub">р</b>';
}

/**
 * Возвращает время, в течении которого лот будет активен
 * @param string $date время публикации поста
 * @return string разница с текущим временем
 */

function timer($date){
    $timer_end = new DateTime($date);
    $current_time = new DateTime();
    $diff = $current_time -> diff($timer_end);
    if( $timer_end < $current_time){
        return array('status' => 'timer--finishing', 'remaining_time' => '00:00' );
    }
    elseif ($diff->format("%H") < 1 ){
        return array('status' => 'timer--finishing', 'remaining_time' => $diff -> format("%H:%I") );
    }
    return array('status' => '', 'remaining_time' => $diff -> format("%H:%I") );
}

/**
 * Возвращает разницу во времени публикации тавки с текущим временем
 * @param string $data значение даты, с которой хотите сравнить текущее время
 * @return string разница с текущим временем
 */

function elapsed_time($data) {
    $posting_date = new DateTime($data);
    $current_time = new DateTime();
    $date = $current_time->diff($posting_date);

    if ($date->format('%y') >= 1) {
        return $posting_date->format("d-M-Y");
    } elseif (1.25 <= ((int)$date->format('%m')) + ($date->format('%d')) / 31) {
        return seePlural($date->format('%m'), 'месяц', 'месяца', 'месяцев');
    } elseif (7 <= (int)$date->format('%m') * 31 + (int)$date->format('%d')) {
        return seePlural(
            floor(($date->format('%d')) / 7) + (int)$date->format('%m') * 4,
            ' неделю',
            'недели',
            'недель'
        );
    } elseif ((1 <= $date->format('%d'))) {
        return seePlural($date->format('%d'), 'день', 'дня', 'дней');
    } elseif (1 <= $date->format('%h')) {
        return seePlural($date->format('%h'), 'час', 'часа', 'часов');
    } else {
        return seePlural($date->format('%i'), 'минута', 'минуты', 'минут');
    }
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


function seePlural($str, $one, $two, $many, $ending = ' назад') {
    return $str.' '.get_noun_plural_form($str, $one, $two, $many).$ending;
}


/**
 * Функция для получения данных из бд
 * @param string $con подключение к бд
 * @param string $sql запрос sql
 * @param array $data массив с данными для вставки в sql
 * @return array массив данных из бд
 */

function sql_request($con,$sql,$data = []) {
    $stmt = db_get_prepare_stmt($con,$sql,$data);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    return  mysqli_fetch_all($res, MYSQLI_ASSOC);
}
