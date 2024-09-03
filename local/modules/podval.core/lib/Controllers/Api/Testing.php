<?php
namespace Podval\Controllers\Api;

class Testing extends \Podval\Core\Classes\Api
{

    public static function entrance(array $params = null)
    {
        \Podval\Core\Logger::print($params);
    }
}