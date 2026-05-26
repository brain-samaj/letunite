<?php
require "db.php";

try {

    $db->exec("ALTER TABLE users ADD COLUMN country TEXT");
} catch (Exception $e) {}

try {
    $db->exec("ALTER TABLE users ADD COLUMN city TEXT");
} catch (Exception $e) {}

try {
    $db->exec("ALTER TABLE users ADD COLUMN dob TEXT");
} catch (Exception $e) {}

try {
    $db->exec("ALTER TABLE users ADD COLUMN gender TEXT");
} catch (Exception $e) {}

try {
    $db->exec("ALTER TABLE users ADD COLUMN marital_status TEXT");
} catch (Exception $e) {}

echo "USER TABLE UPDATED SUCCESSFULLY";

?>
