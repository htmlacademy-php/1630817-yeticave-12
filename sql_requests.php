<?php

function get_categories($con){
    $sql = 'SELECT title,translation FROM categories';
    $categories = sql_request($con,$sql);
    return $categories;
}

function get_lots($con){
    $sql = 'SELECT  l.creation_date, b.lot_id, l.title ,l.first_price,l.image,b.bet_sum AS current_price, c.title as category FROM lots AS l LEFT JOIN bets AS b ON b.lot_id = l.id  AND b.bet_sum = (SELECT MAX(b.bet_sum) FROM bets AS b  WHERE b.lot_id = l.id) JOIN categories AS c ON c.id = l.category_id   ORDER BY l.creation_date';
    $lots = sql_request($con,$sql);
    foreach ($lots as $key => &$lot) {
        if ($lot['current_price'] === null){
            $lot['current_price'] = $lot['first_price'];
        }
        if (date_diff(new DateTime( $lot['creation_date']), new DateTime())->days >= 1){
            unset($lots[$key]);
        }

    }

    return $lots;
}

function get_lot_info($con,$lot_id){
    $sql = 'SELECT  l.*, b.bet_sum AS current_price, c.translation as category FROM lots AS l  JOIN bets AS b ON b.lot_id = l.id JOIN categories AS c ON c.id = l.category_id WHERE b.bet_sum = (SELECT MAX(b.bet_sum) FROM bets AS b  WHERE b.lot_id = l.id) AND l.id = ? ORDER BY  b.bet_sum  DESC';
    $lot = sql_request($con,$sql, [$lot_id]);
    return $lot;
}


function get_lot_bets($con,$lot_id){
    $sql = 'SELECT b.*,users.login FROM bets AS b JOIN users ON users.id = b.bet_author  WHERE lot_id = ?  ORDER BY creation_date DESC ';
    $bets = sql_request($con,$sql,[$lot_id]);
    return $bets;
}