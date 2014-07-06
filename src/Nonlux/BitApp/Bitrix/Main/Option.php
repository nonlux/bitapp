<?php
namespace Nonlux\BitApp\Bitrix\Main;

use Bitrix\Main\Config\Option as BaseOption;

class Option extends BaseOption
{

    public static function getOptions($moduleId)
    {
        return static::$options["-"][$moduleId];
    }

    public static function  clearOptions($moduleId, $siteId = '-')
    {
        unset (static::$options[$siteId][$moduleId]);
        static::$cacheTtl = false;
    }
} 