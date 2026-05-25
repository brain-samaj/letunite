<?php
require "db.php";

try {
    $db->exec("ALTER TABLE users ADD COLUMN profile_pic TEXT DEFAULT ''");
} catch(Exception $e) {}

try {
    $db->exec("ALTER TABLE users ADD COLUMN last_seen DATETIME DEFAULT CURRENT_TIMESTAMP");
} catch(Exception $e) {}

echo "FORCE FIX DONE";
?>
