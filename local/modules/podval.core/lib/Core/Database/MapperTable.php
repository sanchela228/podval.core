<?php
namespace Podval\Core\Database;

use \Podval\Core\Classes\Model;
use \Podval\Core\Logger;

/**
 * Класс обертка над битрикс DataManager для работы с ORM через модели.
 */
class MapperTable extends \Bitrix\Main\ORM\Data\DataManager
{
    // region functional_implementation
    protected static Model $model;
    protected static $tableName;

    public function __construct(Model $model)
    {
        static::$model = $model;

        static::$tableName = static::$model->getTableName();
    }

    public static function getTableName(): string
    {
        return static::$tableName;
    }

    public function setTableName(string $name) : void
    {
        static::$tableName = $name;
    }

    public static function getMap(): array
    {
        $arrFields = [ \Podval\Core\Database\Field::getBitrixDefaultFieldId() ];

        foreach (static::$model->getArrayFields() as $field)
        {
            if ($field instanceof \Podval\Core\Database\Field)
                $arrFields[] = $field->getBitrixField();
        }

        return $arrFields;
    }

    public static function rebuildMapToDatabase(array $map) : array
    {
        $arr = array();
        foreach ($map as $element)
            $arr[$element->getName()] = $element;

        return $arr;
    }

    protected function arrToModel(array $arr, bool $isNewObject = false)
    {
        $class = static::$model::class;
        $model = new $class();

        foreach ($arr as $key => $value)
        {
            if ($key == "id")
            {
                $id = (int) $value;
                $model->setId( $id );
                continue;
            }

            if ( $model->isFieldInModel($key) )
                $model->$key = $value;
        }

        return $model;
    }

    // endregion
    
    public function findElement(int $id)
    {
        $arrResult = parent::getById($id)->fetch();

        //todo: add try and error handler

        $model = $this->arrToModel( $arrResult );

        return $model;
    }
    public function addElement(Model $model)
    {
        $arToUpdate = array();
        foreach ($model->getArrayFields() as $field)
        {
            $name = $field->getName();
            $arToUpdate[$name] = $model->$name;
        }

        //todo: add try and error handler

        $result = parent::add($arToUpdate);
    }
    public function addElements(array $arrModels)
    {
        $arToUpdate = array();

        foreach ($arrModels as $model)
        {
            if ($model instanceof Model)
            {
                $arModelFields = array();
                foreach ($model->getArrayFields() as $field)
                {
                    $name = $field->getName();
                    $arModelFields[$name] = $model->$name;
                }
            }

            $arToUpdate[] = $arModelFields;
        }

        parent::addMulti($arToUpdate);
    }
    public function updateElement(Model $model)
    {
        $arToUpdate = array();
        foreach ($model->getArrayFields() as $field)
        {
            $name = $field->getName();
            $arToUpdate[$name] = $model->$name;
        }

        //todo: add try and error handler

        $result = parent::update($model->id, $arToUpdate);
    }
    public function removeElement(int $id)
    {
        //todo: add try and error handler

        $result = parent::delete($id);
    }

    public function getListElements(array $parameters) : array
    {
        $models = array();
        $rows = parent::getList($parameters)->fetchAll();

        if ($rows)
        {
            foreach ($rows as $row ) $models[] = $this->arrToModel( $row, true );
        }

       return $models;
    }
}