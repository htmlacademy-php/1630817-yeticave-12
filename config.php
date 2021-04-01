<?php
session_start();
//require_once('vendor/autoload.php');

date_default_timezone_set('Asia/Almaty');

$con = mysqli_connect("1630817-yeticave-12", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");

$is_auth = isset($_SESSION['id']) ?? false;
$user_name = isset($_SESSION['login']) ? $_SESSION['login'] : false;

//$transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
//    ->setUsername('keks@phpdemo.ru')
//    ->setPassword('htmlacademy');
//$mailer = new Swift_Mailer($transport);
