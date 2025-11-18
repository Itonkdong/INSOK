<?php

include "../../utils/helper_functions.php";

session_start();
include_once '../../auth/jwt_helper.php';

requireLogin();


if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    $id = $_POST["id"];

    $db = connectDatabase();
    $stmt = $db->prepare("delete from cameras where id =:id");
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
