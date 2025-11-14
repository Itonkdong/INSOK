<?php
session_start();

require '../events/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = connectDatabase();
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (strlen($username) < 3 || strlen($password) < 6) {
        die('Username has to have length of at least 3 and password a length of at least 6');
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, 'user')");
    try {
        $stmt->bindValue(":username", $username, SQLITE3_TEXT);
        $stmt->bindValue(":password", $hashedPassword, SQLITE3_TEXT);
        $stmt->bindValue(":role", 'user', SQLITE3_TEXT);
        $stmt->execute();

        echo "Registration successful <a href='login.php'>Login Here</a>";
    } catch (Exception $e) {
        if ($e->getCode() == 23000) {
            die("Username already exists!");
        } else {
            die("Error: " . $e->getMessage());
        }
    }
}
?>