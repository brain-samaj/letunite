<?php
session_start();
require "db.php";

if(!isset($_SESSION['id'])){
    header("Location:index.php");
    exit;
}

if(!isset($_GET['user'])){
    header("Location:chat.php");
    exit;
}

$me = $_SESSION['id'];
$other = (int)$_GET['user'];

/* GET USER */
$userInfo = $db->prepare("SELECT * FROM users WHERE id=?");
$userInfo->execute([$other]);
$user = $userInfo->fetch();

/* GET MESSAGES */
$msgs = $db->prepare("
SELECT * FROM messages
WHERE (sender=? AND receiver=?)
OR (sender=? AND receiver=?)
ORDER BY id ASC
");

$msgs->execute([$me,$other,$other,$me]);
$messages = $msgs->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<title>Conversation</title>
<link rel="stylesheet" href="style.css">

<script>
window.onload = function(){
    let box = document.querySelector(".chat-box");
    box.scrollTop = box.scrollHeight;
};
</script>

</head>

<body class="chat-body">

<!-- TOP BAR -->
<div class="chat-header">
    <a href="chat.php" style="color:white;text-decoration:none;">←</a>

    <img src="<?= !empty($user['profile_pic']) ? 'uploads/'.$user['profile_pic'] : 'assets/default.png' ?>">

    <b><?= htmlspecialchars($user['name']) ?></b>
</div>

<!-- CHAT MESSAGES -->
<div class="chat-box">

<?php foreach($messages as $m): ?>

<div class="msg <?= $m['sender']==$me ? 'me' : 'them' ?>">

<!-- TEXT -->
<?php if(!empty($m['message'])): ?>
    <div><?= htmlspecialchars($m['message']) ?></div>
<?php endif; ?>

<!-- IMAGE -->
<?php if(!empty($m['image'])): ?>
    <img src="uploads/<?= htmlspecialchars($m['image']) ?>" style="max-width:200px;border-radius:10px;margin-top:5px;">
<?php endif; ?>

<!-- VOICE -->
<?php if(!empty($m['voice'])): ?>
    <audio controls style="margin-top:5px;">
        <source src="uploads/<?= htmlspecialchars($m['voice']) ?>">
    </audio>
<?php endif; ?>

</div>

<?php endforeach; ?>

</div>

<!-- CHAT INPUT -->
<form action="send.php" method="POST" class="chat-form" enctype="multipart/form-data">

<input type="hidden" name="receiver" value="<?= $other ?>">

<input type="text" name="message" placeholder="Message...">

<label for="img" style="font-size:20px;cursor:pointer;">📷</label>
<input type="file" name="image" id="img" accept="image/*" style="display:none;">

<label for="voice" style="font-size:20px;cursor:pointer;">🎤</label>
<input type="file" name="voice" id="voice" accept="audio/*" style="display:none;">

<button type="submit">➤</button>

</form>

</body>
</html>
