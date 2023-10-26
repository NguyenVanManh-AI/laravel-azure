<?php

namespace App\Enums;

use ReflectionClass;

abstract class BaseEnum
{
    /**
     * @return array<mixed>
     */
    public static function getKeys(): array
    {
        return array_keys(static::getEntries());
    }

    /**
     * @return array<mixed>
     */
    public static function getValues(): array
    {
        return array_values(static::getEntries());
    }

    /**
     * @return array<mixed>
     */
    public static function getEntries(): array
    {
        $class = new ReflectionClass(get_called_class());

        return $class->getConstants();
    }
}
