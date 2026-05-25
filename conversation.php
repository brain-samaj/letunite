<?php
session_start();
require "db.php";

if(!isset($_SESSION['id'])){
    header("Location:index.php");
    exit;
}

$me = $_SESSION['id'];
$friend = $_GET['id'];

/* mark seen */
$db->prepare("
    UPDATE messages SET seen=1
    WHERE sender=? AND receiver=?
")->execute([$friend, $me]);

$msgs = $db->prepare("
    SELECT * FROM messages
    WHERE (sender=? AND receiver=?)
    OR (sender=? AND receiver=?)
    ORDER BY id ASC
");
$msgs->execute([$me,$friend,$friend,$me]);
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style.css">
<title>Chat</title>
</head>

<body class="chat-body">

<div class="chat-box">

<?php foreach($msgs as $m): ?>

<div class="msg <?= $m['sender']==$me?'me':'them' ?>">

    <?php if($m['message']): ?>
        <p><?= htmlspecialchars($m['message']) ?></p>
    <?php endif; ?>

    <?php if($m['image']): ?>
        <img src="uploads/<?= $m['image'] ?>" style="max-width:200px;">
    <?php endif; ?>

    <?php if($m['video']): ?>
        <video controls style="max-width:200px;">
            <source src="uploads/<?= $m['video'] ?>">
        </video>
    <?php endif; ?>

    <?php if($m['file']): ?>
        <a href="uploads/<?= $m['file'] ?>" download>📁 Download File</a>
    <?php endif; ?>

    <?php if($m['audio']): ?>
        <audio controls>
            <source src="uploads/<?= $m['audio'] ?>">
        </audio>
    <?php endif; ?>

</div>

<?php endforeach; ?>

</div>

<!-- SEND FORM -->
<form action="send.php" method="POST" enctype="multipart/form-data" class="chat-form">

    <input type="hidden" name="receiver" value="<?= $friend ?>">

    <input type="text" name="message" placeholder="Message">

    <input type="file" name="image" accept="image/*">
    <input type="file" name="video" accept="video/*">
    <input type="file" name="file">
    <input type="file" name="audio" accept="audio/*">

    <button>Send</button>
</form>

</body>
</html>
