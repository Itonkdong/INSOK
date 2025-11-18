<?php
// Include the database connection file
include 'db_connection.php';



session_start();

include_once "../auth/jwt_helper.php";
loginRequired();

$errors = $_SESSION["errors"] ?? null;

// Connect to the SQLite database
$db = connectDatabase();

// Fetch all payments from the database
$query = "SELECT * FROM payments";
$result = $db->query($query);

if (!$result) {
    die("Error fetching payments: " . $db->lastErrorMsg());
}

unset($_SESSION["errors"])



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Payments</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>
<?php if ($errors): ?>
<?php foreach ($errors as $error): ?>
<div>
    Error: <?php echo $error ?>

</div>
<?php endforeach; ?>
<?php endif; ?>


<div style="display: flex; align-items: center; justify-content: space-between">
    <h1>Payments List</h1>
    <a href="add_page.php">
        Add Payment
    </a>
</div>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Date</th>
        <th>Amount</th>
        <th>Type</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($result): ?>
        <?php while ($payment = $result->fetchArray(SQLITE3_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars($payment['id']); ?></td>
                <td><?php echo htmlspecialchars($payment['name']); ?></td>
                <td><?php echo htmlspecialchars($payment['date']); ?></td>
                <td><?php echo htmlspecialchars($payment['amount']); ?></td>
                <td><?php echo htmlspecialchars($payment['type']); ?></td>
                <td>
                    <form action="delete_handler.php" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $payment['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                    <form action="update_page.php" method="get" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $payment['id']; ?>">
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No payments found.</td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<a href="../auth/logout_handler.php"> Logout</a>
</body>
</html>