<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->addExternalCss("/local/components/ryabov/root_section.iblock/templates/.default/style.css");
?>
<div class="contaiter">
    <div class="content">
        <div class="root-section">
            <? foreach ($arResult['ROOT_SECTIONS'] as $arrItems) : ?>
            <div class="root-section__items">
                <div class="photo-items">
                    <div class="img-items">
                        <a href="<?=$arrItems['SECTION_PAGE_URL']?>">
                            <img src="<?=$arrItems['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arrItems['PREVIEW_PICTURE']['ALT']?>">
                        </a>
                    </div>
                </div>
                <div class="items-discriptions">
                    <a href="<?=$arrItems['SECTION_PAGE_URL']?>">
                        <div class="discriptions-text"><?=$arrItems['NAME']?></div>
                    </a>
                </div>
            </div>
            <? endforeach; ?>
        </div>
    </div>
</div>
