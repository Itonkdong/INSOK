<?php

include_once "../../utils/helper_functions.php";
include_once "../../auth/jwt_helper.php";
session_start();
requireLogin();
if ($_SERVER["REQUEST_METHOD"] === "POST")
{
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
    $stmt = $db->prepare("insert into events(name, location, date, type) values (:name, :location, :date, :type)");
    $stmt->bindValue(":name", $name, SQLITE3_TEXT);
    $stmt->bindValue(":location", $location, SQLITE3_TEXT);
    $stmt->bindValue(":date", $date, SQLITE3_TEXT);
    $stmt->bindValue(":type", $type, SQLITE3_TEXT);
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
        header("Location: ../pages/create_page.php");
        exit();
    }


}
else
{
    $_SESSION["errors"] = ["Invalid method"];
    header("Location: ../pages/index.php");
    exit();
}
