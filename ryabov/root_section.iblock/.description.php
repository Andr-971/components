<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = [
    'NAME' => 'Корневые разделы инфоблока', 
    'DESCRIPTION' => 'Выводит корневые разделы инфоблока',
    'CACHE_PATH' => 'Y', 
    'SORT' => 20, 
    'COMPLEX' => 'N', 
    'PATH' => [ 
        'ID' => 'user_components', 
        'NAME' => 'Пользовательские компоненты', 
        'CHILD' => [ 
            'ID' => 'user_catalog_iblock', 
            'NAME' => 'Пользовательский каталог' 
        ]
    ]
];
?>