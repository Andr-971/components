<?php
/**
 * Описание класса Catalog
 * Выводит комплексный компонент
 * @author Андрей
 */
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use \Bitrix\Iblock\Component\Tools;

class Catalog extends CBitrixComponent 
{
    protected function checkModulesIblock()
    {
        if (!Loader::includeModule('iblock')) {
            $this->abortResultCache();
            throw new SystemException('Модуль инфоблок не подключён');
            return;
        }
    }

    protected function getResultSEF() 
    { 
        $arParams = $this->arParams;

        $arResult['FOLDER'] = $arParams['SEF_FOLDER'];
        $arResult['SECTION_URL'] = $arParams['SEF_FOLDER'].$arParams['SEF_URL_TEMPLATES']['section'];
        $arResult['ELEMENT_URL'] = $arParams['SEF_FOLDER'].$arParams['SEF_URL_TEMPLATES']['element'];

        return $arResult;
    }

    protected function getResultNoSEF() 
    { 
        global $APPLICATION;
        $arParams = $this->arParams;
        $arResult['FOLDER'] = htmlspecialcharsbx($APPLICATION->GetCurPage()); 
        $arResult['SECTION_URL'] = htmlspecialcharsbx($APPLICATION->GetCurPage()).'?section=#'.$arParams['VARIABLE_ALIASES']['SECTION_ID'].'#';
        $arResult['ELEMENT_URL'] = htmlspecialcharsbx($APPLICATION->GetCurPage()).'?element=#'.$arParams['VARIABLE_ALIASES']['ELEMENT_ID'].'#';

        return $arResult;
    }

    protected function SefMode() 
    {
        $this->checkModulesIblock();
        $arParams = $this->arParams;
        $arVariables = [];

        $componentPage = CComponentEngine::ParseComponentPath(
            $arParams['SEF_FOLDER'],
            $arParams['SEF_URL_TEMPLATES'], 
            $arVariables 
        );

        if ($componentPage === false && parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) == $arParams['SEF_FOLDER']) {
            $componentPage = 'mainPage';
        }
        CComponentEngine::InitComponentVariables(
            $componentPage,
            null,
            [],
            $arVariables
        );

        $arResult['VARIABLES'] = $arVariables;
        $arResult = array_merge($arResult, $this->getResultSEF());
        $this->arResult = $arResult;

        return $arResult;
    }

    protected function noSefMode() 
    {
        $arParams = $this->arParams;
        $arResult['SECTION_ID'] = htmlspecialcharsbx($_REQUEST['section']);
        $arResult['ELEMENT_ID'] = htmlspecialcharsbx($_REQUEST['element']);
        $arResult = array_merge($arResult, $this->getResultNoSEF());
        $this->arResult = $arResult;

        return $arResult;
    }

    protected function arrayProcessingParams($arParams)
    {
        return $arParams;
    }

    public function onPrepareComponentParams($arParams) 
    {
        $arParams = $this->arrayProcessingParams($arParams);
        return $arParams;
    }

    public function executeComponent() 
    {
        $arParams = $this->arParams;
        global $APPLICATION;
        if ($arParams['SEF_MODE'] == 'Y' || $arParams['USE_CODE_INSTEAD_ID'] == 'Y') {
            $arResult = $this->SefMode();
        }elseif ($arParams['SEF_MODE'] == 'N' || $arParams['USE_CODE_INSTEAD_ID'] == 'N') {
            $arResult = $this->noSefMode();
        }

        $componentPage = '';

        if (isset($_REQUEST['element']) && intval($_REQUEST['element']) > 0)
            $componentPage = 'element'; 
        elseif (isset($arResult['VARIABLES']['ELEMENT_CODE']) && strlen($arResult['VARIABLES']['ELEMENT_CODE']) > 0)
            $componentPage = 'element'; 
        elseif (isset($_REQUEST['section']) && intval($_REQUEST['section']) > 0)
            $componentPage = 'section'; 
        elseif (isset($arResult['VARIABLES']['SECTION_CODE']) && strlen($arResult['VARIABLES']['SECTION_CODE']) > 0)
            $componentPage = 'section'; 
        else
            $componentPage = 'mainPage'; 
    
        $this->includeComponentTemplate($componentPage); 
    }
}
