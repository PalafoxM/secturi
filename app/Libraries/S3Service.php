<?php

namespace App\Libraries;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;

class S3Service
{
    protected $client;
    protected $bucket;

    public function __construct()
    {
        $config = [
            'version' => 'latest',
            'region' => getenv('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key' => getenv('AWS_ACCESS_KEY_ID'),
                'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
            ],
            'suppress_php_deprecation_warning' => true,
            'http' => [
                'verify' => false,
            ],
        ];

        $endpoint = getenv('AWS_ENDPOINT');
        if (!empty($endpoint)) {
            $config['endpoint'] = $endpoint;
            $config['use_path_style_endpoint'] = filter_var(
                getenv('AWS_USE_PATH_STYLE_ENDPOINT') ?: true,
                FILTER_VALIDATE_BOOLEAN
            );
        }

        $this->client = new S3Client($config);
        $this->bucket = getenv('AWS_BUCKET');
    }

    public function uploadFile($sourceFile, $keyName)
    {
        try {
            $result = $this->client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $keyName,
                'SourceFile' => $sourceFile,
            ]);

            return $result->get('ObjectURL');
        } catch (AwsException $e) {
            log_message('error', 'Error al subir a S3: ' . $e->getMessage());
            return false;
        }
    }

    public function folderExists($folderName)
    {
        $folderKey = trim($folderName, '/') . '/';

        try {
            $result = $this->client->listObjectsV2([
                'Bucket' => $this->bucket,
                'Prefix' => $folderKey,
                'MaxKeys' => 1,
            ]);

            return $result->get('KeyCount') > 0;
        } catch (AwsException $e) {
            log_message('error', 'Error al validar carpeta en S3: ' . $e->getMessage());
            return false;
        }
    }

    public function createFolder($folderName)
    {
        $folderKey = trim($folderName, '/') . '/';

        try {
            $this->client->putObject([
                'Bucket' => $this->bucket,
                'Key' => $folderKey,
                'Body' => '',
            ]);

            return true;
        } catch (AwsException $e) {
            log_message('error', 'Error al crear carpeta en S3: ' . $e->getMessage());
            return false;
        }
    }

    public function getFileUrl($keyName)
    {
        return $this->client->getObjectUrl($this->bucket, $keyName);
    }

    public function getPresignedUrl($keyName, $expires = '+20 minutes')
    {
        try {
            $cmd = $this->client->getCommand('GetObject', [
                'Bucket' => $this->bucket,
                'Key' => $keyName
            ]);

            $request = $this->client->createPresignedRequest($cmd, $expires);
            return (string) $request->getUri();
        } catch (AwsException $e) {
            log_message('error', 'Error generando presigned URL en S3: ' . $e->getMessage());
            return false;
        }
    }

    public function downloadToTempFile($keyName, $prefix = 's3_')
    {
        try {
            $extension = pathinfo($keyName, PATHINFO_EXTENSION);
            $tempFile = tempnam(sys_get_temp_dir(), $prefix);

            if ($extension) {
                $renamedTempFile = $tempFile . '.' . $extension;
                rename($tempFile, $renamedTempFile);
                $tempFile = $renamedTempFile;
            }

            $this->client->getObject([
                'Bucket' => $this->bucket,
                'Key' => $keyName,
                'SaveAs' => $tempFile,
            ]);

            return $tempFile;
        } catch (AwsException $e) {
            log_message('error', 'Error descargando archivo temporal desde S3: ' . $e->getMessage());
            return false;
        }
    }
}
