<?php
session_start();
require "db.php";

if(!isset($_SESSION['id'])){
    header("Location:index.php");
    exit;
}

$sender = $_SESSION['id'];
$receiver = $_POST['receiver'];

$message = $_POST['message'] ?? "";

/* FILE UPLOAD HANDLER */
function uploadFile($field){
    if(!empty($_FILES[$field]['name'])){
        $name = time() . "_" . basename($_FILES[$field]['name']);
        move_uploaded_file($_FILES[$field]['tmp_name'], "uploads/" . $name);
        return $name;
    }
    return null;
}

$image = uploadFile("image");
$video = uploadFile("video");
$file  = uploadFile("file");
$audio = uploadFile("audio");

/* Prevent empty send */
if($message=="" && !$image && !$video && !$file && !$audio){
    exit("Empty message");
}

$db->prepare("
    INSERT INTO messages(sender, receiver, message, image, video, file, audio, seen)
    VALUES(?,?,?,?,?,?,?,0)
")->execute([
    $sender,
    $receiver,
    $message,
    $image,
    $video,
    $file,
    $audio
]);

header("Location: conversation.php?id=".$receiver);
exit;
?>
