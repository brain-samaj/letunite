<?php

session_start();
require "auth.php";
require "db.php";

$me=$_SESSION['id'];

$users=$db->prepare(

"

SELECT *

FROM users

WHERE id!=?

"

);

$users->execute([$me]);

?>

<html>

<body>

<div class="feed">

<h2>

CHAT

</h2>

<?php

foreach($users as $u){

$online=

(time()-

(int)$u['last_seen']

)<60;

$unread=$db->prepare(

"

SELECT COUNT(*)

FROM messages

WHERE

sender=?

AND receiver=?

AND seen=0

"

);

$unread->execute([

$u['id'],

$me

]);

$count=$unread->fetchColumn();

?>

<div class="post">

<a href="conversation.php?id=<?=$u['id']?>">

<img

src="<?=profilePic($u)?>"

width="40"

height="40"

style="
border-radius:50%;
object-fit:cover;
vertical-align:middle;
">

<?=$u['name']?>

</a>

<?=

$online

?

"🟢 Online"

:

"⚫ Offline"

?>

<?php

if($count){

echo

"("

.$count

.")";

}

?>

</div>

<?php

}

?>

</div>

</body>

</html>
