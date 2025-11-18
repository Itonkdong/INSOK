<?php

include_once "../payments/db_connection.php";
// Поврзување со базата
try {
    $db = connectDatabase();

    // Креирање на табела за корисници ако не постои
    $query = "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        password TEXT NOT NULL,
        role TEXT NOT NULL
    )";
    $db->exec($query);  // Извршување на SQL за креирање на табела
} catch (PDOException $e) {
    // Ако се појави грешка при поврзување, испечатете ја и прекинете го извршувањето на скриптата
    die("Поврзувањето со базата на податоци не успеа: " . $e->getMessage());
}
?>