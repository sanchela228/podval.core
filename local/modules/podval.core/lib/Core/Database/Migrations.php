<?php
namespace Podval\Core\Database;

use Bitrix\Main\Application;
use Podval\Core\Classes\Model;
use Podval\Core\Database\MapperTable;
use Podval\Core\Logger;

class Migrations
{

    public static function call(string $method, string $model)
    {
        $modelClassName = "\\Podval\\Models\\" . ucfirst($model);

        if ( class_exists($modelClassName) and method_exists(static::class, $method) )
            return static::$method( new $modelClassName() );
        else
        {
            throw new \Exception("not found method or disable migrations");
        }

        return false;
    }

    public static function drop(Model $model)
    {
        $connection = Application::getInstance()->getConnection();

        try
        {
            $connection->dropTable($model->getTableName());
        }
        catch( \Exception $exception )
        {
            throw new \Exception($exception->getMessage());
        }

        return true;
    }

    public static function make(Model $model) : bool
    {
        // make migrations
        $connection = Application::getInstance()->getConnection();
        $mapper = $model::getMapper();

        if ( !$connection->isTableExists($model->getTableName()) )
        {
            try
            {
                $connection->createTable(
                    $model->getTableName(),
                    $mapper::rebuildMapToDatabase( $mapper::getMap() ),
                    ['id'],
                    ['id']
                );
            }
            catch( \Exception $exception )
            {
                throw new \Exception($exception->getMessage());
            }
        }
        else throw new \Exception("table exists");

        return true;
    }
}