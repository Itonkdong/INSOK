<?php
include 'db_connection.php';

include_once "../auth/jwt_helper.php";
loginRequired();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $db = connectDatabase();

    // Fetch the current details of the student
    $stmt = $db->prepare("SELECT * FROM payments WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $payment = $result->fetchArray(SQLITE3_ASSOC);

    $db->close();
} else {
    die("Invalid payment ID.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Payment</title>
</head>
<body>
<h1>Update Payment</h1>

<?php if ($payment): ?>
    <form action="update_handler.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($payment['id']); ?>">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($payment['name']); ?>" required><br><br>
        <label for="date">Date:</label>
        <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($payment['date']); ?>" required><br><br>
        <label for="amount">Amount:</label>
        <input type="number" name="amount" id="amount" value="<?php echo htmlspecialchars($payment['amount']); ?>" required><br><br>
        <label for="type">Type</label>
        <select id="type" name="type">
            <option value="Cash" <?php echo ($payment["type"] === "Cash"? "selected" : "") ?>>Cash</option>
            <option value="Card" <?php echo ($payment["type"] === "Card"? "selected" : "") ?>>Card</option>
        </select>
        <button type="submit">Update Payment</button>
    </form>
<?php else: ?>
    <p>Payment not found.</p>
<?php endif; ?>
</body>
</html>