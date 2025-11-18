<?php

include_once "../../utils/helper_functions.php";

session_start();
include_once "../../auth/jwt_helper.php";
requireLogin();

$errors = $_SESSION["errors"] ?? null;


if (isset($_SESSION["data"]))
{
    $data = $_SESSION["data"];
    $current_name = $data["name"] ?? "";
    $current_id = $data["id"] ?? "";
    $current_location = $data["location"] ?? "";
    $current_date = $data["date"] ?? "";
    $current_type = $data["type"] ?? "";
}
else
{
    if (!isset($_GET["id"]))
    {
        header("Location: index.php");
        exit();
    }

    $id = $_GET["id"];

    $db = connectDatabase();
    $stmt = $db->prepare("select * from events where id=:id");
    $stmt->bindValue(":id", $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $data = $result->fetchArray(SQLITE3_ASSOC);

    $current_name = $data["name"] ?? "";
    $current_id = $data["id"] ?? "";
    $current_location = $data["location"] ?? "";
    $current_date = $data["date"] ?? "";
    $current_type = $data["type"] ?? "";
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
    <title>Update Event</title>
</head>
<body>

<?php if ($errors): ?>
    <?php foreach ($errors as $error): ?>
        <div>
            Error: <?php echo $error ?>

        </div>
    <?php endforeach; ?>
<?php endif; ?>

<form action="../handlers/update_handler.php" method="post">
    <input type="hidden" id="id" name="id" value="<?php echo $current_id ?>">
    <div>
        <label for="name">Name</label>
        <input id="name" name="name" type="text" value="<?php echo $current_name ?>
        ">
    </div>
    <div>
        <label for="location">Location</label>
        <input id="location" name="location" type="text" value="<?php echo $current_location ?>">
    </div>
    <div>
        <label for="date">Date</label>
        <input id="date" name="date" type="date" value="<?php echo $current_date ?>">
    </div>
    <div>
        <label for="type">Type</label>
        <select id="type" name="type">
            <option value="Private" <?php echo $current_type === "Private" ? "selected" : "" ?> >Private</option>
            <option value="Public" <?php echo $current_type === "Public" ? "selected" : "" ?> >Public</option>
        </select>
    </div>
    <button>Update Event</button>
</form>

</body>
</html>
