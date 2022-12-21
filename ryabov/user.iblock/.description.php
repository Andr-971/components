<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = [
    'NAME' => 'Каталог(комплексный)',
    'DESCRIPTION' => 'Универсальный компонент для информационного блока',
    'CACHE_PATH' => 'Y', 
    'SORT' => 40, 
    'COMPLEX' => 'Y', 
    'PATH' => [ 
        'ID' => 'user_components', 
        'NAME' => 'Пользовательские компоненты', 
        'CHILD' => [ 
            'ID' => 'user_catalog_iblock', 
            'NAME' => 'Пользовательский каталог' 
        ]
    ]
];