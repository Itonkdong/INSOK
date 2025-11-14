<?php
include 'db_connection.php';

session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

unset($_SESSION['errors']);
unset($_SESSION['form_data']);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $db = connectDatabase();

    $stmt = $db->prepare("SELECT * FROM tasks WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $task = $result->fetchArray(SQLITE3_ASSOC);

    $db->close();
} else {
    die("Invalid task ID.");
}

$current_title = !empty($form_data['title']) ? $form_data['title'] : ($task['title'] ?? '');
$current_date = !empty($form_data['date']) ? $form_data['date'] : ($task['date'] ?? '');
$current_priority = !empty($form_data['priority']) ? $form_data['priority'] : ($task['priority'] ?? '');
$current_status = !empty($form_data['status']) ? $form_data['status'] : ($task['status'] ?? '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Task</title>
    <style>
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<h1>Update Task</h1>

<?php if (!empty($errors)): ?>
    <div class="error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($task): ?>
    <form action="update_task.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($task['id']); ?>">
        <label for="title">Title:</label>
        <input id="title" type="text" name="title" value="<?php echo htmlspecialchars($current_title); ?>" required><br><br>
        <label for="date">Date:</label>
        <input id="date" type="date" name="date" value="<?php echo htmlspecialchars($current_date); ?>" required><br><br>
        <label for="priority">Priority:</label>
        <input id="priority" type="text" name="priority" value="<?php echo htmlspecialchars($current_priority); ?>" required><br><br>
        <label for="status">Status:</label>
        <input id="status" type="text" name="status" value="<?php echo htmlspecialchars($current_status); ?>" required><br><br>
        <button type="submit">Update Task</button>
    </form>
<?php else: ?>
    <p>Task not found.</p>
<?php endif; ?>
</body>
</html>