<?php

if (PHP_VERSION_ID >= 70400)
{
    \Bitrix\Main\Loader::includeModule('main');

    $core = require 'lib/core.php';
    $core::Load();
}