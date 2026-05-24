<?php

session_start();

if(!isset($_SESSION['id'])){
header("Location: index.php");
exit;
}

require "auth.php";

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

if(!isset($_SESSION['id'])){

header("Location:index.php");

exit;

}

/* POSTS */

if(!empty($_GET['search'])){

$s="%".$_GET['search']."%";

$posts=$db->prepare(

"

SELECT
posts.*,
users.name

FROM posts

JOIN users

ON users.id=posts.user_id

WHERE

users.name LIKE ?

OR

posts.content LIKE ?

ORDER BY posts.id DESC

"

);

$posts->execute([

$s,

$s

]);

}

else{

$posts=$db->query(

"

SELECT

posts.*,

users.name,

users.profile_pic

FROM posts

JOIN users

ON users.id=posts.user_id

ORDER BY posts.id DESC

"

);

}

?>

<html>

<head>

<link
rel="stylesheet"
href="style.css">

<title>

LET UNITE

</title>

</head>

<body>

<div class="top">

LET UNITE

<a
href="logout.php">

Logout

</a>

</div>

<div class="menu">

<a href="home.php">

HOME

</a>
<a href="profile.php">

PROFILE

</a>

<a href="connect.php">

CONNECT

</a>

<a href="chat.php">

CHAT

</a>

<a href="feeds.php">

FEEDS

</a>

</div>

<div class="feed">

<h2>

Welcome

<?=htmlspecialchars(
$_SESSION['name']
)?>

🔥

</h2>

<form method="GET">

<input
name="search"

placeholder="Search users or posts">

<button>

SEARCH

</button>

</form>

<form action="post.php" method="POST" enctype="multipart/form-data">

<textarea

<input type="file" name="image" accept="image/*">

<?php if(!empty($post['image'])): ?>

<img src="uploads/<?=$post['image']?>" width="250">

<?php endif; ?>

name="content"

placeholder=

"Share something..."

required>

</textarea>

<button>

POST

</button>

</form>

<?php

foreach(
$posts
as $post
){

/* LIKE COUNT */

$likes=$db->query(

"

SELECT COUNT(*)

FROM likes

WHERE post_id="

.$post['id']

)->fetchColumn();

/* COMMENTS */

$comments=$db->prepare(

"

SELECT

comments.*,

users.name

FROM comments

JOIN users

ON users.id=

comments.user_id

WHERE post_id=?

ORDER BY

comments.id ASC

"

);

$comments->execute([

$post['id']

]);

?>

<div class="post">

<h3>

<a href="user.php?id=<?=$post['user_id']?>">

<?php

$pic=

!empty($post['profile_pic'])

?

"uploads/".$post['profile_pic']

:

"assets/default.png";

?>

<div
style="
display:flex;
align-items:center;
gap:10px;
">

<img

src="<?=$pic?>"

width="45"

height="45"

style="
border-radius:50%;
object-fit:cover;
">

<a
href="user.php?id=<?=$post['user_id']?>">

<?php

$pic=

!empty(

$post['profile_pic']

)

?

"uploads/".
$post['profile_pic']

:

"assets/default.png";

?>

<div
style="

display:flex;

align-items:center;

gap:10px;

">

<img

src="<?=$pic?>"

width="45"

height="45"

style="

border-radius:50%;

object-fit:cover;
">

<b>

<?=htmlspecialchars($post['name'])?>

</b>

</div>

<p>

<?=nl2br(

htmlspecialchars(

$post['content']

)

)?>

</p>

<small>

<?=htmlspecialchars(

$post['created']

)?>

</small>

<br><br>

❤️

<?=$likes?>

Likes

|

<a
href=

"like.php?id=

<?=$post['id']?>"

>

LIKE

</a>

|

<a
href=

"share.php?id=

<?=$post['id']?>"

>

SHARE

</a>

<hr>

<?php

foreach(
$comments
as $c
){

?>

<p>

<b>

<?=htmlspecialchars(
$c['name']
)?>

</b>

:

<?=htmlspecialchars(
$c['comment']
)?>

</p>

<form
action="comment.php"
method="POST">

<input
type="hidden"
name="post"
value="<?=$post['id']?>">

<input
type="hidden"
name="parent"
value="<?=$c['id']?>">

<input
name="comment"
placeholder="Reply">

<button>

Reply

</button>

</form>

<?php

}

?>

<form

action="comment.php"

method="POST"

>

<input

type="hidden"

name="post"

value=

"<?=$post['id']?>"

>

<input

name="comment"

placeholder=

"Write comment..."

required>

<button>

COMMENT

</button>

</form>

</div>

<?php

}

?>

</div>

</body>

</html>
