<?php

session_start();
require "db.php";

$user=$_SESSION['id'];

$content=trim($_POST['content']);

$image="";

if(!empty($_FILES['image']['name'])){

$file=time()."_".basename($_FILES['image']['name']);

move_uploaded_file(
$_FILES['image']['tmp_name'],
"uploads/".$file
);

$image=$file;

}

$stmt=$db->prepare(

"INSERT INTO posts(user_id,content,image)
VALUES(?,?,?)"

);

$stmt->execute([
$user,
$content,
$image
]);

header("Location:home.php");
exit;
