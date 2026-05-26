<?php

require "db.php";

try{

$db->exec("

CREATE TABLE IF NOT EXISTS users(

id SERIAL PRIMARY KEY,

name TEXT NOT NULL,

email TEXT UNIQUE NOT NULL,

password TEXT NOT NULL,

profile_pic TEXT DEFAULT '',

country TEXT DEFAULT '',

city TEXT DEFAULT '',

dob DATE,

gender TEXT DEFAULT '',

marital_status TEXT DEFAULT '',

joined TIMESTAMP
DEFAULT CURRENT_TIMESTAMP,

last_seen BIGINT

)

");

$db->exec("

CREATE TABLE IF NOT EXISTS posts(

id SERIAL PRIMARY KEY,

user_id INTEGER,

content TEXT,

created TIMESTAMP
DEFAULT CURRENT_TIMESTAMP

)

");

$db->exec("

CREATE TABLE IF NOT EXISTS comments(

id SERIAL PRIMARY KEY,

user_id INTEGER,

post_id INTEGER,

comment TEXT,

created TIMESTAMP
DEFAULT CURRENT_TIMESTAMP

)

");

$db->exec("

CREATE TABLE IF NOT EXISTS likes(

id SERIAL PRIMARY KEY,

user_id INTEGER,

post_id INTEGER

)

");

$db->exec("

CREATE TABLE IF NOT EXISTS shares(

id SERIAL PRIMARY KEY,

user_id INTEGER,

post_id INTEGER,

created TIMESTAMP
DEFAULT CURRENT_TIMESTAMP

)

");

$db->exec("

CREATE TABLE IF NOT EXISTS connects(

id SERIAL PRIMARY KEY,

sender INTEGER,

receiver INTEGER,

status TEXT

)

");

$db->exec("

CREATE TABLE IF NOT EXISTS messages(

id SERIAL PRIMARY KEY,

sender INTEGER,

receiver INTEGER,

message TEXT,

image TEXT,

video TEXT,

audio TEXT,

voice TEXT,

seen INTEGER DEFAULT 0,

created TIMESTAMP
DEFAULT CURRENT_TIMESTAMP

)

");

$db->exec("

CREATE TABLE IF NOT EXISTS feeds(

id SERIAL PRIMARY KEY,

user_id INTEGER,

text TEXT,

created TIMESTAMP
DEFAULT CURRENT_TIMESTAMP

)

");

echo
"DATABASE INITIALIZED SUCCESSFULLY";

}catch(Exception $e){

echo
$e->getMessage();

}

?>
