<?php

session_start();
require "db.php";

if(isset($_SESSION['id'])){
    header("Location: home.php");
    exit;
}

$error="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $name=trim($_POST['name'] ?? '');
    $email=trim($_POST['email'] ?? '');
    $rawPassword=$_POST['password'] ?? '';

    if(
        empty($name) ||
        empty($email) ||
        empty($rawPassword)
    ){

        $error="All fields are required";

    } else {

        $password=password_hash(
            $rawPassword,
            PASSWORD_DEFAULT
        );

        $check=$db->prepare(
            "SELECT id
            FROM users
            WHERE email=?"
        );

        $check->execute([
            $email
        ]);

        if($check->fetch()){

            $error=
            "Email already exists";

        } else {

            $stmt=$db->prepare(

            "INSERT INTO users
            (
            name,
            email,
            password
            )

            VALUES
            (
            ?,
            ?,
            ?
            )"

            );

            $ok=$stmt->execute([

                $name,
                $email,
                $password

            ]);

            if($ok){

                header(
                "Location: login.php"
                );

                exit;

            }else{

                $error=
                "Registration failed";

            }

        }

    }

}

?>

<!DOCTYPE html>

<html>

<head>

<title>LETUNITE Register</title>

<link
rel="stylesheet"
href="style.css">

</head>

<body class="auth-body">

<div class="auth-box">

<h2>Create Account</h2>

<?php if($error): ?>

<p
style="
color:red;
text-align:center;
">

<?= htmlspecialchars($error) ?>

</p>

<?php endif; ?>

<form method="POST">

<input
type="text"
name="name"
placeholder="Full Name"
required>

<input
type="email"
name="email"
placeholder="Email"
required>

<input
type="password"
name="password"
placeholder="Password"
required>

<button
type="submit">

Sign Up

</button>

</form>

<p
style="
text-align:center;
margin-top:10px;
">

Already have account?

<a href="login.php">

Login

</a>

</p>

</div>

</body>

</html>
