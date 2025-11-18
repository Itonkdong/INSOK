<?php

include "../../utils/helper_functions.php";

session_start();

$db = connectDatabase();

if (isset($_GET["filter"]))
{
    $filter = $_GET["filter"];
    $stmt = $db->prepare("select * from tasks where status=:status");
    $stmt->bindValue(":status", $filter, SQLITE3_TEXT);
    $result = $stmt->execute();
}
else
{
    $result = $db->query("select * from tasks");
}


$errors = $_SESSION["errors"] ?? null;
unset($_SESSION["errors"]);


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tasks</title>
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


<?php if (isset($errors)): ?>
    <div>
        Errors:
        <br>
        <?php foreach ($errors as $error): ?>
            <div>
                <?php echo $error ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


<div>
    <a href="index.php"> Show All</a>
    <a href="index.php?filter=Pending">Pending</a>
    <a href="index.php?filter=Done">Done</a>
</div>

<a href="add_task_form.php">Add Task</a>
<table>
    <tr>
        <th>Title</th>
        <th>Date</th>
        <th>Priority</th>
        <th>Status</th>
        <th>Actions</th>
    </tr>
    <?php if ($result):
        while ($task = $result->fetchArray(SQLITE3_ASSOC)) : ?>

            <tr>
                <td><?php echo $task["title"] ?></td>
                <td><?php echo $task["date"] ?></td>
                <td><?php echo $task["priority"] ?></td>
                <td><?php echo $task["status"] ?></td>
                <td>
                    <form method="get" action="update_task_form.php">
                        <input type="hidden" name="id" value="<?php echo $task["id"] ?>
                        ">
                        <button>Update</button>
                    </form>

                    <form method="post" action="../handlers/delete_task.php">
                        <input type="hidden" name="id" value="<?php echo $task["id"] ?>">
                        <button>Delete</button>
                    </form>
                </td>
            </tr>

        <?php endwhile ?>

    <?php endif; ?>
</table>


</body>
</html>
