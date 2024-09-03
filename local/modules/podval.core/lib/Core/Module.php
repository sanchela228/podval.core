<?php
namespace Podval\Core;

class Module extends Classes\Singleton
{
    const module_root = '/local/modules/podval.core/lib/';
    public readonly array $settings;

    public function pathToPages()
    {
        return $_SERVER["DOCUMENT_ROOT"] . static::module_root . "views/pages/";
    }

    public function loadSettings()
    {
        $this->settings = require $_SERVER["DOCUMENT_ROOT"] . static::module_root . "/settings.php";
    }

}