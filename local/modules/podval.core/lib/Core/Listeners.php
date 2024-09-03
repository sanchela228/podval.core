<?php
namespace Podval\Core;

class Listeners
{
    const LISTENERS_PATH = "/local/modules/podval.core/lib/listeners/";
    final public static function initEventWatchers(): void
    {
        $files = scandir( $_SERVER["DOCUMENT_ROOT"] . self::LISTENERS_PATH );

        foreach ($files as $file)
        {
            if ($file == "." or $file == "..") continue;

            if ( file_exists($_SERVER["DOCUMENT_ROOT"] . self::LISTENERS_PATH . $file ) )
            {
                $className = str_replace(".php", "", $file);
                $strListenerClassName = "\\Podval\\Listeners\\" . $className;

                if ( !class_exists($strListenerClassName) or !\Bitrix\Main\Loader::includeModule('podval.core') )
                    continue;

                $obListener = new $strListenerClassName();

                if ($obListener instanceof \Podval\Core\Interfaces\Listener)
                {
                    try
                    {
                        $arMethods = get_class_methods( $obListener );

                        foreach ( $arMethods as $strEventName )
                        {
                            \Bitrix\Main\EventManager::getInstance()->addEventHandler(
                                strtolower($className), $strEventName, array(
                                    get_class($obListener),
                                    $strEventName
                                )
                            );
                        }
                    }
                    catch ( BadMethodCallException $e ) {}
                }

            }
        }

    }
}