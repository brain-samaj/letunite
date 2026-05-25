<?php
require "db.php";

try {
    $db->exec("ALTER TABLE users ADD COLUMN last_seen DATETIME DEFAULT CURRENT_TIMESTAMP");
    echo "SUCCESS: last_seen added";
} catch (Exception $e) {
    echo "ERROR (maybe already exists): " . $e->getMessage();
}
?>
