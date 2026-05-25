<?php
session_start();
require "db.php";

if(!isset($_SESSION['id'])){
    exit("Not allowed");
}

$user = $_SESSION['id'];

$content = isset($_POST['content']) ? trim($_POST['content']) : "";

/* FILES */
$image = "";
$video = "";

/* =========================
   VALIDATION (IMPORTANT)
========================= */

if(
$content == "" &&
empty($_FILES['image']['name']) &&
empty($_FILES['video']['name'])
){
    exit("Post cannot be empty");
}

/* =========================
   IMAGE UPLOAD
========================= */

if(!empty($_FILES['image']['name'])){

$img = time()."_img_".basename($_FILES['image']['name']);

move_uploaded_file(
$_FILES['image']['tmp_name'],
"uploads/".$img
);

$image = $img;
}

/* =========================
   VIDEO UPLOAD
========================= */

if(!empty($_FILES['video']['name'])){

$vid = time()."_vid_".basename($_FILES['video']['name']);

move_uploaded_file(
$_FILES['video']['tmp_name'],
"uploads/".$vid
);

/* NOTE:
   True duration check needs FFmpeg (optional)
*/

$video = $vid;
}

/* =========================
   INSERT POST
========================= */

$stmt = $db->prepare("
INSERT INTO posts(user_id, content, image, video)
VALUES(?,?,?,?)
");

$stmt->execute([
$user,
$content,
$image,
$video
]);

header("Location: home.php");
exit;
