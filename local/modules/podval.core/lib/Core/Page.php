<?php
namespace Podval\Core;
use Bitrix\Main\Application;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\UI\Extension;
use \Podval\Core\Classes\Controller;

class Page
{
    const CONTROLLERS_PAGE_PATH = "/local/modules/podval.core/lib/controllers/pages";
    const VIEWS_PAGE_PATH = "/local/modules/podval.core/lib/views/pages/";
    public ?Controller $controller;
    public bool $isVue;
    public $obRequest;
    public array $page_files = [
        "index" => false,
        "style" => false,
        "script" => false,
        "vue" => false
    ];

    public array $params;

    public static function getPagesRecursive($path, &$arrResult)
    {
        $filesInn = scandir($path);
        $filesInn = array_diff($filesInn, ['..', '.']);
        if($filesInn != [])
        {
            foreach ($filesInn as $file)
            {
                if($file == 'index.php')
                    $arrResult[] = $path . $file;
                if (is_dir($path . $file))
                {
                    $newPath = $path . $file . '/';
                    static::getPagesRecursive($newPath, $arrResult);
                }
            }
        }
    }

    # find views page by route path
    public function __construct( string $route_path, array $params = null, bool $include_controller = true)
    {
        if ($params) $this->params = $params;

        $rootPathControllers = $_SERVER["DOCUMENT_ROOT"] . self::CONTROLLERS_PAGE_PATH;
        $rootPathPages = $_SERVER["DOCUMENT_ROOT"] . self::VIEWS_PAGE_PATH;

        foreach ( explode("/", $route_path) as $route )
            if ($route) $rootPathPages .= $route . "/";

        $this->page_files["index"] = $rootPathPages . "index.php";
        $this->page_files["style"] = $rootPathPages . "styles.css";
        $this->page_files["script"] = $rootPathPages . "scripts.js";

        if ( file_exists($rootPathPages . "vue.js") )
        {
            $this->page_files["vue"] = $rootPathPages . "vue.js";
            $this->isVue = true;
        }

        $this->obRequest = Application::getInstance()->getContext()->getRequest();

        $camel_route_path = Utils::SnakeCaseToCamel($route_path);

        $controllerFile = $rootPathControllers . $camel_route_path;
        if (str_ends_with($controllerFile, "/")) $controllerFile = substr($controllerFile, 0, -1);

        if ( file_exists($controllerFile . ".php") and $include_controller )
        {
            $controllerClassName = "\Podval\Controllers\Pages" . str_replace("/", "\\", $camel_route_path);

            if (str_ends_with($controllerClassName, "\\"))
                $controllerClassName = substr($controllerClassName, 0, -1);

            if ( class_exists($controllerClassName) )
                $this->controller = new $controllerClassName();
        }
    }

    public function isPage()
    {
        return $this->validateFoundContent();
    }

    protected function validateFoundContent() : bool
    {
        if ( file_exists($this->page_files["index"]) )
            return true;

        return false;
    }

    # load page
    public function showContent() : void
    {

        global $USER;
        if (!$USER->IsAuthorized()) LocalRedirect("/");

        if ( !$this->validateFoundContent() )
            throw new \Exception("not found content for page");

        if (isset($this->controller)) $this->controller->entrance();

        if ( isset($this->isVue) ) Extension::load( "ui.vue3" );

        $arQueryList = array_keys( $this->obRequest->getQueryList()->toArray() );
        $isIFrame = in_array( 'IFRAME', $arQueryList );

        \CHTTP::SetStatus("200 OK");
        @define("ERROR_404","N");
        
        // start render page

        ob_start();
        global $APPLICATION;

        if (!$isIFrame) require $_SERVER[ 'DOCUMENT_ROOT' ] . '/bitrix/header.php';
        else
        {
            require( $_SERVER[ "DOCUMENT_ROOT" ] . "/bitrix/modules/main/include/prolog_before.php" );
            require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_after.php");
        }
        
        \CJSCore::init( array( 'main', 'sidepanel', 'ajax' ) );

        Asset::getInstance()->addJs(str_replace($_SERVER[ "DOCUMENT_ROOT" ], "", $this->page_files["script"]));
        Asset::getInstance()->addCss(str_replace($_SERVER[ "DOCUMENT_ROOT" ], "", $this->page_files["style"]));

        if ( isset($this->isVue) )
            Asset::getInstance()->addJs(str_replace($_SERVER[ "DOCUMENT_ROOT" ], "", $this->page_files["vue"]));

        ?>
            <? if ( $isIFrame ): ?>
                <head>
                    <? $APPLICATION->ShowHead(); ?>
                </head>
                <body>
            <? endif; ?>
                <div id="podval-entrance-page">
                    <? include $this->page_files["index"] ?>
                </div>
            <?
                if (!$isIFrame) require_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/bitrix/footer.php';
                else
                {
                    require_once( $_SERVER[ "DOCUMENT_ROOT" ] . "/bitrix/modules/main/include/epilog_after.php" );
                }
            ?>
        
            <? if ( $isIFrame ): ?>
                <body>
            <? endif; ?>
        <?
        echo ob_get_clean();
    }

}