<?php

include_once "../../utils/helper_functions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (!isset($username) || !isset($password))
    {
        echo "Username and password are required\n";
    }

    $db = connectDatabase();

    $stmt = $db->prepare("select * from users where username = :username");
    $stmt->bindValue(":username", $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user)
    {
        echo "Username already exists. User another\n";
        exit();

    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("insert into users(username, password, role) values (:username, :password, :role)");
    $stmt->bindValue(":username", $username, SQLITE3_TEXT);
    $stmt->bindValue(":password", $hashedPassword, SQLITE3_TEXT);
    $stmt->bindValue(":role", 'User', SQLITE3_TEXT);

    if ($stmt->execute())
    {
        header("Location: ../pages/login_form.php");
    }
    else
    {
        echo "Error: {$db->lastErrorMsg()}\n";
    }

}
else
{
    echo "Invalid method\n";
}
