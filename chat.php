<?php
session_start();
require "db.php";

if(!isset($_SESSION['id'])){
    header("Location:index.php");
    exit;
}

$me = $_SESSION['id'];

/* GET ALL USERS YOU HAVE CHATTED WITH */
$users = $db->prepare("
    SELECT DISTINCT u.id, u.name, u.profile_pic
    FROM users u
    JOIN messages m 
    ON (m.sender = u.id OR m.receiver = u.id)
    WHERE (m.sender = ? OR m.receiver = ?)
    AND u.id != ?
");
$users->execute([$me, $me, $me]);
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<title>Chat</title>
</head>

<body class="home-body">

<div class="top">
    <h3>Messages</h3>
    <a href="home.php">Home</a>
</div>

<div class="feed">

<?php foreach($users as $u): ?>

<a class="chat-user" href="conversation.php?id=<?= $u['id'] ?>">

    <img src="<?= $u['profile_pic'] ? 'uploads/'.$u['profile_pic'] : 'assets/default.png' ?>">

    <div>
        <b><?= htmlspecialchars($u['name']) ?></b>
        <p>Tap to chat</p>
    </div>

</a>

<?php endforeach; ?>

</div>

</body>
</html>
