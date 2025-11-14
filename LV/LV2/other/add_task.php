<?php
// Include the database connection file
include 'db_connection.php';
include 'helper_functions.php';

// Check if the request method is POST to handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form values from the POST request, with basic validation
    $title = $_POST['title'] ?? '';
    $date = $_POST['date'] ?? '';
    $priority = $_POST['priority'] ?? '';
    $status = $_POST['status'] ?? '';

    $errors = [];

    // Validate required fields
    if (empty($title) || empty($date) || empty($priority) || empty($status) ) {
        $errors[] = "Please fill in all required fields correctly.";
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
        header("Location: add_tasks_form.php");
        exit();
    }

    // Connect to the SQLite database
    $db = connectDatabase();

    // Prepare and execute the insert statement
    $stmt = $db->prepare("INSERT INTO tasks (title, date, priority, status) VALUES (:title, :date, :priority, :status)");
    $stmt->bindValue(':title', $title, SQLITE3_TEXT);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    $stmt->bindValue(':priority', $priority, SQLITE3_TEXT);
    $stmt->bindValue(':status', $status, SQLITE3_TEXT);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect back to the view page
        header("Location: index.php");
    } else {
        session_start();
        $_SESSION['errors'] = ["Error adding task: " . $db->lastErrorMsg()];
        $_SESSION['form_data'] = $_POST;
        $db->close();
        header("Location: add_tasks_form.php");
        exit();
    }

    // Close the database connection
    $db->close();
} else {
    // If not a POST request, display an error message
    echo "Invalid request method. Please submit the form to add a task.";
}
?>