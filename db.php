<?php

/*
Render PostgreSQL Environment Variables:

DB_HOST
DB_NAME
DB_USER
DB_PASS
DB_PORT

*/

$host = getenv("DB_HOST");
$dbname = getenv("DB_NAME");
$user = getenv("DB_USER");
$pass = getenv("DB_PASS");
$port = getenv("DB_PORT") ?: "5432";

try {

    $db = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $pass
    );

    $db->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    $db->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

} catch(PDOException $e){

    die(
        "Database connection failed: "
        .$e->getMessage()
    );

}
?>
