<?php

namespace App;

class Log
{

    public static function line(string $message): void
    {
        echo $message . "\n";
    }

    public static function info(string $message): void
    {
        echo "\033[34m" . $message . "\033[0m\n";
    }

    public static function success(string $message): void
    {
        echo "\033[32m" . $message . "\033[0m\n";
    }

    public static function error(string $message): void
    {
        echo "\033[31m" . $message . "\033[0m\n";
    }
}
