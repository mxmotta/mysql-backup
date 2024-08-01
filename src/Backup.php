<?php

namespace App;

use App\Filesystem;
use App\Log;
use Ifsnop\Mysqldump\Mysqldump;
use App\AwsClient;

class Backup
{

    /**
     * Run the backup
     * @return void
     */
    public static function run(): void
    {
        try {

            Log::info("Starting backup routine");

            $backup_dir = config('app.backup.dir');
            Filesystem::createDir($backup_dir);

            $databases = explode(',', config('database.db'));
            $s3 = new AwsClient();

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
                    
                    $iterator = $s3->listBucketObjects($backup_dir, $database);
                    if($iterator['KeyCount'] > config('app.backup.max_files')){
                        Log::info('Cleaning S3 Bucket oldest files');
                        foreach (array_splice($iterator['Contents'], config('app.backup.max_files')) as $file) {
                            $s3->deleteObject($file['Key']);
                        }
                    }

                }
                
                $database_files = Filesystem::databaseFiles(Filesystem::readDir($backup_dir), $database);
                if (count($database_files) > config('app.backup.max_files')) {
                    Log::info('Cleaning local oldest files');
                    foreach (array_splice($database_files, config('app.backup.max_files')) as $file) {
                        Filesystem::deleteFile($backup_dir . '/' . $file);
                    }
                }

                Log::success('Done');
            }
            Log::success('Backup routine finished at ' . now()->format('Y-m-d H:i:s'));
        } catch (\Exception $e) {
            Log::error('mysqldump-php error: ' . $e->getMessage());
        }
    }
}
