<?php

use App\AwsClient;
use App\Log;
use Ifsnop\Mysqldump\Mysqldump;

require "vendor/autoload.php";
require "bootstrap.php";

try {

    Log::info("Starting backup routine");

    $backup_dir = config('app.backup.dir');
    if (!is_dir($backup_dir)) {
        mkdir($backup_dir);
    }

    $databases = explode(',', config('database.db'));
    $s3 = new AwsClient();

    if (config('app.backup.destiny') == 'aws') {
        $s3->init();
    }

    var_dump($database);

    foreach ($databases as $database) {
        Log::line("Backuping database: " . $database);
        $file = $backup_dir . '/' . $database . '-' . now()->format('Y-m-d-His') . '.sql';
        Log::line("Creating dump file: " . $file);
        $dump = new Mysqldump(
            "mysql:host=" . config('database.host') . ";dbname=" . $database,
            config('database.user'),
            config('database.password')
        );
        $dump->start($file);
        if (config('app.backup.destiny') == 'aws') {
            Log::line("Starting upload");
            $s3->upload($file);
            Log::success('Upload finished');
            Log::line('Deleting file');
            unset($file);
        }
        Log::success('Done');
    }
    Log::success('Backup routine finished at ' . now()->format('Y-m-d H:i:s'));
} catch (\Exception $e) {
    Log::error('mysqldump-php error: ' . $e->getMessage());
}
