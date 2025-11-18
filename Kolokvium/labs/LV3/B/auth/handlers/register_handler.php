<?php

include_once "../../utils/helper_functions.php";

$minLengthUsername = 3;
$minLengthPassword = 6;

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (strlen($username) < $minLengthUsername || strlen($password) < $minLengthPassword )
    {
        echo "Username or password is not long enough\n";
        exit();
    }
    $db = connectDatabase();
    $stmt = $db->prepare("select * from main.users where username = :username");
    $stmt->bindValue(":username", $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);
    if ($user)
    {
        echo "Username already exists\, try another one\n";
        exit();
    }


    $stmt = $db->prepare("insert into users(username, password, role) values (:username, :password, :role)");
    $stmt->bindValue(":username", $username, SQLITE3_TEXT);
    $stmt->bindValue(":password", password_hash($password, PASSWORD_DEFAULT), SQLITE3_TEXT);
    $stmt->bindValue(":role", "User", SQLITE3_TEXT);
    $result = $stmt->execute();
    if ($result)
    {
        header("Location: ../../events/pages/index.php");
        exit();
    }
    else
    {
        echo "Error: {$db->lastErrorMsg()}\n";
        exit();
    }

}
else
{
    echo "Invalid method\n";
    exit();
}
