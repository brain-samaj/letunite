<?php
session_start();

if(!isset($_SESSION['id'])){
    header("Location: index.php");
    exit;
}

require "db.php";

/* UPDATE LAST SEEN */
$db->prepare("
    UPDATE users
    SET last_seen=?
    WHERE id=?
")->execute([
    time(),
    $_SESSION['id']
]);

/* FETCH POSTS */
$posts = $db->query("
    SELECT
        posts.*,
        users.name,
        users.profile_pic
    FROM posts
    JOIN users ON users.id = posts.user_id
    ORDER BY posts.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>LETUNITE</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="home-body">

<!-- ================= TOP BAR ================= -->
<div class="top">
    <div class="logo">LETUNITE</div>
    <a href="logout.php">Logout</a>
</div>

<!-- ================= MENU ================= -->
<div class="menu">
    <a href="home.php">🏠 Home</a>
    <a href="profile.php">👤 Profile</a>
    <a href="connect.php">🤝 Connect</a>
    <a href="chat.php">💬 Chat</a>
</div>

<!-- ================= CREATE POST ================= -->
<div class="create-post">
    <form action="post.php" method="POST" enctype="multipart/form-data">
        <textarea name="content" placeholder="What's on your mind?" required></textarea>
        <input type="file" name="image">
        <button type="submit">Post</button>
    </form>
</div>

<!-- ================= FEED ================= -->
<div class="feed">

<?php foreach($posts as $p): ?>

    <div class="post-card">

        <!-- USER INFO -->
        <div class="post-header">
            <img src="<?= $p['profile_pic'] ? 'uploads/'.$p['profile_pic'] : 'assets/default.png' ?>">
            <div>
                <b><?= htmlspecialchars($p['name']) ?></b>
                <small>Now</small>
            </div>
        </div>

        <!-- POST CONTENT -->
        <div class="post-body">
            <?= nl2br(htmlspecialchars($p['content'])) ?>
        </div>
<!-- LIKE COUNT -->
<?php
$like = $db->prepare("SELECT COUNT(*) FROM likes WHERE post_id=?");
$like->execute([$p['id']]);
$likes = $like->fetchColumn();
?>

<!-- COMMENT COUNT -->
<?php
$com = $db->prepare("
SELECT COUNT(*)
FROM comments
WHERE post_id=?
");

$com->execute([
$p['id']
]);

$comments = $com->fetchColumn();
?>
<!-- SHARE COUNT -->
<?php
$sh = $db->prepare("SELECT COUNT(*) FROM shares WHERE post_id=?");
$sh->execute([$p['id']]);
$shares = $sh->fetchColumn();
?>

<!-- ACTION BUTTONS -->
<!-- ACTION BUTTONS -->
<div class="post-actions">

    <a href="like.php?id=<?= $p['id'] ?>">
        👍 Like (<?= $likes ?>)
    </a>

    <a href="#comments<?= $p['id'] ?>">
        💬 Comment (<?= $comments ?>)
    </a>

    <a href="share.php?id=<?= $p['id'] ?>">
        🔁 Share (<?= $shares ?>)
    </a>

</div>

<!-- COMMENT FORM -->
<form action="comment.php" method="POST" class="comment-form">

    <input type="hidden" name="post_id" value="<?= $p['id'] ?>">
    <input type="hidden" name="parent_id" value="0">

    <input type="text" name="comment" placeholder="Write a comment..." required>

    <button type="submit">Comment</button>

</form>
<?php

$comments=$db->prepare("
SELECT
comments.*,
users.name
FROM comments
JOIN users
ON users.id=comments.user_id
WHERE
post_id=?
AND
parent_id=0
ORDER BY id DESC
");

$comments->execute([
$p['id']
]);

?>


<?php foreach($comments as $c): ?>

<div class="comment-box">

<b>

<?= htmlspecialchars(
$c['name']
) ?>

</b>

<?= htmlspecialchars(
$c['comment']
) ?>


<?php

$likes=$db->prepare("
SELECT COUNT(*)
FROM comment_likes
WHERE comment_id=?
");

$likes->execute([
$c['id']
]);

$count=$likes->fetchColumn();

?>

<div>

<a
href="comment.php?like=<?= $c['id'] ?>">

❤️ <?= $count ?>

</a>

</div>


<!-- REPLY FORM -->

<form
action="comment.php"
method="POST">

<input
type="hidden"
name="post_id"
value="<?= $p['id'] ?>">

<input
type="hidden"
name="parent_id"
value="<?= $c['id'] ?>">

<input
type="text"
name="comment"
placeholder="Reply..."
required>

</form>


<?php

$reply=$db->prepare("
SELECT
comments.*,
users.name
FROM comments
JOIN users
ON users.id=comments.user_id
WHERE parent_id=?
");

$reply->execute([
$c['id']
]);

?>


<?php foreach($reply as $r): ?>

<div
style="
margin-left:25px;
font-size:13px;
">

↳

<b>

<?= htmlspecialchars(
$r['name']
) ?>

</b>

<?= htmlspecialchars(
$r['comment']
) ?>

</div>

<?php endforeach; ?>

</div>

<?php endforeach; ?>

    <a href="like.php?id=<?= $p['id'] ?>">
        👍 Like (<?= $likes ?>)
    </a>

    <a href="comment.php?id=<?= $p['id'] ?>">
        💬 Comment (<?= $comments ?>)
    </a>

    <a href="share.php?id=<?= $p['id'] ?>">
        🔁 Share (<?= $shares ?>)
    </a>

</div>
      
<!-- ================= COMMENTS DISPLAY ================= -->

<?php
$c = $db->prepare("
    SELECT comments.*, users.name
    FROM comments
    JOIN users
    ON users.id = comments.user_id
    WHERE post_id=? AND parent_id=0
    ORDER BY comments.id DESC
");

$c->execute([
    $p['id']
]);

$allComments = $c->fetchAll();
?>

<?php foreach($allComments as $cmt): ?>

<div style="
margin:5px 0;
padding:8px;
background:#f1f1f1;
border-radius:8px;
">

<b>

<?= htmlspecialchars($cmt['name']) ?>:

</b>

<?= htmlspecialchars($cmt['comment']) ?>

<?php

$clike = $db->prepare("
SELECT COUNT(*)
FROM comment_likes
WHERE comment_id=?
");

$clike->execute([
$cmt['id']
]);

$clikes = $clike->fetchColumn();

?>

<div style="font-size:12px; margin-top:5px;">

<a href="comment.php?like=<?= $cmt['id'] ?>">

❤️ <?= $clikes ?> Like

</a>

</div>


<!-- REPLY FORM -->

<form
action="comment.php"
method="POST">

<input
type="hidden"
name="post_id"
value="<?= $p['id'] ?>">

<input
type="hidden"
name="parent_id"
value="<?= $cmt['id'] ?>">

<input
type="text"
name="comment"
placeholder="Reply..."
required>

<button>

Reply

</button>

</form>

</div>

<?php endforeach; ?>

</div>

<?php endforeach; ?>

</div>

</body>
</html>
