<?php

session_start();

require "db.php";

$post=$_POST['post'];

$comment=$_POST['comment'];

$parent=

$_POST['parent']

??

NULL;

$db->prepare(

"

INSERT INTO comments(

user_id,

post_id,

comment,

parent_id

)

VALUES(

?,?,?,?

)

"

)->execute([

$_SESSION['id'],

$post,

$comment,

$parent

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

$owner!=$_SESSION['id']

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

?,?,?,'comment'

)

"

)->execute([

$owner,

$_SESSION['id'],

$post

]);

}

header(

"Location:home.php"

);
