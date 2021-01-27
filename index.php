<?php
require_once 'helpers.php';

$is_auth = rand(0, 1);

$user_name = 'Mansur';

$categories  = [
        'boards' => 'Доски и лыжи' ,
        'attachment' => 'Крепления',
        'boots' => 'Ботинки',
        'clothing' => 'Одежда',
        'tools' => 'Инструменты',
        'other' => 'Разное'
];
$lots = [
    [
        'title' => '2014 Rossignol District Snowboard',
        'type' => $categories['boards'],
        'price' => 10999,
        'image' => 'img/lot-1.jpg',
        'timer' => '16:16',
    ],
    [
        'title' => 'DC Ply Mens 2016/2017 Snowboard',
        'type' => $categories['boards'],
        'price' => 159999,
        'image' => 'img/lot-2.jpg',
        'timer' => '11:22',
    ],
    [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'type' => $categories['attachment'],
        'price' => 8000,
        'image' => 'img/lot-3.jpg',
        'timer' => '13:13',
    ],
    [
        'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'type' =>  $categories['boots'],
        'price' => 10999,
        'image' => 'img/lot-4.jpg',
        'timer' => '21:21',
    ],
    [
        'title' => 'Куртка для сноуборда DC Mutiny Charocal',
        'type' => $categories['clothing'],
        'price' => 7500,
        'image' => 'img/lot-5.jpg',
        'timer' => '23:23',
    ],
    [
        'title' => 'Маска Oakley Canopy',
        'type' => $categories['other'],
        'price' => 5400,
        'image' => 'img/lot-6.jpg',
        'timer' => '23:44',
    ]
];

function format_price($price)
{
    return  number_format($price, 0, ',', ' ') . ' <b class="rub">р</b>';
}

$page_content = include_template('main.php', ['categories' => $categories, 'lots' =>$lots]);
print (include_template('layout.php', ['categories' => $categories, 'is_auth' =>$is_auth, 'main_content' =>$page_content, 'user_name' => $user_name, 'title' => 'Главная']));
