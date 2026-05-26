<?php

require 'vendor/autoload.php';

use Cloudinary\Cloudinary;

$cloudinary = new Cloudinary([

'cloud'=>[

'cloud_name'=>$_ENV['CLOUDINARY_CLOUD_NAME'],

'api_key'=>$_ENV['CLOUDINARY_API_KEY'],

'api_secret'=>$_ENV['CLOUDINARY_API_SECRET']

],

'url'=>[
'secure'=>true
]

]);

function uploadImage($tmpFile){

global $cloudinary;

$result =
$cloudinary
->uploadApi()
->upload(

$tmpFile,

[
'folder'=>'letunite_images'
]

);

return $result['secure_url'];

}

function uploadVideo($tmpFile){

global $cloudinary;

$result =
$cloudinary
->uploadApi()
->upload(

$tmpFile,

[
'resource_type'=>'video',

'folder'=>'letunite_videos'
]

);

return $result['secure_url'];

}

?>
