<?php

session_start();

require "db.php";

$id=$_SESSION['id'];

$pic=null;

if(
!empty(
$_FILES['profile_pic']['name']
)
){

$name=time()."_".

basename(

$_FILES[
'profile_pic'
]['name']

);

move_uploaded_file(

$_FILES[
'profile_pic'
]['tmp_name'],

"uploads/".$name

);

$pic=$name;

}

$stmt=$db->prepare(

"

UPDATE users

SET

profile_pic=

COALESCE(
?,
profile_pic
),

country=?,

city=?,

dob=?,

gender=?,

marital_status=?

WHERE id=?

"

);

$stmt->execute([

$pic,

$_POST['country'],

$_POST['city'],

$_POST['dob'],

$_POST['gender'],

$_POST['marital_status'],

$id

]);

header(
"Location:profile.php"
);
