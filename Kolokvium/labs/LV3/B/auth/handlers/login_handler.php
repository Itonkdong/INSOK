<?php

include_once "../../utils/helper_functions.php";
include_once "../jwt_helper.php";

$minLengthUsername = 3;
$minLengthPassword = 6;

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);


    $db = connectDatabase();
    $stmt = $db->prepare("select * from users where username = :username");
    $stmt->bindValue(":username", $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);
    if (!$user)
    {
        echo "Username does not exist. Please Register.\n";
        exit();
    }


    if (password_verify($password, $user["password"]))
    {
        session_start();
        $_SESSION["jwt"] = createJWT($user["id"], $user["username"], $user["role"]);
        header("Location: ../../events/pages/index.php");
        exit();
    }
    else
    {
        echo "Invalid password or username";
        exit();
    }

}
else
{
    echo "Invalid method\n";
    exit();
}
