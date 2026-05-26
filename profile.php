<?php

session_start();
require "db.php";

/* Redirect if not logged in */
if (!isset($_SESSION['id'])) {
    header("Location:index.php");
    exit;
}

$id = $_SESSION['id'];

/* Update last seen */
$db->prepare("
UPDATE users
SET last_seen = ?
WHERE id = ?
")->execute([
    time(),
    $id
]);

/* Get user safely */
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Profile</title>
</head>

<body>

<div class="feed">

<h2>MY PROFILE</h2>

<!-- PROFILE IMAGE (CLOUDINARY FIX) -->
<?php if (!empty($user['profile_pic'])): ?>
    <img 
        src="<?= htmlspecialchars($user['profile_pic']) ?>" 
        width="120" 
        style="border-radius:50%; object-fit:cover;"
    >
<?php endif; ?>

<!-- PROFILE FORM -->
<form action="upload_profile.php" method="POST" enctype="multipart/form-data">

<input type="file" name="profile_pic">

<input name="country"
placeholder="Country"
value="<?= htmlspecialchars($user['country'] ?? '') ?>">

<input name="city"
placeholder="City"
value="<?= htmlspecialchars($user['city'] ?? '') ?>">

<input type="date"
name="dob"
value="<?= htmlspecialchars($user['dob'] ?? '') ?>">

<!-- GENDER -->
<select name="gender">
    <option value="<?= htmlspecialchars($user['gender'] ?? '') ?>">
        <?= htmlspecialchars($user['gender'] ?? 'Select Gender') ?>
    </option>
    <option value="Male">Male</option>
    <option value="Female">Female</option>
</select>

<!-- MARITAL STATUS -->
<select name="marital_status">
    <option value="<?= htmlspecialchars($user['marital_status'] ?? '') ?>">
        <?= htmlspecialchars($user['marital_status'] ?? 'Select Status') ?>
    </option>
    <option value="Single">Single</option>
    <option value="Married">Married</option>
</select>

<button type="submit">SAVE PROFILE</button>

</form>

</div>

</body>
</html>
