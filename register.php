<?php

session_start();

require "db.php";

$error="";

if(isset($_POST["signup"])){

$name=trim($_POST["name"]);

$email=trim($_POST["email"]);

$password=$_POST["password"];

if(strlen($password)<6){

$error="Password must be at least 6 characters";

}else{

$pass=password_hash(

$password,

PASSWORD_DEFAULT

);

$profile="";

if(

!empty(

$_FILES[
"profile_pic"
]["name"]

)

){

$file=

time()."_".

basename(

$_FILES[
"profile_pic"
]["name"]

);

move_uploaded_file(

$_FILES[
"profile_pic"
]["tmp_name"],

"uploads/".$file

);

$profile=$file;

}

try{

$stmt=$db->prepare(

"

INSERT INTO users(

name,

email,

password,

profile_pic

)

VALUES(

?,?,?,?

)

"

);

$stmt->execute([

$name,

$email,

$pass,

$profile

]);

header(

"Location:index.php"

);

exit;

}

catch(Exception $e){

$error="Email already exists";

}

}

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

Create Account

</h1>

<form

method="POST"

enctype="multipart/form-data"

>

<input

name="name"

placeholder="Display Name"

required>

<input

name="email"

type="email"

placeholder="Valid Gmail"

required>

<input

name="password"

type="password"

placeholder="Password"

required>

<label>

Profile Picture

</label>

<input

type="file"

name="profile_pic"

accept="image/*">

<button

name="signup"

>

Create Account

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
