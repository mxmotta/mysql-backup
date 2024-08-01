<?php

namespace App;

use Aws\Result;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

class AwsClient
{

    private ?S3Client $client;

    /**
     * Initialize AWS Client
     */
    public function __construct()
    {
        $this->client = new S3Client([
            'version' => config('aws.version'),
            'region'  => config('aws.region'),
            'endpoint' => config('aws.endpoint'),
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key'    => config('aws.key'),
                'secret' => config('aws.secret')
            ]
        ]);
    }

    /**
     * Upload file to AWS S3 Bucket
     * @param string $file
     * @return Aws\Result
     */
    public function upload(string $file): Result
    {
        try {
            return $this->client->putObject([
                'Bucket' => config('aws.bucket'),
                'Key'    => $file,
                'Body'   => fopen($file, 'r'),
                'ACL'    => config('aws.ACL'),
            ]);
        } catch (S3Exception $e) {
            Log::error("There was an error uploading the file.");
            Log::error($e->getMessage());
        }
    }

    /**
     * List AWS S3 Bucket
     * @param string $dir
     * @param string $name
     * @return Aws\Result
     */
    public function listBucketObjects(string $dir, string $name): Result
    {
        try {
            $contents = $this->client->listObjectsV2([
                'Bucket' => config('aws.bucket'),
                'Prefix' => "$dir/$name"
            ]);
            return $contents;
        } catch (S3Exception $e) {
            Log::error("There was an error listing the directory.");
            Log::error($e->getMessage());
        }
    }

    /**
     * Delete file from AWS S3 Bucket
     * @param string $file
     * @return Aws\Result
     */
    public function deleteObject(string $file): Result
    {
        try {
            Log::warning('Deleting file: ' . $file);
            $result = $this->client->deleteObject(array(
                'Bucket' => config('aws.bucket'),
                'Key'    => $file,
            ));
            return $result;
        } catch (S3Exception $e) {
            Log::error("There was an error deleting the directory.");
            Log::error($e->getMessage());
        }
    }
}
