<?php
namespace Library;

abstract class FormatterFactory
{
    public static function create($format)
    {
        $name = '\\Library\\OutputFormatter\\' . ucfirst($format) . 'Formatter';
        return new $name();
    }
}