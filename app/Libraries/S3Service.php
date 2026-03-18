<?php

namespace App\Libraries;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class S3Service
{
    protected $client;
    protected $bucket;

    public function __construct()
    {
        $this->client = new S3Client([
            'version'     => 'latest',
            'region'      => getenv('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => getenv('AWS_ACCESS_KEY_ID'),
                'secret' => getenv('AWS_SECRET_ACCESS_KEY'),
            ],
            'http'        => [
                'verify' => false // Desactiva la verificación SSL (Util para local WAMP)
            ]
        ]);
        $this->bucket = getenv('AWS_BUCKET');
    }

    /**
     * Sube un archivo al bucket de S3
     * 
     * @param string $sourceFile Ruta temporal o local del archivo a subir
     * @param string $keyName Nombre con el que se guardará en S3 (puede incluir carpetas ej. 'documentos/archivo.pdf')
     * @return string|false URL pública del archivo si tiene éxito, o false si falla.
     */
    public function uploadFile($sourceFile, $keyName)
    {
        try {
            $result = $this->client->putObject([
                'Bucket'     => $this->bucket,
                'Key'        => $keyName,
                'SourceFile' => $sourceFile,
                // Si deseas que los archivos sean públicos, descomenta la siguiente línea y asegúrate de que el bucket lo permita
                'ACL'        => 'public-read',
            ]);

            return $result->get('ObjectURL');
        } catch (AwsException $e) {
            var_dump($e->getMessage());
            log_message('error', 'Error al subir a S3: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtiene la URL de un archivo dentro del bucket
     * 
     * @param string $keyName Nombre del archivo en S3
     * @return string URL del archivo
     */
    public function getFileUrl($keyName)
    {
        return $this->client->getObjectUrl($this->bucket, $keyName);
    }

    /**
     * Obtiene una URL temporal (firmada) para descargar o ver un archivo privado
     * 
     * @param string $keyName Nombre del archivo en S3
     * @param string $expires Tiempo de expiración de la URL (ej: '+20 minutes')
     * @return string URL temporal
     */
    public function getPresignedUrl($keyName, $expires = '+20 minutes')
    {
        try {
            $cmd = $this->client->getCommand('GetObject', [
                'Bucket' => $this->bucket,
                'Key'    => $keyName
            ]);
            
            $request = $this->client->createPresignedRequest($cmd, $expires);
            return (string)$request->getUri();
        } catch (AwsException $e) {
            log_message('error', 'Error generando presigned URL en S3: ' . $e->getMessage());
            return false;
        }
    }
}
