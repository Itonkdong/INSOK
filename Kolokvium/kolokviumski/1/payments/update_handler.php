<?php
include 'db_connection.php';
include_once "../auth/jwt_helper.php";
loginRequired();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'] ?? '';
    $date = $_POST['date'] ?? '';
    $amount = $_POST['amount'] ?? 0.0;
    $type = $_POST['type'] ?? '';

    $db = connectDatabase();

    // Update student details
    $stmt = $db->prepare("UPDATE payments SET name = :name, date = :date, amount = :amount, type=:type WHERE id = :id");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    $stmt->bindValue(':amount', $amount, SQLITE3_FLOAT);
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    // Close the database connection
    $db->close();

    // Redirect back to the view page
    header("Location: index.php");
    exit();
} else {
    echo "Invalid request.";
}
?>