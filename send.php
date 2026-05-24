<?php
session_start();
require "db.php";

if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$sender = $_SESSION['id'];
$receiver = $_POST['to'];
$message=

trim(

$_POST['message']

);
if(

$message==""

&&

empty(

$_FILES['image']['name']

)

){

header(

"Location:

conversation.php?

id=".$receiver

);

exit;

}
$image = null;

/* IMAGE UPLOAD */
if (!empty($_FILES['image']['name'])) {

    $fileName = time() . "_" . basename($_FILES['image']['name']);
    $target = "uploads/" . $fileName;

    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    $image = $fileName;
}

/* SAVE MESSAGE */
$stmt = $db->prepare("
    INSERT INTO messages (sender, receiver, message, image)
    VALUES (?, ?, ?, ?)
");

$stmt->execute([$sender, $receiver, $message, $image]);

header("Location: chat.php");
exit;
?>
