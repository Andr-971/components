<?php
/**
 * Описание класса Detall
 * Выводит элемент инфоблока детально
 * @author Андрей
 */
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\Date;
use Bitrix\Iblock\Component\Base;
use Bitrix\Iblock\Component\Tools;
use Bitrix\Iblock\InheritedProperty\ElementValues;
use Bitrix\Iblock\InheritedProperty\SectionValues;
use Bitrix\Main\Type\Collection;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Detail extends CBitrixComponent 
{

    protected function checkModulesIblock()
    {
        if (!Loader::includeModule('iblock')) {
            $this->abortResultCache();
            throw new SystemException('Модуль инфоблок не подключён');
            return;
        }
    }
    
    protected function noTimeCache($arParams) 
    {

        if (!isset($arParams['CACHE_TIME'])) {
            return $arParams['CACHE_TIME'] = 3600;
        }
    }

    public function arrayProcessingParams($arParams)
    {
        $this->checkModulesIblock();
        $arParams['IBLOCK_TYPE'] = trim($arParams['IBLOCK_TYPE']);
        $arParams['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']);

        $notFound = false;
        if ($arParams['USE_CODE_INSTEAD_ID'] == 'Y') {
            $arParams['ELEMENT_CODE'] = empty($arParams['ELEMENT_CODE']) ? '' : trim($arParams['ELEMENT_CODE']);
            if (empty($arParams['ELEMENT_CODE'])) {
                $notFound = true;
            }
        } else {
            $arParams['ELEMENT_ID'] = empty($arParams['ELEMENT_ID']) ? 0 : intval($arParams['ELEMENT_ID']);
            if (empty($arParams['ELEMENT_ID'])) {
                $notFound = true;
            }
        }
        if ($notFound) {
            Tools::process404(
                trim($arParams['MESSAGE_404']) ?: 'Элемент инфоблока не найден',
                true,
                $arParams['SET_STATUS_404'] === 'Y',
                $arParams['SHOW_404'] === 'Y',
                $arParams['FILE_404']
            );
            return;
        }
        $arParams['SECTION_URL'] = trim($arParams['SECTION_URL']);
        $arParams['ELEMENT_URL'] = trim($arParams['ELEMENT_URL']);

        return $arParams;
    }

    protected function gettingDataIBlock() 
    {
        global $APPLICATION;
        $arParams = $this->arParams;
        $this->checkModulesIblock();

        if ($arParams['USE_CODE_INSTEAD_ID'] == 'Y') { 
        $ELEMENT_ID = CIBlockFindTools::GetElementID( 
                0,                         
                $arParams['ELEMENT_CODE'], 
                false,                     
                false,                     
                [
                    'IBLOCK_ACTIVE' => 'Y',
                    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                    'ACTIVE' => 'Y',
                    'ACTIVE_DATE' => 'Y',
                    'SECTION_GLOBAL_ACTIVE' => 'Y',
                    'CHECK_PERMISSIONS' => 'Y',
                ]
            );
        } else { 
            $ELEMENT_ID = $arParams['ELEMENT_ID'];
        }

        if ($ELEMENT_ID) {
            $arSelect = [
                'ID',               
                'CODE',              
                'IBLOCK_ID',         
                'IBLOCK_SECTION_ID', 
                'SECTION_PAGE_URL',  
                'NAME',              
                'DETAIL_PICTURE',    
                'DETAIL_TEXT',       
                'DETAIL_PAGE_URL',   
                'SHOW_COUNTER',      
                'PROPERTY_*',       
            ];

            $arFilter = [
                'IBLOCK_ID' => $arParams['IBLOCK_ID'], 
                'IBLOCK_ACTIVE' => 'Y',                
                'ID' => $ELEMENT_ID,                   
                'ACTIVE' => 'Y',                      
                'ACTIVE_DATE' => 'Y',                  
                'SECTION_GLOBAL_ACTIVE' => 'Y',       
                'CHECK_PERMISSIONS' => 'Y',            
            ];
            if ($arParams['SECTION_ID']) {
                $arFilter['SECTION_ID'] = $arParams['SECTION_ID'];
            } elseif ($arParams['SECTION_CODE']) {
                $arFilter['SECTION_CODE'] = $arParams['SECTION_CODE'];
            }

            $rsElement = CIBlockElement::GetList(
                [],        
                $arFilter, 
                false,     
                false,     
                $arSelect  
            );

            $rsElement->SetUrlTemplates($arParams['ELEMENT_URL'], $arParams['SECTION_URL']);
            
            if ($obElement = $rsElement->GetNextElement()) {

                $arResult['ELEMENT'] = $obElement->GetFields();

                $arResult['PROPERTIES'] = $obElement->GetProperties();

                foreach ($arResult['PROPERTIES'] as $code => $data) {
                    $arResult['DISPLAY_PROPERTIES'][$code] = CIBlockFormatProperties::GetDisplayValue($arResult, $data, '');
                }
                unset($arResult['PROPERTIES']);

                $ipropValues = new ElementValues(
                    $arResult['ELEMENT']['IBLOCK_ID'],
                    $arResult['ELEMENT']['ID']
                );

                $arResult['IPROPERTY_VALUES'] = $ipropValues->getValues();

                if (isset($arResult['ELEMENT']['DETAIL_PICTURE'])) { 
                    $arResult['ELEMENT']['DETAIL_PICTURE'] =
                        (0 < $arResult['ELEMENT']['DETAIL_PICTURE'] ? CFile::GetFileArray($arResult['ELEMENT']['DETAIL_PICTURE']) : false);
                    if ($arResult['ELEMENT']['DETAIL_PICTURE']) {
                        $arResult['ELEMENT']['DETAIL_PICTURE']['ALT'] =
                            $arResult['ELEMENT']['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'];
                        if ($arResult['ELEMENT']['DETAIL_PICTURE']['ALT'] == '') {
                            $arResult['ELEMENT']['DETAIL_PICTURE']['ALT'] = $arResult['ELEMENT']['NAME'];
                        }
                        $arResult['ELEMENT']['DETAIL_PICTURE']['TITLE'] =
                            $arResult['ELEMENT']['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'];
                        if ($arResult['ELEMENT']['DETAIL_PICTURE']['TITLE'] == '') {
                            $arResult['ELEMENT']['DETAIL_PICTURE']['TITLE'] = $arResult['ELEMENT']['NAME'];
                        }
                    }
                }

                $arSectionFilter = [
                    'IBLOCK_ID' => $arResult['ELEMENT']['IBLOCK_ID'],
                    'ID' => $arResult['ELEMENT']['IBLOCK_SECTION_ID'],
                    'ACTIVE' => 'Y',
                ];

                $rsSection = CIBlockSection::GetList([], $arSectionFilter);

                $rsSection->SetUrlTemplates('', $arParams['SECTION_URL']);

                if ($arResult['SECTION'] = $rsSection->GetNext()) {

                    $arResult['SECTION']['PATH'] = [];

                    if ($arParams['ADD_SECTIONS_CHAIN'] == 'Y') {
                        $rsPath = CIBlockSection::GetNavChain(
                            $arResult['SECTION']['IBLOCK_ID'],
                            $arResult['SECTION']['ID'],
                            [
                                'ID',
                                'NAME',
                                'SECTION_PAGE_URL'
                            ]
                        );
                        $rsPath->SetUrlTemplates('', $arParams['SECTION_URL']);
                        while ($arPath = $rsPath->GetNext()) {
                            $arResult['SECTION']['PATH'][] = $arPath;
                        }
                    }
                }

            }
            
        }

        if (isset($arResult['ELEMENT']['ID'])) {
            $this->SetResultCacheKeys(
                [
                    'ID',
                    'NAME',
                    'IPROPERTY_VALUES'
                ]
            );
        } else {
            $this->AbortResultCache();
            Tools::process404(
                trim($arParams['MESSAGE_404']) ?: 'Элемент инфоблока не найден',
                true,
                $arParams['SET_STATUS_404'] === 'Y',
                $arParams['SHOW_404'] === 'Y',
                $arParams['FILE_404']
            );
        }

        return $arResult;
    }

    protected function getResult($arResult) 
    { 
        $this->arResult = $this->gettingDataIBlock();
        return $this->arResult;
    }

    public function onPrepareComponentParams($arParams) 
    {
        $this->noTimeCache($arParams);
        $this->arrayProcessingParams($arParams);

        return $arParams;
    }

    public function executeComponent() 
    {
        $arParams = $this->arParams;
        global $USER;
        global $APPLICATION;

        if($this->startResultCache(false, ($arParams['CACHE_GROUPS']==='N' ? false: $USER->GetGroups())))
        {
            $this->arResult = $this->getResult($this->arParams); 
            $this->includeComponentTemplate(); 
        }

        if (isset($this->arResult['ELEMENT']['ID'])) 
        {
            CIBlockElement::CounterInc($this->arResult['ELEMENT']['ID']);
        }

        if ($arParams['SET_PAGE_TITLE'] == 'Y') { 
            if ($this->arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != '') {
                $APPLICATION->SetTitle($this->arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']);
            } else {
                $APPLICATION->SetTitle($arResult['ELEMENT']['NAME']);
            }
        }
        if ($arParams['SET_BROWSER_TITLE'] == 'Y') { 
            if ($this->arResult['IPROPERTY_VALUES']['ELEMENT_META_TITLE'] != '') {
                $APPLICATION->SetPageProperty('title', $this->arResult['IPROPERTY_VALUES']['ELEMENT_META_TITLE']);
            } else {
                $APPLICATION->SetPageProperty('title', $this->arResult['ELEMENT']['NAME']);
            }
        }
        if ($arParams['SET_META_KEYWORDS'] == 'Y' && $this->arResult['IPROPERTY_VALUES']['ELEMENT_META_KEYWORDS'] != '') {
            $APPLICATION->SetPageProperty('keywords', $this->arResult['IPROPERTY_VALUES']['ELEMENT_META_KEYWORDS']);
        }

        if ($arParams['SET_META_DESCRIPTION'] == 'Y' && $this->arResult['IPROPERTY_VALUES']['ELEMENT_META_DESCRIPTION'] != '') {
            $APPLICATION->SetPageProperty('description', $this->arResult['IPROPERTY_VALUES']['ELEMENT_META_DESCRIPTION']);
        }

        if ($arParams['ADD_SECTIONS_CHAIN'] == 'Y' && !empty($this->arResult['SECTION']['PATH'])) {
            foreach ($this->arResult['SECTION']['PATH'] as $arPath) {
                $APPLICATION->AddChainItem($arPath['NAME'], $arPath['~SECTION_PAGE_URL']);
            }
        }
    }
}