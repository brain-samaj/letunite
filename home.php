<?php
session_start();

if(!isset($_SESSION['id'])){
    header("Location:index.php");
    exit;
}

require "db.php";

$user = $_SESSION['id'];

/* UPDATE LAST SEEN */
$db->prepare("
UPDATE users SET last_seen=? WHERE id=?
")->execute([time(), $user]);

/* FETCH POSTS */
$stmt = $db->query("
SELECT posts.*, users.name, users.profile_pic
FROM posts
JOIN users ON users.id = posts.user_id
ORDER BY posts.id DESC
");

$posts = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
<title>LETUNITE</title>
<link rel="stylesheet" href="style.css">

<script>
function likePost(id){
    fetch("like.php?id=" + id)
    .then(()=>location.reload());
}

function sendComment(postId){

    let input = document.getElementById("c"+postId);
    let text = input.value;

    fetch("comment.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "post_id=" + postId + "&comment=" + encodeURIComponent(text)
    }).then(()=>location.reload());

    return false;
}

function viewImage(src){
    let modal = document.createElement("div");
    modal.style.position = "fixed";
    modal.style.top = 0;
    modal.style.left = 0;
    modal.style.width = "100%";
    modal.style.height = "100%";
    modal.style.background = "rgba(0,0,0,0.9)";
    modal.style.display = "flex";
    modal.style.justifyContent = "center";
    modal.style.alignItems = "center";
    modal.style.zIndex = 9999;

    modal.innerHTML = `<img src="${src}" style="max-width:95%; max-height:95%;">`;

    modal.onclick = ()=> modal.remove();

    document.body.appendChild(modal);
}
</script>

</head>

<body class="home-body">

<div class="top">
    <div class="logo">LETUNITE</div>
    <a href="logout.php">Logout</a>
</div>

<div class="menu">
    <a href="home.php">Home</a>
    <a href="profile.php">Profile</a>
    <a href="connect.php">Connect</a>
    <a href="chat.php">Chat</a>
</div>

<!-- POST FORM -->
<div class="create-post">

<form action="post.php" method="POST" enctype="multipart/form-data">

<textarea name="content" placeholder="What's happening?" required></textarea>

<input type="file" name="image">

<button type="submit">Post</button>

</form>

</div>

<!-- FEED -->
<div class="feed" id="feed">

<?php foreach($posts as $p): ?>

<div class="post-card">

<div class="post-header">

<?php if(!empty($p['profile_pic'])): ?>
    <img src="<?= htmlspecialchars($p['profile_pic']) ?>" width="40" style="border-radius:50%;">
<?php endif; ?>

<b><?= htmlspecialchars($p['name']) ?></b>

</div>

<div class="post-body">
<?= nl2br(htmlspecialchars($p['content'])) ?>
</div>

<!-- POST IMAGE (CLOUDINARY FIX) -->
<?php if(!empty($p['image'])): ?>
    <img 
        src="<?= htmlspecialchars($p['image']) ?>" 
        style="max-width:100%; cursor:pointer;"
        onclick="viewImage(this.src)"
    >
<?php endif; ?>

<?php
$like = $db->prepare("SELECT COUNT(*) FROM likes WHERE post_id=?");
$like->execute([$p['id']]);
$likeCount = $like->fetchColumn();

$comment = $db->prepare("SELECT COUNT(*) FROM comments WHERE post_id=?");
$comment->execute([$p['id']]);
$commentCount = $comment->fetchColumn();

$share = $db->prepare("SELECT COUNT(*) FROM shares WHERE post_id=?");
$share->execute([$p['id']]);
$shareCount = $share->fetchColumn();
?>

<div class="post-actions">

<button onclick="likePost(<?= $p['id'] ?>)">
👍 Like (<?= $likeCount ?>)
</button>

<a href="#csection<?= $p['id'] ?>">
💬 Comment (<?= $commentCount ?>)
</a>

<a href="share.php?id=<?= $p['id'] ?>">
🔁 Share (<?= $shareCount ?>)
</a>

</div>

<!-- COMMENTS -->
<div id="csection<?= $p['id'] ?>">

<form onsubmit="return sendComment(<?= $p['id'] ?>)">

<input type="text" id="c<?= $p['id'] ?>" placeholder="Write comment..." required>

<button type="submit">Send</button>

</form>

<?php
$c = $db->prepare("
SELECT comments.*, users.name
FROM comments
JOIN users ON users.id = comments.user_id
WHERE post_id=?
ORDER BY id DESC
");
$c->execute([$p['id']]);
$all = $c->fetchAll();
?>

<?php foreach($all as $cm): ?>
    <div class="comment-box">
        <b><?= htmlspecialchars($cm['name']) ?>:</b>
        <?= htmlspecialchars($cm['comment']) ?>
    </div>
<?php endforeach; ?>

</div>

</div>

<?php endforeach; ?>

</div>

</body>
</html>
