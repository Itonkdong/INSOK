<?php
include 'db_connection.php';
include 'helper_functions.php';
session_start();
loginRequired();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $db = connectDatabase();

    // First, check if the event is public or private
    $stmt = $db->prepare("SELECT type FROM events WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $event = $result->fetchArray(SQLITE3_ASSOC);

    if (!$event) {
        $db->close();
        $_SESSION['error_message'] = "Event not found.";
        header("Location: index.php");
        exit();
    }

    // Check if the event is private
    if ($event['type'] === 'Private') {
        $db->close();
        $_SESSION['error_message'] = "Private events can not be deleted";
        header("Location: index.php");
        exit();
    }

    // Delete event by ID (only if it's public)
    $stmt = $db->prepare("DELETE FROM events WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->execute();

    // Close the database connection
    $db->close();

    // Set success message
    $_SESSION['success_message'] = "Event deleted successfully.";

    // Redirect back to the view page
    header("Location: index.php");
    exit();
} else {
    $_SESSION['error_message'] = "Invalid request.";
    header("Location: index.php");
    exit();
}
?>