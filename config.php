<?php
session_start();

date_default_timezone_set('Asia/Almaty');

$con = mysqli_connect("1630817-yeticave-12", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");

$is_auth = isset($_SESSION['id']) ?? 0;
