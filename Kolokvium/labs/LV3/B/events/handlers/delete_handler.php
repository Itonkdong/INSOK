<?php

include_once "../../utils/helper_functions.php";

include_once "../../auth/jwt_helper.php";
session_start();
requireLogin();

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $id = $_POST["id"];
    $db = connectDatabase();

    $stmt = $db->prepare("select * from events where id=:id");
    $stmt->bindValue(":id", $id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    $event = $result->fetchArray(SQLITE3_ASSOC);
    if ($event["type"] === "Private")
    {
        $_SESSION["errors"] = ["Cant remove Private events"];
        header("Location: ../pages/index.php");
        exit();
    }

    $stmt = $db->prepare("delete from events where id=:id");
    $stmt->bindValue(":id", $id, SQLITE3_TEXT);
    $result = $stmt->execute();
    if ($result)
    {
        header("Location: ../pages/index.php");
        exit();
    }
    else
    {
        $_SESSION["errors"] = [$db->lastErrorMsg()];
        header("Location: ../pages/index.php");
        exit();
    }


}
else
{
    $_SESSION["errors"] = ["Invalid method"];
    header("Location: ../pages/index.php");
    exit();
}
