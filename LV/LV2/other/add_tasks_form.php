<?php
session_start();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

unset($_SESSION['errors']);
unset($_SESSION['form_data']);

$current_title = $form_data['title'] ?? '';
$current_date = $form_data['date'] ?? '';
$current_priority = $form_data['priority'] ?? '';
$current_status = $form_data['status'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task</title>
    <style>
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<h1>Add Task</h1>

<?php if (!empty($errors)): ?>
    <div class="error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="add_task.php" method="POST">
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($current_title); ?>" required>
    <br/>
    <label for="date">Date:</label>
    <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($current_date); ?>" required>
    <br/>
    <label for="priority">Priority:</label>
    <input type="text" name="priority" id="priority" value="<?php echo htmlspecialchars($current_priority); ?>" required>
    <br/>
    <label for="status">Status:</label>
    <input type="text" name="status" id="status" value="<?php echo htmlspecialchars($current_status); ?>" required>
    <br/>
    <button type="submit">Add Task</button>
</form>
</body>
</html>
