<?php

session_start();

require "db.php";
require "auth.php";

if(

!isset(

$_GET['id']

)

){

header(

"Location:connect.php"

);

exit;

}

$stmt=$db->prepare(

"

SELECT *

FROM users

WHERE id=?

"

);

$stmt->execute([

$_GET['id']

]);

$user=$stmt->fetch();

if(!$user){

die(

"User not found"

);

}

?>

<html>

<head>

<link
rel="stylesheet"
href="style.css">

<title>

<?=$user['name']?>

</title>

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

</div>

<div
class="feed"

style="

text-align:center;

"

>

<img

src="<?=profilePic($user)?>"

width="130"

height="130"

style="

border-radius:50%;

object-fit:cover;

"

>

<h2>

<?=htmlspecialchars(

$user['name']

)?>

</h2>

<hr>

<p>

Country:

<?=htmlspecialchars(

$user['country']

?? ""

)?>

</p>

<p>

City:

<?=htmlspecialchars(

$user['city']

?? ""

)?>

</p>

<p>

Gender:

<?=htmlspecialchars(

$user['gender']

?? ""

)?>

</p>

<p>

Date Of Birth:

<?=htmlspecialchars(

$user['dob']

?? ""

)?>

</p>

<p>

Marital Status:

<?=htmlspecialchars(

$user['marital_status']

?? ""

)?>

</p>

</div>

</body>

</html>
