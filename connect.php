<?php

session_start();

require "db.php";
require "auth.php";

if(!isset($_SESSION['id'])){

header("Location:index.php");

exit;

}

if(!empty($_GET['search'])){

$s="%".$_GET['search']."%";

$users=$db->prepare(

"

SELECT *

FROM users

WHERE

id!=?

AND

name LIKE ?

ORDER BY name

"

);

$users->execute([

$_SESSION['id'],

$s

]);

}

else{

$users=$db->prepare(

"

SELECT *

FROM users

WHERE id!=?

ORDER BY name

"

);

$users->execute([

$_SESSION['id']

]);

}

?>

<html>

<head>

<link
rel="stylesheet"
href="style.css">

<title>

CONNECT

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

<a href="feeds.php">

FEEDS

</a>

</div>

<div class="feed">

<h2>

CONNECT PEOPLE

</h2>

<form method="GET">

<input

name="search"

placeholder="Search people">

<button>

SEARCH

</button>

</form>

<?php

foreach($users as $u){

?>

<div

class="post"

style="

display:flex;

align-items:center;

gap:12px;

"

>

<a

href=

"user.php?id=

<?=$u['id']?>"

>

<img

src="<?=profilePic($u)?>"

width="55"

height="55"

style="

border-radius:50%;

object-fit:cover;

"

>

</a>

<div>

<a

href=

"user.php?id=

<?=$u['id']?>"

style="

text-decoration:none;

font-weight:bold;

font-size:18px;

"

>

<?=htmlspecialchars(

$u['name']

)?>

</a>

</div>

</div>

<?php

}

?>

</div>

</body>

</html>
