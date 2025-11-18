<?php


include_once "../../utils/helper_functions.php";
include_once "../jwt_helper.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $db = connectDatabase();

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("select * from users where username = :username");
    $stmt->bindValue(":username", $username, SQLITE3_TEXT);
    $result = $stmt->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if ($user)
    {
        if (password_verify($password,  $user["password"]))
        {
            $JWT = createJWT($user["id"], $user["username"], $user["role"]);
            session_start();
            $_SESSION["jwt"] = $JWT;
            header("Location: ../../cameras/pages/index.php");
            exit();
        }
        else
        {
            echo "Invalid Password. Try again\n";
            exit();
        }
    }
    else
    {
        echo "User does not exist\n";
        exit();
    }

}
else
{
    echo "Invalid method\n";
}
