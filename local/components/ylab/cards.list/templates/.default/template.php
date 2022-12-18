<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;

?>
<div class="list">
    <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
        <div>
            <p><?= Loc::getMessage('YLAB.CARD.LIST.NUMBER') ?> <?= $arItem['CARD_NUMBER'] ?></p>
            <p><?= Loc::getMessage('YLAB.CARD.LIST.USER') ?> <?= $arItem['CARD_USER'] ?></p>
            <p><?= Loc::getMessage('YLAB.CARD.LIST.TYPE') ?> <?= $arItem['CARD_TYPE'] ?></p>
            <p><?= Loc::getMessage('YLAB.CARD.LIST.SECRET') ?> <?= $arItem['CARD_SECRET'] ?></p>
        </div>
        <hr>
    <?php } ?>
</div>