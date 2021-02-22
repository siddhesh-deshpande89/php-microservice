<?php
namespace App\Helpers;

class Config
{

    /**
     * Returns config value based on key
     * 
     * @param string $key
     * @return mixed
     */
    public static function get(string $key)
    {
        $config = require dirname(__DIR__) . '/../config/app.php';

        return $config[$key];
    }
}