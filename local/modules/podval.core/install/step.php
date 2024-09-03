<?if(!check_bitrix_sessid()) return;?>
<?
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

echo CAdminMessage::ShowNote("Модуль ".Loc::getMessage("CORE_MODULE")." установлен");
?>