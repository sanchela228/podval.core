<?php
namespace Podval\Core;

class Utils
{
    public static function SnakeCaseToCamel(string $string) : string
    {
        return str_replace("_", "", ucwords($string, " /_"));
    }

    public static function camelCaseToSnake(string $string) : string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    public static function NumToWord(int $num, array $words)
    {
        $num = $num % 100;
        if ($num > 19) $num = $num % 10;

        switch ($num)
        {
            case 1: return($words[0]);
            case 2: case 3: case 4:	return($words[1]);
            default: return($words[2]);
        }
    }

    public static function ExpireDate($duration, \DateTime $from = null)
    {
        if ($from != null)
            return \Bitrix\Main\Type\DateTime::createFromPhp($from->modify("+" . $duration["STRING"]));

        return \Bitrix\Main\Type\DateTime::createFromPhp(new \DateTime("+" . $duration["STRING"]));
    }

    public static function Uuid($data = null)
    {
        $data = $data ?? random_bytes(16);

        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

}