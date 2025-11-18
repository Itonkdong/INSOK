<?php
include_once "../auth/jwt_helper.php";
loginRequired();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Payment</title>
</head>
<body>
<form action="add_handler.php" method="POST">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>
    <br />
    <label for="date">Date:</label>
    <input type="date" name="date" id="date" required>
    <br />
    <label for="amount">Amount:</label>
    <input type="number" name="amount" id="amount" required>
    <br />
    <label for="type">Type:</label>
    <select id="type" name="type">
        <option value="Cash">Cash</option>
        <option value="Card">Card</option>
    </select>
    <button type="submit">Add Payment</button>
</form>
</body>