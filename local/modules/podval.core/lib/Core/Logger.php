<?php
namespace Podval\Core;

class Logger
{
    public static function debug($log, $path = false, bool $append = false)
    {
        $defaultName = "imgenerated.log";
        $root = $_SERVER["DOCUMENT_ROOT"] . "/local/modules/podval.core/lib/logs/";
        $append ? $append = FILE_APPEND : $append = 0;

        if ($path)
        {
            if (is_string($path))
            {
                $put = "";
                if (strpos($path, "/") !== false)
                {
                    $filename = substr( strrchr($path, "/"), 1 );
                    $put = str_replace($filename, "", $path);
                }
                else $filename = $path;
            }

            # special for log in classes
            if (is_array($path) and class_exists($path[0]) and is_string($path[1]))
            {
                $class = str_replace('\\', '/', $path[0]);
                if (strpos($class, "Podval/") !== false)
                    $class = str_replace("Podval/", "", $class);

                $put = $class;
            }

            $put = mb_strtolower($put);
            $filePath = $put;

            @self::validateFolders($filePath, $root);
        }
        else $filePath = $defaultName;

        $root .= $filePath;
        if ($path[1] and is_array($path)) $root .= "/" . $path[1];
        if ($filename) $root .= "/" . $filename;

        file_put_contents($root, print_r($log, true), $append);
    }

    protected static function validateFolders(string $path, string $root)
    {
        mkdir($root . $path, 0771, true);
    }

    public static function print($string)
    {
        echo "<pre>"; print_r($string); echo "</pre>";
    }
}