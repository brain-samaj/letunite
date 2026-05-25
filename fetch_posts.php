<?php
session_start();
require "db.php";

$posts = $db->query("
SELECT posts.*, users.name, users.profile_pic
FROM posts
JOIN users ON users.id = posts.user_id
ORDER BY posts.id DESC
");

foreach($posts as $p):
?>

<div class="post-card" data-id="<?= $p['id'] ?>">

<!-- USER -->
<div class="post-header">

<img
src="<?= !empty($p['profile_pic']) ? 'uploads/'.$p['profile_pic'] : 'assets/default.png' ?>">

<b><?= htmlspecialchars($p['name']) ?></b>

</div>

<!-- TEXT -->
<?php if(!empty($p['content'])): ?>
<div class="post-body">
<?= nl2br(htmlspecialchars($p['content'])) ?>
</div>
<?php endif; ?>

<!-- IMAGE -->
<?php if(!empty($p['image'])): ?>

<img
src="uploads/<?= htmlspecialchars($p['image']) ?>"
class="post-image"
onclick="viewImage(this.src)">

<?php endif; ?>

<!-- VIDEO -->
<?php if(!empty($p['video'])): ?>

<video controls class="post-video">
<source src="uploads/<?= htmlspecialchars($p['video']) ?>">
</video>

<?php endif; ?>

<!-- COUNTS -->
<?php

$like = $db->prepare("SELECT COUNT(*) FROM likes WHERE post_id=?");
$like->execute([$p['id']]);
$likes = $like->fetchColumn();

$com = $db->prepare("SELECT COUNT(*) FROM comments WHERE post_id=?");
$com->execute([$p['id']]);
$comments = $com->fetchColumn();

$share = $db->prepare("SELECT COUNT(*) FROM shares WHERE post_id=?");
$share->execute([$p['id']]);
$shares = $share->fetchColumn();

?>

<!-- ACTIONS -->
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

</div>

<?php endforeach; ?>
