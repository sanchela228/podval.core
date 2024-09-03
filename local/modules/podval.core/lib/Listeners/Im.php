<?php
namespace Podval\Listeners;

use Podval\Core\Logger;

class Im implements \Podval\Core\Interfaces\Listener
{
    public static function OnAfterMessagesAdd(int $messageId)
    {
        file_put_contents(
            $_SERVER["DOCUMENT_ROOT"] . "/tessssssst.log",
            [$messageId],
        );

    }
}