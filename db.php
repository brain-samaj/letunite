<?php

$db=new PDO("sqlite:letunite.db");

$db->setAttribute(
PDO::ATTR_ERRMODE,
PDO::ERRMODE_EXCEPTION
);

/* USERS */

$db->exec("

CREATE TABLE IF NOT EXISTS users(

id INTEGER PRIMARY KEY,

name TEXT,

email TEXT UNIQUE,

password TEXT,

joined DATETIME
DEFAULT CURRENT_TIMESTAMP

)

");

/* POSTS */

$db->exec("

CREATE TABLE IF NOT EXISTS posts(

id INTEGER PRIMARY KEY,

user_id INTEGER,

content TEXT,

created DATETIME
DEFAULT CURRENT_TIMESTAMP

)

");

/* COMMENTS */

$db->exec("

CREATE TABLE IF NOT EXISTS comments(

id INTEGER PRIMARY KEY,

user_id INTEGER,

post_id INTEGER,

comment TEXT,

created DATETIME
DEFAULT CURRENT_TIMESTAMP

)

");

/* LIKES */

$db->exec("

CREATE TABLE IF NOT EXISTS likes(

id INTEGER PRIMARY KEY,

user_id INTEGER,

post_id INTEGER

)

");

/* SHARE */

$db->exec("

CREATE TABLE IF NOT EXISTS shares(

id INTEGER PRIMARY KEY,

user_id INTEGER,

post_id INTEGER,

created DATETIME
DEFAULT CURRENT_TIMESTAMP

)

");

/* CONNECT */

$db->exec("

CREATE TABLE IF NOT EXISTS connects(

id INTEGER PRIMARY KEY,

sender INTEGER,

receiver INTEGER,

status TEXT

)

");

/* CHAT */

$db->exec("

CREATE TABLE IF NOT EXISTS messages(

id INTEGER PRIMARY KEY,

sender INTEGER,

receiver INTEGER,

message TEXT,

created DATETIME
DEFAULT CURRENT_TIMESTAMP

)

");

/* FEEDS */

$db->exec("

CREATE TABLE IF NOT EXISTS feeds(

id INTEGER PRIMARY KEY,

user_id INTEGER,

text TEXT,

created DATETIME
DEFAULT CURRENT_TIMESTAMP

)

");

?>
