<?php

namespace App;

class Log
{

    public static function line(string $message): void
    {
        echo $message . "\n";
        self::logToFile($message);
    }

    public static function info(string $message): void
    {
        echo "\033[34m" . $message . "\033[0m\n";
        self::logToFile($message);
    }

    public static function success(string $message): void
    {
        echo "\033[32m" . $message . "\033[0m\n";
        self::logToFile($message);
    }

    public static function error(string $message): void
    {
        echo "\033[31m" . $message . "\033[0m\n";
        self::logToFile($message);
    }

    protected static function logToFile($message): void
    {
        $log_path = config('app.log.path');
        Filesystem::createDir($log_path);
        Filesystem::writeFile($log_path . '/mysql-backup.log', $message . "\n");
    }
}
