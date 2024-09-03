<?php

return new class
{
    public static function Load(): void
    {
        spl_autoload_register( function ( $strClassName )
        {
            if (str_contains($strClassName, 'Podval') and !str_contains($strClassName, 'views'))
            {
                $arClassName = explode("\\", $strClassName);
                $shortNameClass = array_pop($arClassName);

                $strClassPath = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/podval.core/lib';
                $strClassPath .= '/'. strtolower( implode('/', $arClassName) ). "/" . $shortNameClass . '.php';

                $strClassPath = str_replace('podval/', '', $strClassPath);
                
                include_once $strClassPath;
            }
        });

        \Podval\Core\Module::getInstance()->loadSettings();
        
        self::initModules( \Podval\Core\Module::getInstance()->settings["modules"] );
    }
    
    protected static function initModules(array $modulesList): void
    {
        foreach ($modulesList as $module => $active)
            if ($active)
            {
                switch ($module)
                {
                    case "core_listeners":
                        \Podval\Core\Listeners::initEventWatchers(); break;

                }
            }
    }
};

