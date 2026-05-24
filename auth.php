<?php

function profilePic($user){

if(

!empty(

$user['profile_pic']

)

&&

file_exists(

"uploads/".
$user['profile_pic']

)

){

return

"uploads/".
$user['profile_pic'];

}

return

"assets/default.png";

}

?>
