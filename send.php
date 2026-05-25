<?php
session_start();
require "db.php";

if(!isset($_SESSION['id'])){
    exit;
}

$sender = $_SESSION['id'];
$receiver = $_POST['receiver'] ?? null;
$message = trim($_POST['message'] ?? "");

/* STOP IF NO RECEIVER */
if(!$receiver){
    exit;
}

/* -----------------------
   IMAGE UPLOAD
------------------------*/
$image = null;

if(!empty($_FILES['image']['name'])){
    $imgName = time() . "_" . basename($_FILES['image']['name']);
    $target = "uploads/" . $imgName;

    if(move_uploaded_file($_FILES['image']['tmp_name'], $target)){
        $image = $imgName;
    }
}

/* -----------------------
   VOICE UPLOAD
------------------------*/
$voice = null;

if(!empty($_FILES['voice']['name'])){
    $voiceName = time() . "_" . basename($_FILES['voice']['name']);
    $target = "uploads/" . $voiceName;

    if(move_uploaded_file($_FILES['voice']['tmp_name'], $target)){
        $voice = $voiceName;
    }
}

/* -----------------------
   SAVE MESSAGE
------------------------*/
$stmt = $db->prepare("
INSERT INTO messages (sender, receiver, message, image, voice)
VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([
    $sender,
    $receiver,
    $message,
    $image,
    $voice
]);

/* RETURN TO CHAT */
header("Location: conversation.php?user=" . $receiver);
exit;
