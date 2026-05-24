<?php

session_start();

require "db.php";

$db->prepare(

"UPDATE users
SET last_seen=?
WHERE id=?"

)->execute([

time(),

$_SESSION['id']

]);

require "db.php";

$id=$_SESSION['id'];

$user=$db->query(
"SELECT * FROM users
WHERE id=".$id
)->fetch();

?>

<html>

<head>

<link rel="stylesheet"
href="style.css">

</head>

<body>

<div class="feed">

<h2>MY PROFILE</h2>

<?php

if(!empty($user['profile_pic'])){

echo "

<img
src='uploads/

".$user['profile_pic']."

'
width='120'>

";

}

?>

<form
action="upload_profile.php"
method="POST"
enctype="multipart/form-data">

<input
type="file"
name="profile_pic">

<input
name="country"
placeholder="Country"
value="<?= $user['country'] ?>">

<input
name="city"
placeholder="City"
value="<?= $user['city'] ?>">

<input
type="date"
name="dob"
value="<?= $user['dob'] ?>">

<select
name="gender">

<option>

<?= $user['gender'] ?>

</option>

<option>
Male
</option>

<option>
Female
</option>

</select>

<select
name="marital_status">

<option>

<?= $user['marital_status'] ?>

</option>

<option>
Single
</option>

<option>
Married
</option>

</select>

<button>

SAVE PROFILE

</button>

</form>

</div>

</body>

</html>
