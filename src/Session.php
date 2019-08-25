<?php
namespace Softia\Challenge\CoffeeMachine;

class Session
{
    private static $data = [];

    public static function set($key, $value) {
        self::$data[$key] = $value;
    }

    public static function get($key) {
        return self::$data[$key];
    }
}