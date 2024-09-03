<?php
namespace Podval\Core;

use Bitrix\Main\Routing\RoutingConfigurator;

class Router extends \Podval\Core\Classes\Singleton
{
    public function getPathsToPages() : array
    {
        $pages = [];
        Page::getPagesRecursive( Module::getInstance()->pathToPages(), $pages );

        return $pages;
    }

    public function setRoutes( RoutingConfigurator &$routes ) : void
    {
        $settings = Module::getInstance()->settings;

        # set pages to $routes
        foreach ($this->getPathsToPages() as $path)
        {
            $route = str_replace(
                [ Module::getInstance()->pathToPages(), 'index.php' ],
                '',
                "/" . $path
            );

            $routes->any($route, function () use($route)
            {
                $page = new \Podval\Core\Page($route);

                if ( $page->isPage() ) $page->showContent();
            });
        }

        # other routes settings

        if ($settings["modules"]["core_migrations"])
        {
            $routes->any( '/podval/migrations/{method_name}/{model_name}', function ($method_name, $model_name)
            {
                \Podval\Core\Database\Migrations::call($method_name, $model_name);
            });
        }

        if ($settings["modules"]["core_api"])
        {
            $routes->any( '/api/podval/{api_path}', function ($api_path)
            {
                \Podval\Core\Api::call($api_path);
            });
        }
    }
}