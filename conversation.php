<?php

session_start();

require "db.php";

if(!isset($_SESSION['id'])){

header("Location:index.php");

exit;

}

if(!isset($_GET['id'])){

header("Location:chat.php");

exit;
}

$me=$_SESSION['id'];

$friend=$_GET['id'];

$db->prepare(

"

UPDATE messages

SET seen=1

WHERE sender=?

AND receiver=?

"

)->execute([

$friend,

$me

]);

$msgs=$db->prepare(

"

SELECT

messages.*,

users.name

FROM messages

JOIN users

ON users.id=

messages.sender

WHERE

(

sender=?

AND receiver=?

)

OR

(

sender=?

AND receiver=?

)

ORDER BY id ASC

"

);

$msgs->execute([

$me,

$friend,

$friend,

$me

]);

?>

<html>

<head>

<link
rel="stylesheet"
href="style.css">

</head>

<body>

<div class="feed">

<?php

foreach($msgs as $m){

$mine=

$m['sender']==$me;

?>

<div
style="

display:flex;

justify-content:

<?=

$mine

?

'flex-end'

:

'flex-start'

?>

;

margin:10px;

">

<div
style="

max-width:70%;

padding:10px;

border-radius:12px;

background:

<?=

$mine

?

'#DCF8C6'

:

'#eee'

?>

;

">

<?php

if(!empty($m['message'])){

echo htmlspecialchars(

$m['message']

);

}

if(!empty($m['image'])){

?>

<br>

<img

src="uploads/<?=$m['image']?>"

width="200">

<?php

}

?>

</div>

</div>

<?php

}

?>

<form

action="send.php"

method="POST"

enctype="multipart/form-data"

>

<input

type="hidden"

name="to"

value="<?=$friend?>">

<input

name="message"

placeholder="Type message">

<input

type="file"

name="image">

<button>

SEND

</button>

</form>

</div>

</body>

</html>
