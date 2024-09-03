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
    // маршруты
    $routes->any( '/any_page_path/{id}', function ( $id )
    {
        Loader::includeModule( 'podval.core' );

        $page = new \Podval\Core\Page(
            "/any_page_path/",
            ["id" => $id]
        );

        if ( $page->isPage() ) $page->showContent();

        require $_SERVER[ 'DOCUMENT_ROOT' ] . '/bitrix/urlrewrite.php';
    } )->methods( [ 'GET', 'POST', 'OPTIONS' ] );
};