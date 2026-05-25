<?php
session_start();
require "db.php";

/* CHECK LOGIN */
if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$user = $_SESSION['id'];

$content = isset($_POST['content']) ? trim($_POST['content']) : "";

/* DEFAULT IMAGE */
$image = "";

/* CREATE UPLOAD FOLDER IF NOT EXISTS */
if(!is_dir("uploads")){
    mkdir("uploads", 0777, true);
}

/* IMAGE UPLOAD */
if(!empty($_FILES['image']['name'])){

    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $file = time() . "_" . rand(1000,9999) . "." . $ext;

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        "uploads/" . $file
    );

    $image = $file;
}

/* INSERT POST */
$stmt = $db->prepare("
    INSERT INTO posts(user_id, content, image)
    VALUES(?,?,?)
");

$stmt->execute([
    $user,
    $content,
    $image
]);

header("Location: home.php");
exit;
?>
