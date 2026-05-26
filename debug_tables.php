<?php
require "db.php";

try {

    $tables = $db->query("
        SELECT tablename
        FROM pg_tables
        WHERE schemaname = 'public'
    ");

    echo "<h2>DATABASE TABLES</h2><br>";

    foreach($tables as $t){
        echo $t['tablename'] . "<br>";
    }

} catch(Exception $e){
    echo "Error: " . $e->getMessage();
}
?>
