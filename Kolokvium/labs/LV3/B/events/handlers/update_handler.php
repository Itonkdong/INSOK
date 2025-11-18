<?php

include_once "../../utils/helper_functions.php";
include_once "../../auth/jwt_helper.php";
session_start();
requireLogin();

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $id = $_POST["id"];
    $name = $_POST["name"];
    $location = $_POST["location"];
    $type = $_POST["type"];
    $date = $_POST["date"];

    if (!in_array($type, ["Private", "Public"]))
    {
        $_SESSION["errors"] = ["Invalid type"];
        $_SESSION["data"] = $_POST;
        header("Location: ../pages/create_page.php");
        exit();
    }

    $db = connectDatabase();
    $stmt = $db->prepare("update events set name=:name, location=:location, date=:date, type=:type where id=:id");
    $stmt->bindValue(":name", $name, SQLITE3_TEXT);
    $stmt->bindValue(":location", $location, SQLITE3_TEXT);
    $stmt->bindValue(":date", $date, SQLITE3_TEXT);
    $stmt->bindValue(":type", $type, SQLITE3_TEXT);
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
        $_SESSION["data"] = $_POST;
        header("Location: ../pages/update_page.php");
        exit();
    }


}
else
{
    $_SESSION["errors"] = ["Invalid method"];
    header("Location: ../pages/index.php");
    exit();
}
