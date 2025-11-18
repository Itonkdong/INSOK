<?php
include 'db_connection.php';
include_once "../auth/jwt_helper.php";
loginRequired();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $db = connectDatabase();

    $stmt = $db->prepare("select * from payments where id=:id");
    $stmt->bindValue(":id", $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $payment = $result->fetchArray(SQLITE3_ASSOC);
    if ($payment["amount"] > 100)
    {
        $_SESSION["errors"] = ["Cant delete payments with amount more then 100"];
        header("Location: index.php");
        exit();
    }

    // Delete student by ID
    $stmt = $db->prepare("DELETE FROM payments WHERE id = :id");
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