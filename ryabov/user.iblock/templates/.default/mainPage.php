<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>

<?$APPLICATION->IncludeComponent(
	"ryabov:root_section.iblock",
	"",
	Array(
		"CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"ELEMENT_COUNT" => $arParams['POPULAR_ELEMENT_COUNT'],
		"ELEMENT_URL" => $arResult['ELEMENT_URL'],
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
		"POPULAR_SECTIONS" => $arParams['POPULAR_SECTIONS'],
		"ROOT_SECTIONS" => $arParams['POPULAR_ROOT_SECTIONS'],
		"SECTION_URL" => $arResult['SECTION_URL'],
		"SET_BROWSER_TITLE" => $arParams['POPULAR_SET_BROWSER_TITLE'],
		"SET_PAGE_TITLE" => $arParams['POPULAR_SET_PAGE_TITLE'],
		"USE_CODE_INSTEAD_ID" => $arParams['USE_CODE_INSTEAD_ID']
	)
);?>

