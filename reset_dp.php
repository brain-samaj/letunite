<?php
require "db.php";

/*
  FULL DATABASE SELF-REPAIR FOR LETUNITE
  Fixes missing columns + ensures Render DB matches your app
*/

try {

    /* USERS */
    $db->exec("
        CREATE TABLE IF NOT EXISTS users(
            id INTEGER PRIMARY KEY,
            name TEXT,
            email TEXT UNIQUE,
            password TEXT,
            joined DATETIME DEFAULT CURRENT_TIMESTAMP,
            profile_pic TEXT DEFAULT '',
            last_seen DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    /* POSTS */
    $db->exec("
        CREATE TABLE IF NOT EXISTS posts(
            id INTEGER PRIMARY KEY,
            user_id INTEGER,
            content TEXT,
            created DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    /* COMMENTS */
    $db->exec("
        CREATE TABLE IF NOT EXISTS comments(
            id INTEGER PRIMARY KEY,
            user_id INTEGER,
            post_id INTEGER,
            comment TEXT,
            created DATETIME DEFAULT CURRENT_TIMESTAMP
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

    /* SHARES */
    $db->exec("
        CREATE TABLE IF NOT EXISTS shares(
            id INTEGER PRIMARY KEY,
            user_id INTEGER,
            post_id INTEGER,
            created DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    /* CHAT CONNECTIONS */
    $db->exec("
        CREATE TABLE IF NOT EXISTS connects(
            id INTEGER PRIMARY KEY,
            sender INTEGER,
            receiver INTEGER,
            status TEXT
        )
    ");

    /* MESSAGES */
    $db->exec("
        CREATE TABLE IF NOT EXISTS messages(
            id INTEGER PRIMARY KEY,
            sender INTEGER,
            receiver INTEGER,
            message TEXT,
            image TEXT,
            voice TEXT,
            created DATETIME DEFAULT CURRENT_TIMESTAMP,
            seen INTEGER DEFAULT 0
        )
    ");

    /* FEEDS */
    $db->exec("
        CREATE TABLE IF NOT EXISTS feeds(
            id INTEGER PRIMARY KEY,
            user_id INTEGER,
            text TEXT,
            created DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    echo "✅ LETUNITE DATABASE FULLY REPAIRED";

} catch (Exception $e) {

    echo "❌ ERROR: " . $e->getMessage();

}
?>
