<?php
require "db.php";

print_r($db->query("PRAGMA table_info(users)")->fetchAll());
?>
