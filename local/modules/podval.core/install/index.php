<?

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

class podval_core extends CModule
{
    public function __construct() {

        if (file_exists(__DIR__ . "/version.php"))
        {
            $arModuleVersion = array();

            include_once(__DIR__ . "/version.php");

            $this->MODULE_ID = str_replace("_", ".", get_class($this));
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
            $this->MODULE_NAME = Loc::getMessage("CORE_MODULE");
            $this->MODULE_DESCRIPTION = Loc::getMessage("CORE_DESCRIPTION");
            $this->PARTNER_NAME = Loc::getMessage("CORE_PARTNER_NAME");
            $this->PARTNER_URI = Loc::getMessage("CORE_PARTNER_URI");
        }

        return false;
    }

    public function DoInstall() {
        global $DB, $APPLICATION, $step;

        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallEvents();

        $APPLICATION->IncludeAdminFile(Loc::getMessage("CORE_DESCRIPTION"),
            $_SERVER["DOCUMENT_ROOT"] . "/local/modules/podval.core/install/step.php");
        return false;
    }

    public function DoUninstall() {
        global $APPLICATION;

        $this->UnInstallEvents();

        ModuleManager::unRegisterModule($this->MODULE_ID);

        $APPLICATION->IncludeAdminFile(
            Loc::getMessage("FALBAR_TOTOP_UNINSTALL_TITLE") . " \"" . Loc::getMessage("CORE_NAME") . "\"",
            __DIR__ . "/unstep.php"
        );

        return false;
    }

    public function InstallFiles() {
        return false;
    }

    public function UnInstallFiles(){
        return false;
    }

    public function InstallDB() {

        return false;
    }

    public function InstallEvents() {

    }

}