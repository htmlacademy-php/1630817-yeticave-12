<?php

$lots_without_winners = get_lots_without_winners($con);
foreach ($lots_without_winners as $lot) {
    $winner_id = get_bet_by_id($con, $lot['max_bet_id'])[0]['bet_author'];
    set_lot_winner($con, $winner_id, $lot['id']);
    $lot_url = $_SERVER['REQUEST_URI'].'./lot.php?lot_id='.$lot['id'];
    $url_on_my_bets_page = $_SERVER['REQUEST_URI']."/my_bets.php";
    $winner_info = select_user_info_by_id($con, $winner_id);
    $set_to = $winner_info['email'];
    $message = (new Swift_Message())
        ->setTo($set_to)
        ->setFrom("keks@phpdemo.ru", "keks")
        ->setBody(include_template('email.php', [
            'lot_url' => $lot_url,
            'url_on_my_bets_page' => $url_on_my_bets_page,
            'email' => $winner_info['email'],
            'user_name' => $winner_info['login'],
            'lot_title' => $lot['title'],
        ]), 'text/html');
    $mailer->send($message);
}
