<?php
namespace Podval\Listeners;

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Podval\Core\Logger;

class Crm implements \Podval\Core\Interfaces\Listener
{
    //OnAfterCrmControlPanelBuild Событие построения верхнего меню в crm

    public static function onAfterCrmDealUpdate(&$arResult): void
    {
        Logger::debug($arResult, "onBeforeCrmDealUpdate-data.log");

        if (!$arResult["CATEGORY_ID"])
            $arResult["CATEGORY_ID"] = 0;

        // check if document signature and to stage FINAL_INVOICE
        if ($arResult['STAGE_ID'] === 'FINAL_INVOICE' && $arResult["CATEGORY_ID"] === 0)
        {
            $request = \ITS\Personal\Modules\Requests::getByDealId($arResult["ID"]);

            if ($request)
                \CIBlockElement::SetPropertyValueCode($request->getId(), "STATUS", 63);
        }

        // documents
        if ($arResult['STAGE_ID'] === 'EXECUTING' && $arResult["CATEGORY_ID"] === 0)
        {
            $request = \ITS\Personal\Modules\Requests::getByDealId($arResult["ID"]);

            if ($request)
                \CIBlockElement::SetPropertyValueCode($request->getId(), "STATUS", 66);
        }

        // if deal go to start addiction
        if ($arResult['STAGE_ID'] === 'UC_YR67HT' && $arResult["CATEGORY_ID"] === 0)
        {
            $request = \ITS\Personal\Modules\Requests::getByDealId($arResult["ID"]);

            if ($request)
                \CIBlockElement::SetPropertyValueCode($request->getId(), "STATUS", 64);
        }

        // if deal go to end addiction
        if (($arResult['STAGE_ID'] === 'UC_1BQ03M' || $arResult['STAGE_ID'] === 'WON') && $arResult["CATEGORY_ID"] === 0)
        {
            $request = \ITS\Personal\Modules\Requests::getByDealId($arResult["ID"]);

            if ($request)
                \CIBlockElement::SetPropertyValueCode($request->getId(), "STATUS", 67);
        }

        // if deal go to end addiction
        if ( ($arResult['STAGE_ID'] === 'APOLOGY' || $arResult['STAGE_ID'] === 'LOSE' ) && $arResult["CATEGORY_ID"] === 0)
        {
            $request = \ITS\Personal\Modules\Requests::getByDealId($arResult["ID"]);

            if ($request)
                \CIBlockElement::SetPropertyValueCode($request->getId(), "STATUS", 65);
        }

    }
    
//    public static function onEntityDetailsTabsInitialized(Event $event): EventResult
//    {
//        $intEntityTypeID = $event->getParameter( 'entityTypeID' );
//        $intId = $event->getParameter( 'entityID' );
//        $arTabs = $event->getParameter( 'tabs' );
//
//        if ( $intEntityTypeID === \CCrmOwnerType::Deal )
//        {
//            $arTabs = array_merge(
//                array(
//                    array(
//                        'id' => 'payments_tab_control_demo_list',
//                        'name' => 'Подвал',
//                        'loader' => array(
//                            'serviceUrl' => '/testing/?IFRAME=Y&TAB=Y&DEAL_ID=' . $intId,
//                            'componentData' =>
//                                array(
//                                    'template' => '',
//                                    'params' => array(
//                                        'alias' => 'demo',
//                                        'name' => 'demo-list',
//                                    ),
//                                ),
//                        ),
//                    ),
//                ),
//                $arTabs
//            );
//        }
//
//        return new EventResult( EventResult::SUCCESS, [
//            'tabs' => $arTabs,
//        ] );
//
//    }
}