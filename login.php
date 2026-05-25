<?php
session_start();
require "db.php";

if(isset($_SESSION['id'])){
    header("Location: home.php");
    exit;
}

$error = "";

if($_POST){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if($user && password_verify($password, $user['password'])){
        $_SESSION['id'] = $user['id'];
        header("Location: home.php");
        exit;
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>LETUNITE Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="auth-body">

<div class="auth-box">

    <h2>Login</h2>

    <?php if($error): ?>
        <p style="color:red; text-align:center;">
            <?= $error ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <input type="text" name="email" placeholder="Email" required>

        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>

    </form>

    <p style="text-align:center; margin-top:10px;">
        Don't have an account? <a href="register.php" style="color:#00bfff;">Register</a>
    </p>

</div>

</body>
</html>
