<?php

/* =========================
   DATABASE CONNECTION
========================= */

$dbPath = __DIR__ . "/letunite.db";

$db = new PDO("sqlite:" . $dbPath);

$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

/* =========================
   AUTO DATABASE SETUP
   (RUNS EVERY TIME SAFELY)
========================= */

/* USERS */
$db->exec("
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY,
    name TEXT,
    email TEXT UNIQUE,
    password TEXT,
    joined DATETIME DEFAULT CURRENT_TIMESTAMP,
    profile_pic TEXT DEFAULT '',
    last_seen DATETIME DEFAULT CURRENT_TIMESTAMP
)");

/* POSTS */
$db->exec("
CREATE TABLE IF NOT EXISTS posts (
    id INTEGER PRIMARY KEY,
    user_id INTEGER,
    content TEXT,
    created DATETIME DEFAULT CURRENT_TIMESTAMP
)");

/* COMMENTS */
$db->exec("
CREATE TABLE IF NOT EXISTS comments (
    id INTEGER PRIMARY KEY,
    user_id INTEGER,
    post_id INTEGER,
    comment TEXT,
    created DATETIME DEFAULT CURRENT_TIMESTAMP
)");

/* LIKES */
$db->exec("
CREATE TABLE IF NOT EXISTS likes (
    id INTEGER PRIMARY KEY,
    user_id INTEGER,
    post_id INTEGER
)");

/* SHARES */
$db->exec("
CREATE TABLE IF NOT EXISTS shares (
    id INTEGER PRIMARY KEY,
    user_id INTEGER,
    post_id INTEGER,
    created DATETIME DEFAULT CURRENT_TIMESTAMP
)");

/* CONNECTS */
$db->exec("
CREATE TABLE IF NOT EXISTS connects (
    id INTEGER PRIMARY KEY,
    sender INTEGER,
    receiver INTEGER,
    status TEXT
)");

/* MESSAGES (CHAT SYSTEM) */
$db->exec("
CREATE TABLE IF NOT EXISTS messages (
    id INTEGER PRIMARY KEY,
    sender INTEGER,
    receiver INTEGER,
    message TEXT,
    image TEXT,
    video TEXT,
    audio TEXT,
    voice TEXT,
    created DATETIME DEFAULT CURRENT_TIMESTAMP,
    seen INTEGER DEFAULT 0
)");

/* FEEDS (OPTIONAL FEATURE) */
$db->exec("
CREATE TABLE IF NOT EXISTS feeds (
    id INTEGER PRIMARY KEY,
    user_id INTEGER,
    text TEXT,
    created DATETIME DEFAULT CURRENT_TIMESTAMP
)");

?>
