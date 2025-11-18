<?php

include "../../utils/helper_functions.php";

include_once '../../auth/jwt_helper.php';

requireLogin();

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = $_POST["name"];
    $id = $_POST["id"];
    $location = $_POST["location"];
    $date = (float) $_POST["date"];
    $price = $_POST["price"];
    $type = $_POST["type"];

    $db = connectDatabase();
    $stmt = $db->prepare("update cameras set name=:name, location=:location, date=:date, price=:price, type=:type where id =:id");
    $stmt->bindValue(":name", $name, SQLITE3_TEXT);
    $stmt->bindValue(":location", $location, SQLITE3_TEXT);
    $stmt->bindValue(":date", $date, SQLITE3_TEXT);
    $stmt->bindValue(":price", $price, SQLITE3_FLOAT);
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
        $_SESSION["data"] =$_POST;
        header("Location: ../pages/update_form.php");
        exit();
    }
}
else
{
    $_SESSION["errors"] = ["Invalid method"];
    header("Location: ../pages/index.php");
    exit();
}
