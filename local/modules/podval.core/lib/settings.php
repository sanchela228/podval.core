<?php

return array(

    /**
        init modules settings
        set "module_name" => true|false, for init in hit
     */

    "modules" => [
        "core_listeners" => true,
        "core_migrations" => true,
        "core_api" => true
    ],

    /**
        bots settings..
        set "bot_code" => "NameClassBot" in Controllers/Bots/ without namespace

     */

    "bots" => [
        "e933qgx5q7v5mgpr" => "TestChatBot"
    ]
);