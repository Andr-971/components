<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
?>

<?$APPLICATION->IncludeComponent(
	"ryabov:section.iblock",
	"",
	Array(
		"ADD_SECTIONS_CHAIN" => $arParams['ADD_SECTIONS_CHAIN'],
		"CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"DISPLAY_BOTTOM_PAGER" => $arParams['DISPLAY_BOTTOM_PAGER'],
		"DISPLAY_TOP_PAGER" => $arParams['DISPLAY_TOP_PAGER'],
		"ELEMENT_COUNT" => $arParams['SECTION_ELEMENT_COUNT'],
		"ELEMENT_URL" => $arResult['ELEMENT_URL'],
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
		"MAIN_PAGE_URL" => $arResult["FOLDER"],
		"MESSAGE_404" => $arParams['MESSAGE_404'],
		"PAGER_SHOW_ALL" => $arParams['PAGER_SHOW_ALL'],
		"PAGER_SHOW_ALWAYS" => $arParams['PAGER_SHOW_ALWAYS'],
		"PAGER_TEMPLATE" => $arParams['PAGER_TEMPLATE'],
		"PAGER_TITLE" => $arParams['PAGER_TITLE'],
		"SECTION_CODE" => $arResult['VARIABLES']['SECTION_CODE'],
		"SECTION_ID" => $arResult['SECTION_ID'],
		"SECTION_URL" => $arResult['SECTION_URL'],
		"SET_BROWSER_TITLE" => $arParams['SECTION_SET_BROWSER_TITLE'],
		"SET_META_DESCRIPTION" => $arParams['SECTION_SET_META_DESCRIPTION'],
		"SET_META_KEYWORDS" => $arParams['SECTION_SET_META_KEYWORDS'],
		"SET_PAGE_TITLE" => $arParams['SECTION_SET_PAGE_TITLE'],
		"SET_STATUS_404" => $arParams['SET_STATUS_404'],
		"SHOW_404" => $arParams['SHOW_404'],
		"USE_CODE_INSTEAD_ID" => $arParams['USE_CODE_INSTEAD_ID']
	)
);?>
