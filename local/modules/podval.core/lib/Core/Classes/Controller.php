<?php
namespace Podval\Core\Classes;

abstract class Controller
{
    abstract public function entrance(array $params = null);
}