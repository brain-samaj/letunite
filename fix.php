<?php
require "db.php";

try {

    // Add profile_pic column safely
    $db->exec("
        ALTER TABLE users
        ADD COLUMN profile_pic TEXT DEFAULT ''
    ");

    echo "SUCCESS: profile_pic column added";

} catch (PDOException $e) {

    // If it already exists, SQLite will throw an error
    if (strpos($e->getMessage(), "duplicate column name") !== false) {
        echo "INFO: profile_pic column already exists";
    } else {
        echo "ERROR: " . $e->getMessage();
    }

}
?>
