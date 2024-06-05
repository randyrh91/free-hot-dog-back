<?php
namespace App\Util;

class Parameters
{
    public static function getParameter(string $name): string
    {
        return $_ENV[$name];
    }
}