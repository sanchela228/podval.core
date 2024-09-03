<?php
namespace Podval\Core\Database;

enum DatabaseField
{
    case Integer;
    case Text;
    case Datetime;
}

class Field
{
    protected string $name;
    protected array $params;
    protected DatabaseField $type;

    public function __construct(DatabaseField $field, string $nameTable, array $params = array())
    {
        $this->type = $field;
        $this->name = $nameTable;
        $this->param = $params;
    }

    public function getName()
    {
        return $this->name;
    }

    public static function getBitrixDefaultFieldId()
    {
        return new \Bitrix\Main\Entity\IntegerField( "id", array(
            "primary" => true,
            "autocomplete" => true,
        ));
    }

    public function getBitrixField()
    {
        switch ( $this->type )
        {
            case DatabaseField::Integer:
                return new \Bitrix\Main\Entity\IntegerField( $this->name, $this->param );
            case DatabaseField::Text:
                return new \Bitrix\Main\Entity\TextField( $this->name, $this->param );
            case DatabaseField::Datetime:
                return new \Bitrix\Main\Entity\DatetimeField( $this->name, $this->param );

        }
    }
}