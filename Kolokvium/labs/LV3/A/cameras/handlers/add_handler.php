<?php

include "../../utils/helper_functions.php";
session_start();
include_once '../../auth/jwt_helper.php';

requireLogin();



if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = $_POST["name"];
    $location = $_POST["location"];
    $date = (float) $_POST["date"];
    $price = $_POST["price"];
    $type = $_POST["type"];

    $db = connectDatabase();
    $stmt = $db->prepare("insert into cameras(name, location, date, price, type) values (:name, :location, :date, :price, :type)");
    $stmt->bindValue(":name", $name, SQLITE3_TEXT);
    $stmt->bindValue(":location", $location, SQLITE3_TEXT);
    $stmt->bindValue(":date", $date, SQLITE3_TEXT);
    $stmt->bindValue(":price", $price, SQLITE3_FLOAT);
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
        $_SESSION["data"] =$_POST;
        header("Location: ../pages/add_form.php");
        exit();
    }
}
else
{
    $_SESSION["errors"] = ["Invalid method"];
    header("Location: ../pages/index.php");
    exit();
}
