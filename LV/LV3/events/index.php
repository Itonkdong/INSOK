<?php
// Include the database connection file
include 'db_connection.php';
include 'helper_functions.php';

// Start session to handle messages
session_start();

loginRequired();

// Get messages from session
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';

// Clear messages from session
unset($_SESSION['error_message']);
unset($_SESSION['success_message']);

// Connect to the SQLite database
$db = connectDatabase();

// Fetch all students from the database
if (isset($_GET["filter"]))
{
    $filter = $_GET["filter"];

    $stm = $db->prepare("SELECT * FROM events WHERE type = :type");
    $stm->bindValue(":type", $filter, SQLITE3_TEXT);
    $result = $stm->execute();

}
else
{
    $query = "SELECT * FROM events";
    $result = $db->query($query);
}

if (!$result) {
    die("Error fetching events: " . $db->lastErrorMsg());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View events</title>
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
<div style="display: flex; align-items: center; justify-content: space-between">
    <h1>Events List</h1>
    <a href="add_event_form.php">
        Add event
    </a>
    <a href="index.php">Show All</a>
    <a href="index.php?filter=Private">Show only Private</a>
    <a href="index.php?filter=Public">Show only Public</a>
</div>

<?php if ($error_message): ?>
    <div style="color: red;">
        <?php echo htmlspecialchars($error_message); ?>
    </div>
<?php endif; ?>

<?php if ($success_message): ?>
    <div style="color: green;">
        <?php echo htmlspecialchars($success_message); ?>
    </div>
<?php endif; ?>

<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Date</th>
        <th>Priority</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php if ($result): ?>
        <?php while ($event = $result->fetchArray(SQLITE3_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars($event['id']); ?></td>
                <td><?php echo htmlspecialchars($event['name']); ?></td>
                <td><?php echo htmlspecialchars($event['date']); ?></td>
                <td><?php echo htmlspecialchars($event['location']); ?></td>
                <td><?php echo htmlspecialchars($event['type']); ?></td>
                <td>
                    <form action="delete_event.php" method="post" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                        <button type="submit">Delete</button>
                    </form>
                    <form action="update_event_form.php" method="get" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $event['id']; ?>">
                        <button type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5">No events found.</td>
        </tr>
    <?php endif; ?>

    <a href="../auth/logout_handler.php" class="text-white bg-red-500 hover:bg-red-600 px-4 py-2 rounded-md text-lg">
        Logout
    </a>
    </tbody>
</table>
</body>
</html>