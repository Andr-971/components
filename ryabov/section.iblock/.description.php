<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = [
    'NAME' => 'Разделы инфоблока', 
    'DESCRIPTION' => 'Выводит разделы инфоблока',
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