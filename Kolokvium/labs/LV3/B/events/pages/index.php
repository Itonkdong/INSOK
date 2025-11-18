<?php


include_once "../../utils/helper_functions.php";

session_start();
include_once "../../auth/jwt_helper.php";
requireLogin();


$errors = $_SESSION["errors"] ?? null;

$db = connectDatabase();
$result = $db->query("select * from events");

unset($_SESSION["errors"])


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Events</title>
</head>
<body>
<?php if ($errors): ?>
    <?php foreach ($errors as $error): ?>
        <div>
            Error: <?php echo $error ?>

        </div>
    <?php endforeach; ?>
<?php endif; ?>

<a href="create_page.php">Add Event</a>
<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Location</th>
        <th>Date</th>
        <th>Type</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($event = $result->fetchArray(SQLITE3_ASSOC)): ?>
    <tr>
        <td><?php echo $event["name"] ?></td>
        <td><?php echo $event["location"] ?></td>
        <td><?php echo $event["date"] ?></td>
        <td><?php echo $event["type"] ?></td>
        <td>
            <form style="display: inline;" action="update_page.php" method="get">
                <input type="hidden" id="id" name="id" value="<?php echo $event["id"] ?>">
                <button>Update</button>
            </form>
            <form style="display: inline;" action="../handlers/delete_handler.php" method="post">
                <input type="hidden" id="id" name="id" value="<?php echo $event["id"] ?>">
                <button>Delete</button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<a href="../../auth/handlers/logout_handler.php">Logout</a>

</body>
</html>
