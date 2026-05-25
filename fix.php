<?php
require "db.php";

try {
    $db->exec("ALTER TABLE users ADD COLUMN last_seen DATETIME DEFAULT CURRENT_TIMESTAMP");
    echo "Column added successfully";
} catch(Exception $e) {
    echo "Error or already exists: " . $e->getMessage();
}
?>
