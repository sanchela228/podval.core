<?php
namespace Podval\Models;
use Podval\Core\Database\Field;
use Podval\Core\Database\DatabaseField;

class Test extends \Podval\Core\Classes\Model
{
    protected static ?string $table = "podval_testing_table";

    public function getArrayFields() : array
    {
        return [
            new Field( DatabaseField::Text, "field1" ),
            new Field( DatabaseField::Text, "field2" )
        ];
    }
}