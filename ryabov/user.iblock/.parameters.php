<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if (!CModule::IncludeModule('iblock')) {
    return;
}

$arInfoBlockTypes = CIBlockParameters::GetIBlockTypes();

$arInfoBlocks = [];
$arFilter = ['ACTIVE' => 'Y'];

if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $arFilter['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}
$rsIBlock = CIBlock::GetList(
    ['SORT' => 'ASC'],
    $arFilter
);
while($iblock = $rsIBlock->Fetch()) {
    $arInfoBlocks[$iblock['ID']] = '['.$iblock['ID'].'] '.$iblock['NAME'];
}

$arInfoBlockSections = [
    '-' => '[=Выберите=]',
];
$arFilter = [
    'SECTION_ID' => false, 
    'ACTIVE' => 'Y' 
];

if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $arFilter['IBLOCK_TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}

if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $arFilter['IBLOCK_ID'] = $arCurrentValues['IBLOCK_ID'];
}
$result = CIBlockSection::GetList(
    ['SORT' => 'ASC'],
    $arFilter
);
while ($section = $result->Fetch()) {
    $arInfoBlockSections[$section['ID']] = '['.$section['ID'].'] '.$section['NAME'];
}

$arComponentParameters = [ 
    'GROUPS' => [
        'POPULAR_SETTINGS' => [
            'NAME' => 'Настройки главной страницы',
            'SORT' => 800
        ],
        'SECTION_SETTINGS' => [
            'NAME' => 'Настройки страницы раздела',
            'SORT' => 900
        ],
        'ELEMENT_SETTINGS' => [
            'NAME' => 'Настройки страницы элемента',
            'SORT' => 1000
        ],
    ],
    'PARAMETERS' => [
        'IBLOCK_TYPE' => [
            'PARENT' => 'BASE',
            'NAME' => 'Тип инфоблока',
            'TYPE' => 'LIST',
            'VALUES' => $arInfoBlockTypes,
            'REFRESH' => 'Y',
        ],
        'IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Инфоблок',
            'TYPE' => 'LIST',
            'VALUES' => $arInfoBlocks,
            'REFRESH' => 'Y',
        ],
        'USE_CODE_INSTEAD_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Использовать символьный код вместо ID',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
        ],
        'ADD_SECTIONS_CHAIN' => [
            'PARENT' => 'BASE',
            'NAME' => 'Включать родителей в цепочку навигации',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
        'POPULAR_ROOT_SECTIONS' => [
            'PARENT' => 'POPULAR_SETTINGS',
            'NAME' => 'Показывать корневые разделы',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
        'POPULAR_SECTIONS' => [
            'PARENT' => 'POPULAR_SETTINGS',
            'NAME' => 'Выберите разделы инфоблока',
            'TYPE' => 'LIST',
            'VALUES' => $arInfoBlockSections,
            'MULTIPLE'=>'Y',
            'REFRESH' => 'Y',
            'SIZE' => '7'
        ],
        'POPULAR_ELEMENT_COUNT' => [
            'PARENT' => 'POPULAR_SETTINGS',
            'NAME' => 'Максимальное количество элементов в разделе',
            'TYPE' => 'STRING',
            'DEFAULT' => '2',
        ],
        'POPULAR_SET_PAGE_TITLE' => [
            'PARENT' => 'POPULAR_SETTINGS',
            'NAME' => 'Устанавливать заголовок страницы из названия инфоблока',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
        'POPULAR_SET_BROWSER_TITLE' => [
            'PARENT' => 'POPULAR_SETTINGS',
            'NAME' => 'Устанавливать заголовок окна браузера из названия инфоблока',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
        'SECTION_ELEMENT_COUNT' => [
            'PARENT' => 'SECTION_SETTINGS',
            'NAME' => 'Количество элементов на странице',
            'TYPE' => 'STRING',
            'DEFAULT' => '2',
        ],
        'SECTION_SET_PAGE_TITLE' => [
            'PARENT' => 'SECTION_SETTINGS',
            'NAME' => 'Устанавливать заголовок страницы для раздела',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
        'SECTION_SET_BROWSER_TITLE' => [
            'PARENT' => 'SECTION_SETTINGS',
            'NAME' => 'Устанавливать заголовок окна браузера для раздела',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
        'SECTION_SET_META_KEYWORDS' => [
            'PARENT' => 'SECTION_SETTINGS',
            'NAME' => 'Устанавливать мета-тег keywords для раздела',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
        'SECTION_SET_META_DESCRIPTION' => [
            'PARENT' => 'SECTION_SETTINGS',
            'NAME' => 'Устанавливать мета-тег description для раздела',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
        'ELEMENT_SET_PAGE_TITLE' => [
            'PARENT' => 'ELEMENT_SETTINGS',
            'NAME' => 'Устанавливать заголовок страницы для элемента',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
        'ELEMENT_SET_BROWSER_TITLE' => [
            'PARENT' => 'ELEMENT_SETTINGS',
            'NAME' => 'Устанавливать заголовок окна браузера для элемента',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
        'ELEMENT_SET_META_KEYWORDS' => [
            'PARENT' => 'ELEMENT_SETTINGS',
            'NAME' => 'Устанавливать мета-тег keywords для элемента',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
        'ELEMENT_SET_META_DESCRIPTION' => [
            'PARENT' => 'ELEMENT_SETTINGS',
            'NAME' => 'Устанавливать мета-тег description для элемента',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
        'VARIABLE_ALIASES' => [ 
            'MAIN_PAGE_URL' => ['NAME' => 'Главная страница',
            ],
            'SECTION_ID' => ['NAME' => 'Идентификатор раздела',
            ],
            'ELEMENT_ID' => ['NAME' => 'Идентификатор элемента',
            ],
        ],
        'SEF_MODE' => [ 
            'mainPage' => [
                'NAME' => 'Главная страница', 
                'DEFAULT' => 'mainPage',
            ],
            'section' => [
                'NAME' => 'Страница раздела',
                'DEFAULT' => '#SECTION_CODE#/',
            ],
            'element' => [
                'NAME' => 'Страница элемента',
                'DEFAULT' => '#SECTION_CODE#/#ELEMENT_CODE#/',
            ],
        ],
        'CACHE_TIME'  =>  ['DEFAULT' => 3600],
        'CACHE_GROUPS' => [ 
            'PARENT' => 'CACHE_SETTINGS',
            'NAME' => 'Учитывать права доступа',
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ],
    ],
];

CIBlockParameters::AddPagerSettings(
    $arComponentParameters,
    'Элементы',  
    false,       
    true         
);

CIBlockParameters::Add404Settings($arComponentParameters, $arCurrentValues);