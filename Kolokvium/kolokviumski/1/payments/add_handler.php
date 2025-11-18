<?php
// Include the database connection file
include 'db_connection.php';
include_once "../auth/jwt_helper.php";
loginRequired();

// Check if the request method is POST to handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form values from the POST request, with basic validation
    $name = $_POST['name'] ?? '';
    $date = $_POST['date'] ?? '';
    $amount = $_POST['amount'] ?? 0.0;
    $type = $_POST['type'] ?? '';

    // Validate required fields
    if (empty($name) || empty($date) || empty($type)|| $amount <= 0) {
        echo "Please fill in all required fields correctly.";
        exit;
    }
    $amount = floatval($amount);

    // Connect to the SQLite database
    $db = connectDatabase();

    // Prepare and execute the insert statement
    $stmt = $db->prepare("INSERT INTO payments (name, date, amount, type) VALUES (:name, :date, :amount, :type)");
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    $stmt->bindValue(':amount', $amount, SQLITE3_FLOAT);
    $stmt->bindValue(':type', $type, SQLITE3_TEXT);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Redirect back to the view page
        header("Location: index.php");
    } else {
        echo "Error adding payment: " . $db->lastErrorMsg();
    }

    // Close the database connection
    $db->close();
} else {
    // If not a POST request, display an error message
    echo "Invalid request method. Please submit the form to add a payment.";
}
?>