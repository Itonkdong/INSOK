<?php

include "../../utils/helper_functions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $title = $_POST["title"];
    $date = $_POST["date"];
    $priority = $_POST["priority"];
    $status = $_POST["status"];

    $errors = [];

    session_start();

    if (!validPriority($priority))
    {
        $errors[] = "Invalid priority";
    }

    if (!validStatus($status))
    {
        $errors[] = "Invalid status";
    }


    if (count($errors) !== 0)
    {
        $_SESSION["errors"] = $errors;
        $_SESSION["data"] = $_POST;
        header("Location: ../pages/add_task_form.php");
        exit();
    }

    $db = connectDatabase();
    $stmt = $db->prepare("insert into tasks(title, date, priority, status) values (:title, :date, :priority, :status) ");
    $stmt->bindValue(":title", $title, SQLITE3_TEXT);
    $stmt->bindValue(":date", $date, SQLITE3_TEXT);
    $stmt->bindValue(":priority", $priority, SQLITE3_TEXT);
    $stmt->bindValue(":status", $status, SQLITE3_TEXT);
    $result = $stmt->execute();

    if (!$result)
    {
        $_SESSION["errors"] = $errors;
        $_SESSION["data"] = $_POST;
        header("Location: ../pages/add_task_form.php");
        exit();
    }

    $db->close();
    header("Location: ../pages/index.php");
    exit();

}
else
{
    echo "Invalid Method";
}
