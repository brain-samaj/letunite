<?php

require __DIR__ . '/vendor/autoload.php';

use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

/* CONFIGURE CLOUDINARY */
Configuration::instance([
    'cloud' => [
        'cloud_name' => 'djngtqjs8',
        'api_key' => '192215628574648',
        'api_secret' => '28DaLWm5wjH3EQ5cvYYZscSHh5I',
    ],
    'url' => [
        'secure' => true
    ]
]);

/* UPLOAD FUNCTION */
function uploadImage($tmpFile) {
    $result = (new UploadApi())->upload($tmpFile, [
        "folder" => "letunite"
    ]);

    return $result['secure_url'];
}

?>
