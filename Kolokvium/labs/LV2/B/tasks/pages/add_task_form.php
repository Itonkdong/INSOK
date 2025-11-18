<?php

session_start();


$errors = $_SESSION["errors"] ?? null;


if (isset($_SESSION["data"]))
{
    $existing_data = $_SESSION["data"];
    $current_title = $existing_data["title"] ?? "";
    $current_priority = $existing_data["priority"] ?? "";
    $current_status = $existing_data["status"] ?? "";
    $current_date = $existing_data["date"] ?? "";

}
else
{
    $current_title = "";
    $current_priority = "";
    $current_status = "";
    $current_date = "";
}

unset($_SESSION["data"]);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Task</title>
</head>
<body>
<?php if (isset($errors)): ?>
    <div>
        Errors:
        <br>
        <?php foreach ($errors as $error)
        {
            echo nl2br("$error\n");
        } ?>
    </div>

<?php endif ?>

<form method="post" action="../handlers/add_task.php">
    <div>
        <label for="title">Title</label>
        <input type="text" id="title" name="title" required value="<?php echo $current_title ?>">
    </div>
    <div>
        <label for="date">Date</label>
        <input type="date" id="date" name="date" required value="<?php echo $current_date ?>">
    </div>

    <div>
        <label for="priority">Priority</label>
        <select id="priority" name="priority" required>
            <option>Select Priority</option>
            <option value="Low" <?php echo ($current_priority === "Low") ? "selected" : "" ?>>Low</option>
            <option value="Medium" <?php echo ($current_priority === "Medium") ? "selected" : "" ?>>Medium</option>
            <option value="High" <?php echo ($current_priority === "High") ? "selected" : "" ?>>High</option>
        </select>
    </div>
    <div>
        <label for="status">Status</label>
        <input type="text" id="status" name="status" required value="<?php echo $current_status ?>">
    </div>
    <button>Add Task</button>

</form>
</body>
</html>
