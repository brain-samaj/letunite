<?php
session_start();
require "db.php";

if(!isset($_SESSION['id'])){
    header("Location:index.php");
    exit;
}

$me = $_SESSION['id'];

$users = $db->query("
SELECT * FROM users WHERE id != $me
");
?>

<!DOCTYPE html>
<html>
<head>
<title>LETUNITE Chat</title>
<link rel="stylesheet" href="style.css">
</head>

<body class="chat-body">

<div class="chat-topbar">
    💬 Chats
</div>

<div class="chat-list">

<?php foreach($users as $u): ?>

<a href="conversation.php?user=<?= $u['id'] ?>" class="chat-user">

<img src="<?= !empty($u['profile_pic']) ? 'uploads/'.$u['profile_pic'] : 'assets/default.png' ?>">

<div class="chat-user-info">
    <b><?= htmlspecialchars($u['name']) ?></b>
    <small>Tap to chat</small>
</div>

</a>

<?php endforeach; ?>

</div>

</body>
</html>
