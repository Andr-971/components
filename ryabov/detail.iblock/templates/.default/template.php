<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->addExternalCss("/local/components/ryabov/detail.iblock/templates/.default/style.css"); 
?>
<div class="container">
    <div class="content">
        <h2 class="name-item"><?=$arResult['ELEMENT']['NAME']?></h2>
        <div class="photo-discription">
            <div class="photo-img">
                <div class="img-item">
                    <img src="<?=$arResult['ELEMENT']['DETAIL_PICTURE']['SRC']?>" alt="<?=$arResult['DETAIL_PICTURE']['ALT']?>">
                </div>
            </div>
            <div class="discription">
                <div class="discription-item">
                    <?=$arResult['ELEMENT']['DETAIL_TEXT']?>
                </div>
            </div>
        </div>
        <div class="detailed-description">
            <div class="detailed-description__box">
                <div class="detailed-text">
                    <p class="artnumber detailed-description__item"><?=$arResult['DISPLAY_PROPERTIES']['ARTNUMBER']['NAME']?>: <span><?=$arResult['DISPLAY_PROPERTIES']['ARTNUMBER']['VALUE']?></span></p>
                    <p class="manufacturer detailed-description__item"><?=$arResult['DISPLAY_PROPERTIES']['MANUFACTURER']['NAME']?>: <span><?=$arResult['DISPLAY_PROPERTIES']['MANUFACTURER']['VALUE']?></span></p>
                    <p class="material detailed-description__item"><?=$arResult['DISPLAY_PROPERTIES']['MATERIAL']['NAME']?>: <span><?=$arResult['DISPLAY_PROPERTIES']['MATERIAL']['DISPLAY_VALUE']?></span></p>
                </div>
                <div class="detailed-button">
                    <button><a href="<?=$arResult['ELEMENT']['SECTION_PAGE_URL']?>">Назад в раздел</a></button>
                </div>
            </div>
        </div>
    </div>
</div>
