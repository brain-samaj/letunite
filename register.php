<?php
session_start();
require "db.php";

if(isset($_SESSION['id'])){
    header("Location: home.php");
    exit;
}

$error = "";

if($_POST){

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    /* check if email exists */
    $check = $db->prepare("SELECT id FROM users WHERE email=?");
    $check->execute([$email]);

    if($check->fetch()){
        $error = "Email already exists";
    } else {

        $stmt = $db->prepare("
            INSERT INTO users(name,email,password)
            VALUES(?,?,?)
        ");

        $stmt->execute([$name,$email,$password]);

        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>LETUNITE Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-body">

<div class="auth-box">

    <h2>Create Account</h2>

    <?php if($error): ?>
        <p style="color:red; text-align:center;">
            <?= $error ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <input type="text" name="name" placeholder="Full Name" required>

        <input type="text" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Sign Up</button>

    </form>

    <p style="text-align:center; margin-top:10px;">
        Already have an account? <a href="login.php" style="color:#00bfff;">Login</a>
    </p>

</div>

</body>
</html>
