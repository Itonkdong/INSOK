<?php
// Include the database connection file
include 'db_connection.php';
include 'helper_functions.php';

loginRequired();

// Check if the request method is POST to handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form values from the POST request, with basic validation
    $name = $_POST['name'] ?? '';
    $date = $_POST['date'] ?? '';
    $location = $_POST['location'] ?? '';
    $type = $_POST['type'] ?? '';

    $errors = [];

    // Validate required fields
    if (empty($name) || empty($date) || empty($location) || empty($type) ) {
        $errors[] = "Please fill in all required fields correctly.";
    }

    if (!validType($type)) {
        $errors[] = "Invalid type. Valid options are: Private, Public";
    }


    // If there are validation errors, redirect back to form with errors
    if (!empty($errors)) {
        session_start();
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        header("Location: add_event_form.php");
        exit();
    }

    // Connect to the SQLite database
    $db = connectDatabase();

    // Prepare and execute the insert statement
    $stmt = $db->prepare("INSERT INTO events (name, date, location, type) VALUES (:name, :date, :location, :type)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    $stmt->bindValue(':location', $location, SQLITE3_TEXT);
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect back to the view page
        header("Location: index.php");
    } else {
        session_start();
        $_SESSION['errors'] = ["Error adding task: " . $db->lastErrorMsg()];
        $_SESSION['form_data'] = $_POST;
        $db->close();
        header("Location: add_event_form.php");
        exit();
    }

    // Close the database connection
    $db->close();
} else {
    // If not a POST request, display an error message
    echo "Invalid request method. Please submit the form to add a task.";
}
?>