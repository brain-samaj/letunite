<?php
session_start();
require "db.php";

/* LOGIN CHECK */
if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit;
}

$user = $_SESSION['id'];

/* =========================
   1. ADD COMMENT OR REPLY
========================= */
if(isset($_POST['comment'])){

    $post_id = $_POST['post_id'];
    $comment = trim($_POST['comment']);

    $parent_id = isset($_POST['parent_id']) ? $_POST['parent_id'] : 0;

    if($comment != ""){

        $stmt = $db->prepare("
            INSERT INTO comments(post_id, user_id, comment, parent_id)
            VALUES(?,?,?,?)
        ");

        $stmt->execute([
            $post_id,
            $user,
            $comment,
            $parent_id
        ]);
    }

    header("Location: home.php");
    exit;
}


/* =========================
   2. LIKE / UNLIKE COMMENT
========================= */
if(isset($_GET['like'])){

    $comment_id = $_GET['like'];

    // check if already liked
    $check = $db->prepare("
        SELECT id FROM comment_likes
        WHERE comment_id=? AND user_id=?
    ");
    $check->execute([$comment_id, $user]);

    if($check->fetch()){
        // unlike
        $db->prepare("
            DELETE FROM comment_likes
            WHERE comment_id=? AND user_id=?
        ")->execute([$comment_id, $user]);
    } else {
        // like
        $db->prepare("
            INSERT INTO comment_likes(comment_id, user_id)
            VALUES(?,?)
        ")->execute([$comment_id, $user]);
    }

    header("Location: home.php");
    exit;
}

?>
