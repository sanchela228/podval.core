<?php
namespace Podval\Listeners;

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Podval\Core\Logger;

class Crm implements \Podval\Core\Interfaces\Listener
{
    public static function onEntityDetailsTabsInitialized(Event $event): EventResult
    {
        $intEntityTypeID = $event->getParameter( 'entityTypeID' );
        $intId = $event->getParameter( 'entityID' );
        $arTabs = $event->getParameter( 'tabs' );

        if ( $intEntityTypeID === \CCrmOwnerType::Deal )
        {
            $arTabs = array_merge(
                array(
                    array(
                        'id' => 'payments_tab_control_demo_list',
                        'name' => 'Подвал',
                        'loader' => array(
                            'serviceUrl' => '/testing/?IFRAME=Y&TAB=Y&DEAL_ID=' . $intId,
                            'componentData' =>
                                array(
                                    'template' => '',
                                    'params' => array(
                                        'alias' => 'demo',
                                        'name' => 'demo-list',
                                    ),
                                ),
                        ),
                    ),
                ),
                $arTabs
            );
        }

        return new EventResult( EventResult::SUCCESS, [
            'tabs' => $arTabs,
        ] );

    }
}