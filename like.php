<?php

session_start();

require "db.php";

$post=$_GET['id'];

$user=$_SESSION['id'];

$stmt=$db->prepare(

"

INSERT INTO likes(

user_id,

post_id

)

VALUES(?,?)

"

);

$stmt->execute([

$user,

$post

]);

$get=$db->prepare(

"

SELECT user_id

FROM posts

WHERE id=?

"

);

$get->execute([

$post

]);

$owner=$get->fetchColumn();

if(

$owner

&&

$owner!=$user

){

$db->prepare(

"

INSERT INTO notifications(

user_id,

actor_id,

post_id,

type

)

VALUES(

?,?,?,'like'

)

"

)->execute([

$owner,

$user,

$post

]);

}

header(

"Location:home.php"

);
