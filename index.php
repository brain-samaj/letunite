<?php

session_start();

if(isset($_SESSION['id'])){

header("Location:home.php");

exit;

}

?>

<html>

<head>

<link rel="stylesheet" href="style.css">

<title>LET UNITE</title>

</head>

<body>

<div class="box">

<h1>

LET UNITE

</h1>

<p>

Connect • Unite • Grow

</p>

<button
onclick="location='register.php'"
>

Sign Up

</button>

<button
onclick="location='login.php'"
>

Sign In

</button>

</div>

</body>

</html>
