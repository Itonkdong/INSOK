<?php
include 'db_connection.php';
include 'helper_functions.php';

session_start();

loginRequired();

$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

unset($_SESSION['errors']);
unset($_SESSION['form_data']);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $db = connectDatabase();

    $stmt = $db->prepare("SELECT * FROM events WHERE id = :id");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $event = $result->fetchArray(SQLITE3_ASSOC);

    $db->close();
} else {
    die("Invalid event ID.");
}

$current_name = !empty($form_data['name']) ? $form_data['name'] : ($event['name'] ?? '');
$current_date = !empty($form_data['date']) ? $form_data['date'] : ($event['date'] ?? '');
$current_location = !empty($form_data['location']) ? $form_data['location'] : ($event['location'] ?? '');
$current_type = !empty($form_data['type']) ? $form_data['type'] : ($event['type'] ?? '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Event</title>
    <style>
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<h1>Update Event</h1>

<?php if (!empty($errors)): ?>
    <div class="error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($event): ?>
    <form action="update_event.php" method="post">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($event['id']); ?>">
        <label for="name">Name:</label>
        <input id="name" type="text" name="name" value="<?php echo htmlspecialchars($current_name); ?>" required><br><br>
        <label for="date">Date:</label>
        <input id="date" type="date" name="date" value="<?php echo htmlspecialchars($current_date); ?>" required><br><br>
        <label for="location">Location:</label>
        <input id="location" type="text" name="location" value="<?php echo htmlspecialchars($current_location); ?>" required><br><br>
        <label for="type">Type:</label>
        <select id="type" name="type" required>
            <option value="">Select Type</option>
            <option value="Private" <?php echo ($current_type === 'Private') ? 'selected' : ''; ?>>Private</option>
            <option value="Public" <?php echo ($current_type === 'Public') ? 'selected' : ''; ?>>Public</option>
        </select><br><br>
        <button type="submit">Update Event</button>
    </form>
<?php else: ?>
    <p>Event not found.</p>
<?php endif; ?>
</body>
</html>