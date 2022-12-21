<?php
/**
 * Описание класса Detall
 * Выводит элемент инфоблока детально
 * @author Андрей
 */
use Bitrix\Main\Loader;
use Bitrix\Main\CUser;
use Bitrix\Iblock\Component\Base;
use Bitrix\Iblock\Component\Tools;
use Bitrix\Iblock\InheritedProperty\ElementValues;
use Bitrix\Iblock\InheritedProperty\SectionValues;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class rootSections extends CBitrixComponent 
{
    protected function checkModulesIblock()
    {
        if (!Loader::includeModule('iblock')) {
            $this->abortResultCache();
            throw new SystemException('Модуль инфоблок не подключён');
            return;
        }
    }

    protected function noTimeCache() 
    {
        $arParams = $this->arParams;
        if (!isset($arParams['CACHE_TIME'])) {
            return $arParams['CACHE_TIME'] = 3600;
        }
    }

    protected function arrayProcessingParams($arParams)
    {
        $arParams['IBLOCK_TYPE'] = trim($arParams['IBLOCK_TYPE']);
        $arParams['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']);
        $arParams['SECTION_COUNT'] = intval($arParams['SECTION_COUNT']);
        if ($arParams['SECTION_COUNT'] <= 0) {
            $arParams['SECTION_COUNT'] = 5;
        }
        $arParams['ELEMENT_COUNT'] = intval($arParams['ELEMENT_COUNT']);
        if($arParams['ELEMENT_COUNT'] <= 0) {
            $arParams['ELEMENT_COUNT'] = 3;
        }

        $arParams['SECTION_URL'] = trim($arParams['SECTION_URL']);
        $arParams['ELEMENT_URL'] = trim($arParams['ELEMENT_URL']);

        return $arParams;
    }

    protected function gettingDataIBlock()
    {
        $this->checkModulesIblock();
        $this->noTimeCache();
        $arParams = $this->arParams;

        if ($arParams['ROOT_SECTIONS'] == 'Y') 
        {
            $arSelect = [
                'ID',
                'NAME',
                'PICTURE',
                'DESCRIPTION',
                'DESCRIPTION_TYPE',
                'SECTION_PAGE_URL'
            ];
            $arFilter = [
                'IBLOCK_ID' => $arParams['IBLOCK_ID'], 
                'IBLOCK_ACTIVE' => 'Y',                
                'SECTION_ID' => false,                 
                'ACTIVE' => 'Y',                       
                'CHECK_PERMISSIONS' => 'Y',            
            ];
            $arSort = [
                'SORT' => 'ASC',
            ];
            $rsSections = CIBlockSection::GetList($arSort, $arFilter, false, $arSelect);
            $rsSections->SetUrlTemplates('', $arParams['SECTION_URL']);

            while ($arSection = $rsSections->GetNext()) {
                if (0 < $arSection['PICTURE']) {
                    $arSection['PREVIEW_PICTURE'] = CFile::GetFileArray($arSection['PICTURE']);
                } else {
                    $arSection['PREVIEW_PICTURE'] = false;
                }
                unset($arSection['PICTURE']);
                $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues(
                    $arParams['IBLOCK_ID'],
                    $arSection['ID']
                );
                $arSection['IPROPERTY_VALUES'] = $ipropValues->getValues();

                if ($arSection['PREVIEW_PICTURE']) {
                    $arSection['PREVIEW_PICTURE']['ALT'] =
                        $arSection['IPROPERTY_VALUES']['SECTION_PICTURE_FILE_ALT'];
                    if ($arSection['PREVIEW_PICTURE']['ALT'] == '') {
                        $arSection['PREVIEW_PICTURE']['ALT'] = $arSection['NAME'];
                    }
                    $arSection['PREVIEW_PICTURE']['TITLE'] =
                        $arSection['IPROPERTY_VALUES']['[SECTION_PICTURE_FILE_TITLE'];
                    if ($arSection['PREVIEW_PICTURE']['TITLE'] == '') {
                        $arSection['PREVIEW_PICTURE']['TITLE'] = $arSection['NAME'];
                    }
                }

                $arResult['ROOT_SECTIONS'][] = $arSection;
            }
        }
        $arSelect = [
            'ID',
            'NAME',
            'SECTION_PAGE_URL'
        ];
        $arFilter = [
            'IBLOCK_ID' => $arParams['IBLOCK_ID'], 
            'IBLOCK_ACTIVE' => 'Y',                
            'SECTION_ID' => false,                
            'ACTIVE' => 'Y',                       
            'CHECK_PERMISSIONS' => 'Y',           
        ];
        if (!empty($arParams['POPULAR_SECTIONS'])) {
            $arFilter['ID'] = $arParams['POPULAR_SECTIONS'];
        }
        $arSort = [
            'SORT' => 'ASC',
        ];
        $rsSections = CIBlockSection::GetList($arSort, $arFilter, false, $arSelect);
        $rsSections->SetUrlTemplates('', $arParams['SECTION_URL']);
        $arSelect = [
            'ID',
            'CODE',
            'IBLOCK_ID',
            'NAME',
            'PREVIEW_PICTURE',
            'DETAIL_PAGE_URL',
            'PREVIEW_TEXT_TYPE',
            'PREVIEW_TEXT',
            'SHOW_COUNTER'
        ];
        $arFilter = [
            'ACTIVE' => 'Y',                     
            'IBLOCK_ID' => $arParams['IBLOCK_ID'], 
            'ACTIVE_DATE' => 'Y',                  
            'INCLUDE_SUBSECTIONS' => 'Y',          
            'CHECK_PERMISSIONS' => 'Y',            
        ];
        $arSort = [
            'SHOW_COUNTER' => 'DESC',
        ];
        while ($arSection = $rsSections->GetNext()) {
            $arSection['ITEMS'] = [];
            $arFilter['SECTION_ID'] = $arSection['ID'];
            $rsElements = CIBlockElement::GetList(
                $arSort,
                $arFilter,
                false,
                ['nTopCount' => $arParams['ELEMENT_COUNT']],
                $arSelect
            );
            $rsElements->SetUrlTemplates($arParams['ELEMENT_URL']);
            while($arElement = $rsElements->GetNext()) {
                $ipropValues = new ElementValues(
                    $arElement['IBLOCK_ID'],
                    $arElement['ID']
                );
                $arElement['IPROPERTY_VALUES'] = $ipropValues->getValues();
                if (0 < $arElement['PREVIEW_PICTURE']) {
                    $arElement['PREVIEW_PICTURE'] = CFile::GetFileArray($arElement['PREVIEW_PICTURE']);
                } else {
                    $arElement['PREVIEW_PICTURE'] = false;
                }
                if ($arElement['PREVIEW_PICTURE']) {
                    $arElement['PREVIEW_PICTURE']['ALT'] =
                        $arElement['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_ALT'];
                    if ($arElement['PREVIEW_PICTURE']['ALT'] == '') {
                        $arElement['PREVIEW_PICTURE']['ALT'] = $arElement['NAME'];
                    }
                    $arElement['PREVIEW_PICTURE']['TITLE'] =
                        $arElement['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'];
                    if ($arElement['PREVIEW_PICTURE']['TITLE'] == '') {
                        $arElement['PREVIEW_PICTURE']['TITLE'] = $arElement['NAME'];
                    }
                }
                $arSection['ITEMS'][] = $arElement;
            }

            $arResult['POPULAR_SECTIONS'][] = $arSection;
        }

        $this->SetResultCacheKeys(['IBLOCK']);

        return $arResult;
    }

    protected function getResult($arResult) 
    {
        $this->arResult = $this->gettingDataIBlock();
        return $this->arResult;
    }

    public function onPrepareComponentParams($arParams) {
        $arParams = $this->arrayProcessingParams($arParams);

        return $arParams;
    }

    public function executeComponent() 
    {
        global $USER;
        global $APPLICATION;
        $this->checkModulesIblock();
        $arParams = $this->arParams;
        $rsIblock = CIBlock::GetByID($arParams['IBLOCK_ID']);
        $arResult['IBLOCK'] = $rsIblock ->GetNext();

        if($this->startResultCache(false, ($arParams['CACHE_GROUPS']==='N'? false: $USER->GetGroups())))
        {
            $this->arResult = $this->getResult($this->arParams);
            $this->includeComponentTemplate(); 
        }
        if ($arParams['SET_BROWSER_TITLE']) {
            $APPLICATION->SetPageProperty('title', $arResult['IBLOCK']['NAME']);
        }
        if ($arParams['SET_PAGE_TITLE']) {
            $APPLICATION->SetTitle($arResult['IBLOCK']['NAME']);
        }
    }

}