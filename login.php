<?php

session_start();

require "db.php";

$error="";

if(isset($_POST["login"])){

$email=$_POST["email"];

$password=$_POST["password"];

$stmt=$db->prepare(

"SELECT * FROM users

WHERE email=?"

);

$stmt->execute([

$email

]);

$user=$stmt->fetch();

if(

$user &&

password_verify(

$password,

$user["password"]

)

){

$_SESSION["id"]=$user["id"];

$_SESSION["name"]=$user["name"];

header(

"Location:home.php"

);

exit;

}

$error="Wrong login";

}

?>

<html>

<head>

<link rel="stylesheet"

href="style.css">

</head>

<body>

<div class="box">

<h1>

Sign In

</h1>

<form method="POST">

<input

name="email"

type="email"

placeholder="Email"

required>

<input

name="password"

type="password"

placeholder="Password"

required>

<button

name="login"

>

Login

</button>

</form>

<p>

<?=$error?>

</p>

<a href="index.php">

Back

</a>

</div>

</body>

</html>
