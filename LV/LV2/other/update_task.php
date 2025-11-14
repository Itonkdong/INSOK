<?php
include 'db_connection.php';
include 'helper_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $title = trim($_POST['title']);
    $date = $_POST['date'];
    $priority = trim($_POST['priority']);
    $status = trim($_POST['status']);

    $errors = [];

    // Validate inputs
    if (empty($title)) {
        $errors[] = "Title cannot be empty.";
    }

    if (!validPriority($priority)) {
        $errors[] = "Invalid priority. Valid options are: Low, Medium, High";
    }

    if (!validStatus($status)) {
        $errors[] = "Invalid status. Valid options are: Pending, Done";
    }

    // If there are validation errors, redirect back to form with errors
    if (!empty($errors)) {
        session_start();
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: update_task_form.php?id=" . $id);
        exit();
    }

    $db = connectDatabase();

    // Update task details
    $stmt = $db->prepare("UPDATE tasks SET title = :title, date = :date, priority = :priority, status = :status WHERE id = :id");
    $stmt->bindValue(':title', $title, SQLITE3_TEXT);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    $stmt->bindValue(':priority', $priority, SQLITE3_TEXT);
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    $result = $stmt->execute();

    if (!$result) {
        session_start();
        $_SESSION['errors'] = ["Error updating task: " . $db->lastErrorMsg()];
        $_SESSION['form_data'] = $_POST;
        $db->close();
        header("Location: update_task_form.php?id=" . $id);
        exit();
    }

    // Check if any rows were affected
    if ($db->changes() === 0) {
        session_start();
        $_SESSION['errors'] = ["No task found with ID: " . $id];
        $_SESSION['form_data'] = $_POST;
        $db->close();
        header("Location: update_task_form.php?id=" . $id);
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