<?php
include 'db_connection.php';
include 'helper_functions.php';

session_start();
loginRequired();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $date = $_POST['date'];
    $location = trim($_POST['location']);
    $type = trim($_POST['type']);

    $errors = [];

    // Validate inputs
    if (empty($name)) {
        $errors[] = "Name cannot be empty.";
    }

    if (!validType($type)) {
        $errors[] = "Invalid type. Valid options are: Private, Public";
    }


    // If there are validation errors, redirect back to form with errors
    if (!empty($errors)) {
        session_start();
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: update_event_form.php?id=" . $id);
        exit();
    }

    $db = connectDatabase();

    // Update task details
    $stmt = $db->prepare("UPDATE events SET name = :name, date = :date, location = :location, type = :type WHERE id = :id");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    $stmt->bindValue(':location', $location, SQLITE3_TEXT);
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    $result = $stmt->execute();

    if (!$result) {
        session_start();
        $_SESSION['errors'] = ["Error updating event: " . $db->lastErrorMsg()];
        $_SESSION['form_data'] = $_POST;
        $db->close();
        header("Location: update_event_form.php?id=" . $id);
        exit();
    }

    // Check if any rows were affected
    if ($db->changes() === 0) {
        session_start();
        $_SESSION['errors'] = ["No event found with ID: " . $id];
        $_SESSION['form_data'] = $_POST;
        $db->close();
        header("Location: update_event_form.php?id=" . $id);
        exit();
    }

    // Close the database connection
    $db->close();

    // Redirect back to the view page on success
    header("Location: index.php");
    exit();
} else {
    echo "Invalid request.";
}
?>