<?php

namespace App;

class Filesystem
{

    public static function createDir(string $dir): void
    {
        if (!is_dir($dir)) {
            mkdir($dir);
        }
    }

    public static function writeFile(string $filename, string $content): void
    {
        if (!file_exists($filename)) {
            touch($filename);
        }

        if (is_writable($filename)) {


            if (!$fp = fopen($filename, 'a')) {
                Log::error("Cannot open file ($filename)");
                exit;
            }

            // Write $content to our opened file.
            if (fwrite($fp, $content) === FALSE) {
                Log::error("Cannot write to file ($filename)");
                exit;
            }

            fclose($fp);
        } else {
            Log::error("The file $filename is not writable");
        }
    }

    public static function readDir(string $dir): array
    {
        $dir_content = [];
        if ($handle = opendir($dir)) {

            while (false !== ($entry = readdir($handle))) {
                if ($entry != '.' && $entry != '..') {
                    array_push($dir_content, $entry);
                }
            }

            closedir($handle);
        }

        return $dir_content;
    }

    public static function databaseFiles(array $files, string $database): array
    {
        $files = array_filter($files, fn ($file) => str_starts_with($file, $database));
        rsort($files);
        return $files;
    }

    public static function deleteFile(string $file): void
    {
        Log::info('Deleting file: ' . $file);
        unlink($file);
    }
}
