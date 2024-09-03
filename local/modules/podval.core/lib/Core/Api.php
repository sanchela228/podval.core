<?php
namespace Podval\Core;

class Api
{
    public static function call(string $apiPath)
    {
        $namespace = "\\Podval\\Controllers\\Api";

        $arExplode = explode("/", explode("?", $apiPath)[0]);
        foreach ($arExplode as $path)
            $namespace .= "\\" . ucfirst($path);

        if (class_exists($namespace))
            $namespace::entrance( $_REQUEST );
        else
            throw new \Exception("not found api method");
    }
}