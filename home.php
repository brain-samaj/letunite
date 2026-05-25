<?php
session_start();

if(!isset($_SESSION['id'])){
    header("Location:index.php");
    exit;
}

require "db.php";

$user = $_SESSION['id'];

/* LAST SEEN */
$db->prepare("
UPDATE users SET last_seen=? WHERE id=?
")->execute([time(), $user]);

/* POSTS */
$posts = $db->query("
SELECT posts.*, users.name, users.profile_pic
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

<!-- AJAX (REAL-TIME FOUNDATION) -->
<script>
function likePost(id){
    fetch("like.php?id=" + id)
    .then(()=>location.reload());
}

function sendComment(postId){
    let input = document.getElementById("c"+postId);
    let text = input.value;

    fetch("comment.php", {
        method:"POST",
        headers:{"Content-Type":"application/x-www-form-urlencoded"},
        body:"post_id="+postId+"&comment="+text+"&parent_id=0"
    }).then(()=>location.reload());

    return false;
}
</script>

</head>

<script>
/* AUTO REFRESH FEED (REAL TIME SIMULATION) */
setInterval(()=>{
    fetch("fetch_posts.php")
    .then(res=>res.text())
    .then(data=>{
        document.getElementById("feed").innerHTML = data;
    });
}, 5000);
</script>

<script>
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

    modal.innerHTML = `
        <img src="${src}" style="max-width:95%; max-height:95%; border-radius:10px;">
    `;

    modal.onclick = ()=> modal.remove();

    document.body.appendChild(modal);
}
</script>

</body>
</html>

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
<img src="<?= !empty($p['profile_pic']) ? 'uploads/'.$p['profile_pic'] : 'assets/default.png' ?>">
<b><?= htmlspecialchars($p['name']) ?></b>
</div>

<div class="post-body">
<?= nl2br(htmlspecialchars($p['content'])) ?>
</div>

<?php if(!empty($p['image'])): ?>
<img src="uploads/<?= htmlspecialchars($p['image']) ?>" class="post-image">
<?php endif; ?>

<?php
$likes = $db->prepare("SELECT COUNT(*) FROM likes WHERE post_id=?");
$likes->execute([$p['id']]);
$likeCount = $likes->fetchColumn();

$comments = $db->prepare("SELECT COUNT(*) FROM comments WHERE post_id=?");
$comments->execute([$p['id']]);
$commentCount = $comments->fetchColumn();

$shares = $db->prepare("SELECT COUNT(*) FROM shares WHERE post_id=?");
$shares->execute([$p['id']]);
$shareCount = $shares->fetchColumn();
?>

<div class="post-actions">

<!-- LIKE (AJAX) -->
<button onclick="likePost(<?= $p['id'] ?>)">
👍 Like (<?= $likeCount ?>)
</button>

<!-- COMMENT -->
<a href="#csection<?= $p['id'] ?>">
💬 Comment (<?= $commentCount ?>)
</a>

<!-- SHARE -->
<a href="share.php?id=<?= $p['id'] ?>">
🔁 Share (<?= $shareCount ?>)
</a>

</div>

<!-- COMMENT INPUT (AJAX READY) -->
<div id="csection<?= $p['id'] ?>">

<form onsubmit="return sendComment(<?= $p['id'] ?>)">
<input type="text" id="c<?= $p['id'] ?>" placeholder="Write comment..." required>
<button type="submit">Send</button>
</form>

<!-- COMMENTS -->
<?php
$c = $db->prepare("
SELECT comments.*, users.name
FROM comments
JOIN users ON users.id = comments.user_id
WHERE post_id=? AND parent_id=0
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
