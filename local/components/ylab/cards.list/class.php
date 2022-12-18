<?php

namespace YLab\Components;

use Bitrix\Iblock\IblockTable;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\Grid\Options as GridOptions;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Main\UI\PageNavigation;
use \CBitrixComponent;
use \CIBlockElement;
use \Exception;
use \Bitrix\Main\UI\Filter\Options;

/**
 * Class CardsListComponent
 * @package YLab\Components
 * Компонент отображения списка элементов нашего ИБ
 */
class CardsListComponent extends CBitrixComponent
{
    /** @var int $idIBlock ID информационного блока */
    private $idIBlock;

    /** @var string $templateName Имя шаблона компонента */
    private $templateName;

    /**
     * @param $arParams
     * @return array
     * @throws \Bitrix\Main\LoaderException
     */
    public function onPrepareComponentParams($arParams)
    {
        Loader::includeModule('iblock');

        $this->templateName = $this->GetTemplateName();

        return $arParams;
    }
	
	/**
     * Метод executeComponent
     *
     * @return mixed|void
     * @throws Exception
     */
    public function executeComponent()
    {
        $this->idIBlock = self::getIBlockIdByCode('credit_card');
        $this->arResult['ITEMS'] = $this->getElements();
        $this->includeComponentTemplate();
    }
	
	/**
     * Получим элементы Инфо-Блока
     * @return array
     */
    public function getElements(): array
    {
        $result = [];

        $arFilter = [
			'ACTIVE' => 'Y',
			'IBLOCK_ID' => $this->idIBlock
		];

        $elements = CIBlockElement::GetList(
            [],
            $arFilter,
            false,
            false,
            [
                'ID',
                'IBLOCK_ID',
                'PROPERTY_CARD_NUMBER',
                'PROPERTY_CARD_USER',
                'PROPERTY_CARD_TYPE',
            ]
        );


        while ($element = $elements->GetNext()) {
            $cardSecret = md5($element['PROPERTY_CARD_NUMBER_VALUE']);

            $result[] = [
                'ID' => $element['ID'],
                'CARD_NUMBER' => $element['PROPERTY_CARD_NUMBER_VALUE'],
                'CARD_USER' => $element['PROPERTY_CARD_USER_VALUE'],
                'CARD_TYPE' => $element['PROPERTY_CARD_TYPE_VALUE'],
                'CARD_SECRET' => $cardSecret,
            ];
        }

        return $result;
    }
	
	/**
     * Метод возвращает ID инфоблока по символьному коду
     *
     * @param $code
     *
     * @return int|void
     * @throws Exception
     */
    public static function getIBlockIdByCode($code)
    {
        $IB = IblockTable::getList([
            'select' => ['ID'],
            'filter' => ['CODE' => $code],
            'limit' => '1',
            'cache' => ['ttl' => 3600],
        ]);
        $return = $IB->fetch();
        if (!$return) {
            throw new Exception('IBlock with code"' . $code . '" not found');
        }

        return $return['ID'];
    }
}

?>