<?php

session_start();

require "db.php";

require "cloudinary.php";

if(!isset($_SESSION['id'])){

header("Location:index.php");

exit;

}

$user =
$_SESSION['id'];

$content =
trim(
$_POST['content']
?? ""
);

$image="";

$video="";

/* IMAGE */

if(

isset($_FILES['image'])

&&

$_FILES['image']['tmp_name']

!=""

){

$image=

uploadImage(

$_FILES['image']['tmp_name']

);

}

/* VIDEO */

if(

isset($_FILES['video'])

&&

$_FILES['video']['tmp_name']

!=""

){

/* 60MB LIMIT */

if(

$_FILES['video']['size']

>

60*1024*1024

){

die(

"Video too large"

);

}

$video=

uploadVideo(

$_FILES['video']['tmp_name']

);

}

/* REQUIRE CONTENT */

if(

$content==""

&&

$image==""

&&

$video==""

){

header(

"Location:home.php"

);

exit;

}

$stmt=

$db->prepare(

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
