<?php

# to /local/routes/main.php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Routing\RoutingConfigurator;
use PAD\Core\Actions\Page;
use PAD\Core\Interfaces\Action;
use PAD\Core\Tools\Alias;
use PAD\Core\Tools\ContainerPath;
use PAD\Core\Values\Page\Js;
use PAD\Core\Views;

return function ( RoutingConfigurator $routes )
{
    Loader::includeModule( 'podval.core' );

    \Podval\Core\Router::getInstance()->setRoutes($routes);
};