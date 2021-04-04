<?php
//require_once 'helpers.php';
//require_once 'config.php';
//require_once 'functions.php';
//require_once 'sql_requests.php';
//
//$lots_without_winners = get_lots_without_winners($con);
//foreach ($lots_without_winners as $lot) {
//    $winner_id  = get_bet_by_id($con,$lot['max_bet_id'])['bet_author'];
//    set_lot_winner($con, $winner_id, $lot['id']);
//    $message = (new Swift_Message())
//        ->setTo("taktashev.m@gmail.com")
//        ->setFrom("keks@phpdemo.ru", "keks")
//        ->setBody(include_template('email.php',[$ссылка_на_лот, $ссылка_на_страницу_мои_ставки]));
//    $mailer->send($message);
//}
//
//
