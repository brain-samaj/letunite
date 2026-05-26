<?php

session_start();
require "db.php";
require "cloudinary.php";

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id'];
$content = $_POST['content'];

$image_url = null;

if(isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != ""){
    $image_url = uploadImage($_FILES['image']['tmp_name']);
}

$stmt = $db->prepare("
    INSERT INTO posts(user_id, content, image, created)
    VALUES(?,?,?,datetime('now'))
");

$stmt->execute([$user_id, $content, $image_url]);

header("Location: home.php");
exit;
?>
