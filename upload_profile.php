<?php

session_start();
require "db.php";
require "cloudinary.php";

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id'];

if(isset($_FILES['profile_pic']['tmp_name']) && $_FILES['profile_pic']['tmp_name'] != ""){

    $url = uploadImage($_FILES['profile_pic']['tmp_name']);

    $stmt = $db->prepare("UPDATE users SET profile_pic=? WHERE id=?");
    $stmt->execute([$url, $id]);
}

header("Location: profile.php");
exit;
?>
