<?php

include_once "../../utils/helper_functions.php";

session_start();
include_once '../../auth/jwt_helper.php';

requireLogin();

$errors = $S_SESSION["errors"] ?? null;

$db = connectDatabase();
$result = $db->query("select * from cameras");

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Camera</title>
</head>
<body>
<a href="add_form.php">Add Camera</a>
<?php if (isset($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <div>
            Error: <?php echo $error ?>
        </div>
    <?php endforeach; ?>

<?php endif; ?>

<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>Location</th>
        <th>Date</th>
        <th>Price</th>
        <th>Type</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php while ($camera = $result->fetchArray(SQLITE3_ASSOC)): ?>
    <tr>
        <td><?php echo $camera["name"] ?></td>
        <td><?php echo $camera["location"] ?></td>
        <td><?php echo $camera["date"] ?></td>
        <td><?php echo $camera["price"] ?></td>
        <td><?php echo $camera["type"] ?></td>
        <td>
            <form style="display: inline" action="update_form.php" method="get">
                <input type="hidden" id="id" name="id" value="<?php echo $camera["id"] ?>">
                <button>Update</button>
            </form>
            <form style="display: inline" action="../handlers/delete_handler.php" method="post">
                <input type="hidden" id="id" name="id" value="<?php echo $camera["id"] ?>">
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
