<?php

session_start();

require "db.php";

require "auth.php";

if(!isset($_SESSION['id'])){

header("Location:index.php");

exit;

}

$stmt=$db->prepare(

"

SELECT

notifications.*,

users.name,

users.profile_pic

FROM notifications

JOIN users

ON users.id=

notifications.actor_id

WHERE

notifications.user_id=?

ORDER BY

notifications.id DESC

"

);

$stmt->execute([

$_SESSION['id']

]);

$notifications=$stmt->fetchAll();

$db->prepare(

"

UPDATE notifications

SET seen=1

WHERE user_id=?

"

)->execute([

$_SESSION['id']

]);

?>

<html>

<head>

<link rel="stylesheet"

href="style.css">

<title>

Notifications

</title>

<meta
http-equiv="refresh"
content="10">

</head>

<body>

<div class="top">

LET UNITE

</div>

<div class="menu">

<a href="home.php">

HOME

</a>

<a href="connect.php">

CONNECT

</a>

<a href="chat.php">

CHAT

</a>

<a href="feeds.php">

FEEDS

</a>

</div>

<div class="feed">

<h2>

Notifications

</h2>

<?php

if(

count($notifications)

==0

){

echo

"

<div class='post'>

No notifications yet

</div>

";

}

foreach(

$notifications

as $n

){

?>

<div
class="post">

<a

href=

"viewpost.php?id=

<?=$n['post_id']?>"

style="

display:flex;

align-items:center;

gap:10px;

text-decoration:none;

color:black;

"

>

<img

src="<?=profilePic($n)?>"

width="45"

height="45"

style="

border-radius:50%;

object-fit:cover;

"

>

<div>

<b>

<?=htmlspecialchars(

$n['name']

)?>

</b>

<?php

if(

$n['type']

=="like"

){

echo

" liked your post ❤️";

}

elseif(

$n['type']

=="comment"

){

echo

" commented on your post 💬";

}

?>

</div>

</a>

</div>

<?php

}

?>

</div>

</body>

</html>
