<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->addExternalCss("/local/components/ryabov/section.iblock/templates/.default/style.css"); 

?>
<div class="container">
    <div class="content">
        <h2 class="name-sections"><?=$arResult['NAME']?></h2>
        <div class="<?=$arResult['BACK_BUTTON_CLASS']?>"><button><a href="<?=$arResult['MAIN_PAGE_URL']?>"><?=$arResult['BACK_BUTTON_NAME']?></a></button></div>
        <? $arResult['BACK_BUTTON']?>
        <div class="content-items">
            <? foreach ($arResult['ITEMS'] as $arrItems) : ?>
                <div class="content-item">
                    <div class="photo-item">
                        <div class="img-item">
                            <a href="<?=$arrItems['DETAIL_PAGE_URL']?>">
                                <img src="<?=$arrItems['DETAIL_PICTURE']['SRC']?>" alt="<?=$arrItems['DETAIL_PICTURE']['ALT']?>">
                            </a>
                        </div>
                    </div>
                    <div class="discription-item">
                        <div class="discription-text">
                            <p><?=$arrItems['NAME']?></p>
                        </div>
                        <div class="discription-button">
                            <button><a href="<?=$arrItems['DETAIL_PAGE_URL']?>">Подробно</a></button>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
        <?= $arResult['NAV_STRING']; ?>
    </div>
</div>
