<?php

namespace App;

use Aws\EndpointDiscovery\EndpointList;
use Aws\EndpointV2\EndpointProviderV2;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

class AwsClient
{

    private ?S3Client $client;

    public function init(): AwsClient
    {
        // Instantiate an Amazon S3 client.
        
        $this->client = new S3Client([
            'version' => config('aws.version'),
            'region'  => config('aws.region'),
            'endpoint'=> config('aws.endpoint'),
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key'    => config('aws.key'),
                'secret' => config('aws.secret')
            ]
        ]);

        return $this;
    }

    public function upload(string $file): void
    {
        try {
            $this->client->putObject([
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
}
