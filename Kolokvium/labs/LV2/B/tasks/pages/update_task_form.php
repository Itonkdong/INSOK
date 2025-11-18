<?php

if (!isset($_GET["id"]))
{
    header("Location: index.php");
    exit();
}


include "../../utils/helper_functions.php";

session_start();

$errors = $_SESSION["errors"] ?? null;

if (isset($_SESSION["data"]))
{
    $existing_data = $_SESSION["data"];
    $id = $existing_data["id"];
    $current_title = $existing_data["title"] ?? "";
    $current_priority = $existing_data["priority"] ?? "";
    $current_status = $existing_data["status"] ?? "";
    $current_date = $existing_data["date"] ?? "";
}
else
{
    $id = $_GET["id"];

    $db = connectDatabase();

    $stmt = $db->prepare("select * from tasks where id=:id");
    $stmt->bindValue(":id", $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $task = $result->fetchArray(SQLITE3_ASSOC);

    $id = $task["id"];
    $current_title = $task["title"] ?? "";
    $current_priority = $task["priority"] ?? "";
    $current_status = $task["status"] ?? "";
    $current_date = $task["date"] ?? "";

}

unset($_SESSION["errors"]);
unset($_SESSION["data"]);


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Update Task</title>
</head>
<body>

<?php if (isset($errors)):?>
    <div>
        Errors:
        <br>
        <?php foreach ($errors as $error)
        {
            echo nl2br("$error\n");
        } ?>
    </div>

<?php endif ?>

<form method="post" action="../handlers/update_task.php">
    <input type="hidden" name="id" value="<?php echo $id ?>">
    <div>
        <label for="title">Title</label>
        <input type="text" id="title" name="title" required value="<?php echo $current_title ?>">
    </div>
    <div>
        <label for="date">Date</label>
        <input type="date" id="date" name="date" required  value="<?php echo $current_date ?>">
    </div>

    <div>
        <label for="priority">Priority</label>
        <select id="priority" name="priority" required>
            <option>Select Priority</option>
            <option value="Low" <?php echo ($current_priority === "Low") ? "selected" : "" ?> >Low</option>
            <option value="Medium" <?php echo ($current_priority === "Medium") ? "selected" : "" ?>>Medium</option>
            <option value="High" <?php echo ($current_priority === "High") ? "selected" : "" ?>>High</option>
        </select>
    </div>
    <div>
        <label for="status">Status</label>
        <input type="text" id="status" name="status" required value="<?php echo $current_status ?>" >
    </div>
    <button>Update Task</button>

</form>
</body>
</html>
