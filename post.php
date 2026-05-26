<?php

session_start();

require "db.php";
require "cloudinary.php";

if(!isset($_SESSION['id'])){
    header("Location:index.php");
    exit;
}

$user = $_SESSION['id'];

$content = trim($_POST['content'] ?? "");

$image = "";
$video = "";

/* IMAGE */

if(
isset($_FILES['image']) &&
$_FILES['image']['tmp_name'] != ""
){

$image = uploadImage(
$_FILES['image']['tmp_name']
);

}

/* VIDEO (MAX 60 SEC) */

if(
isset($_FILES['video']) &&
$_FILES['video']['tmp_name'] != ""
){

$seconds = 0;

if(
function_exists(
'ffprobe'
)
){

$seconds = 0;

}

/*
Temporary size check
(Adjust later if needed)
*/

if(
$_FILES['video']['size']
>
50*1024*1024
){

die(
"Video too large"
);

}

/* Upload video */

$result =
\Cloudinary\Uploader::upload(

$_FILES['video']['tmp_name'],

[
"resource_type"=>"video",
"folder"=>"letunite_videos"
]

);

$video =
$result['secure_url'];

}

$stmt = $db->prepare(

"

INSERT INTO posts(

user_id,
content,
image,
video

)

VALUES(

?,?,?,?

)

"

);

$stmt->execute([

$user,
$content,
$image,
$video

]);

header(
"Location:home.php"
);

exit;

?>
