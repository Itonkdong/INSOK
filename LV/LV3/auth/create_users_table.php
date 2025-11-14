<?php
include "../events/db_connection.php";
try {
    $db = connectDatabase();
    $query = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        password TEXT NOT NULL,
        role TEXT NOT NULL
    )";

    if ($db->exec($query)) {
        echo "Table users created successfully.";
    } else {
        echo "Error creating table: " . $db->lastErrorMsg();
    }
} catch (Exception $e) {

    die("Database connection did not succeed: " . $e->getMessage());
}
?>