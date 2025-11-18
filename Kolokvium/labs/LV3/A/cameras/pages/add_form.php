<?php
session_start();
include_once '../../auth/jwt_helper.php';

requireLogin();

$errors = $S_SESSION["errors"] ?? null;

if (isset($S_SESSION["data"]))
{
    $data = $S_SESSION["data"];
    $current_name = $data["name"] ?? "";
    $current_location = $data["location"] ?? "";
    $current_price = $data["price"] ?? "";
    $current_type = $data["type"] ?? "";
    $current_date = $data["date"] ?? "";
}
else
{
    $current_name = "";
    $current_location = "";
    $current_price = "";
    $current_type = "";
    $current_date = "";
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add Camera</title>
</head>
<body>

<?php if (isset($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <div>
            Error: <?php echo $error ?>
        </div>
    <?php endforeach; ?>

<?php endif; ?>

<form action="../handlers/add_handler.php" method="post">
    <div>
        <label for="name">Name</label>
        <input id="name" name="name" type="text" <?php echo $current_name ?>
        >
    </div>
    <div>
        <label for="location">Location</label>
        <input id="location" name="location" type="text" <?php echo $current_location ?>>
    </div>
    <div>
        <label for="date">Date</label>
        <input id="date" name="date" type="date" <?php echo $current_date ?>>
    </div>
    <div>
        <label for="price">Price</label>
        <input id="price" name="price" type="number" <?php echo $current_price ?>>
    </div>
    <div>
        <label for="type">Type</label>
        <select id="type" name="type">
            <option value="Indoors" <?php echo ($current_type === 'Indoors') ? 'selected' : '' ?>
            >Indoors
            </option>
            <option value="Outdoors" <?php echo ($current_type === 'Outdoors') ? 'selected' : '' ?>
            >Outdoors
            </option>
        </select>

    </div>
    <button>Add Camera</button>

</form>


</body>
</html>
