<?php
include 'helper_functions.php';

session_start();
loginRequired();
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];

unset($_SESSION['errors']);
unset($_SESSION['form_data']);

$current_name = $form_data['name'] ?? '';
$current_date = $form_data['date'] ?? '';
$current_location = $form_data['location'] ?? '';
$current_type = $form_data['type'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Event</title>
    <style>
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<h1>Add Event</h1>

<?php if (!empty($errors)): ?>
    <div class="error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="add_event.php" method="POST">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($current_name); ?>" required>
    <br/>
    <label for="date">Date:</label>
    <input type="date" name="date" id="date" value="<?php echo htmlspecialchars($current_date); ?>" required>
    <br/>
    <label for="location">Location:</label>
    <input type="text" name="location" id="location" value="<?php echo htmlspecialchars($current_location); ?>"
           required>
    <br/>
    <label for="type">Type:</label>
    <select name="type" id="type" required>
        <option value="">Select Type</option>
        <option value="Private" <?php echo ($current_type === 'Private') ? 'selected' : ''; ?>>Private</option>
        <option value="Public" <?php echo ($current_type === 'Public') ? 'selected' : ''; ?>>Public</option>
    </select>
    <br/>
    <button type="submit">Add Event</button>
</form>
</body>
</html>
