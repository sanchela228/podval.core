<?php

# to /local/routes/main.php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Routing\RoutingConfigurator;

return function ( RoutingConfigurator $routes )
{
    Loader::includeModule( 'podval.core' );

    \Podval\Core\Router::getInstance()->setRoutes($routes);
};