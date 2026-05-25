<?php
session_start();
require "db.php";

/* CHECK LOGIN */
if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$user = $_SESSION['id'];

/* VALIDATE POST ID */
if(!isset($_GET['id'])){
    header("Location: home.php");
    exit;
}

$post = $_GET['id'];

/* CHECK IF ALREADY LIKED */
$check = $db->prepare("
    SELECT id FROM likes 
    WHERE user_id=? AND post_id=?
");
$check->execute([$user, $post]);

if(!$check->fetch()){

    /* INSERT LIKE */
    $db->prepare("
        INSERT INTO likes(user_id, post_id)
        VALUES(?,?)
    ")->execute([$user, $post]);

    /* GET POST OWNER */
    $get = $db->prepare("
        SELECT user_id FROM posts WHERE id=?
    ");
    $get->execute([$post]);
    $owner = $get->fetchColumn();

    /* CREATE NOTIFICATION (IF NOT SELF LIKE) */
    if($owner && $owner != $user){

        $db->prepare("
            INSERT INTO notifications(user_id, actor_id, post_id, type)
            VALUES(?,?,?,'like')
        ")->execute([
            $owner,
            $user,
            $post
        ]);
    }
}

/* RETURN TO HOME */
header("Location: home.php");
exit;
?>
