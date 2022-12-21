<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>

<?$APPLICATION->IncludeComponent(
	"ryabov:detail.iblock",
	"",
	Array(
		"ADD_SECTIONS_CHAIN" => $arParams['ADD_SECTIONS_CHAIN'],
		"CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"ELEMENT_CODE" => $arResult['VARIABLES']['ELEMENT_CODE'],
		"ELEMENT_ID" => $arResult['ELEMENT_ID'],
		"ELEMENT_URL" => $arResult['ELEMENT_URL'],
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
		"MESSAGE_404" => $arParams['MESSAGE_404'],
		"SECTION_URL" => $arResult['SECTION_URL'],
		"SET_BROWSER_TITLE" => $arParams['ELEMENT_SET_BROWSER_TITLE'],
		"SET_META_DESCRIPTION" => $arParams['ELEMENT_SET_META_DESCRIPTION'],
		"SET_META_KEYWORDS" => $arParams['ELEMENT_SET_META_KEYWORDS'],
		"SET_PAGE_TITLE" => $arParams['ELEMENT_SET_PAGE_TITLE'],
		"SET_STATUS_404" => $arParams['SET_STATUS_404'],
		"SHOW_404" => $arParams['SHOW_404'],
		"USE_CODE_INSTEAD_ID" => $arParams['USE_CODE_INSTEAD_ID']
	)
);?>