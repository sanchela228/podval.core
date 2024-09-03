<?php
namespace Podval\Core\Classes;

use \Podval\Core\Database\MapperTable as Mapper;
use Podval\Core\Logger;
use \Podval\Core\Utils;

abstract class Model
{
    // region functional_implementation_of_the_model

    protected static ?string $table;
    public readonly int $id;

    // array< \Podval\Core\Database\Field >
    abstract function getArrayFields() : array;

    public static function getShortName()
    {
        return (new \ReflectionClass(static::class))->getShortName();
    }

    public static function getMapper() : Mapper
    {
        $class = get_called_class();
        return new Mapper( new $class() );
    }

    public function getTableName() : string
    {
        if ( isset(static::$table) ) return static::$table;

        return "podval_" . Utils::camelCaseToSnake( (new \ReflectionClass($this))->getShortName() );
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function isFieldInModel(string $field) : bool
    {
        $fields = $this->getArrayFields();

        return in_array($field, $this->objectFieldsToArray($fields));
    }

    protected function objectFieldsToArray(array $fields) : array
    {
        $arr = array();
        foreach ($fields as $field)
        {
            if ($field instanceof \Podval\Core\Database\Field)
                $arr[] = $field->getName();
        }

        return $arr;
    }

    // endregion

    // region methods_with_mapper

    public static function find(int $id) : Model|false
    {
        return static::getMapper()->findElement($id);
    }

    public function save()
    {
        if ( isset($this->id) )
            return static::getMapper()->updateElement($this);
        else
            return static::getMapper()->addElement($this);
    }

    public function remove()
    {
        return static::getMapper()->removeElement( $this->id );
    }

    public static function delete(int $id)
    {
        return static::getMapper()->removeElement( $id );
    }

    public static function addMulti(array $arrModels)
    {
        return static::getMapper()->addElements( $arrModels );
    }

    public static function getList(array $parameters)
    {
        return static::getMapper()->getListElements( $parameters );
    }

    public static function where(string $column, mixed $needle)
    {
        return static::getMapper()->getListElements( [
            "filter" => [$column => $needle]
        ] );
    }

    // endregion

//    function __construct()
//    {
//        if ( self::$syncWithFields ) return $this;
//
//        foreach ( self::$fields as $fieldName )
//        {
//
//        }
//
//        self::$syncWithFields = true;
//        return $this;
//    }
}