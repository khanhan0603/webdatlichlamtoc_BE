<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;

$s3 = new S3Client([
    'version' => 'latest',
    'region' => 'auto',
    'endpoint' => 'https://973511ab69b286deef999022bb921863.r2.cloudflarestorage.com',
    'credentials' => [
        'key' => 'cc15758aab6f92e8dc3ebfce23896b6a',
        'secret' => '8afca860ec2600ea95603f0e66961c44e32d3a23b4faff510011b28c85e328e3',
    ],
]);

try {
    $result = $s3->putObject([
        'Bucket' => 'quanlylamtoc',
        'Key' => 'test.txt',
        'Body' => 'hello world',
    ]);

    var_dump($result);
} catch (Exception $e) {
    echo $e->getMessage();
}