<?php

include "../../utils/helper_functions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $id = $_POST["id"];


    $db = connectDatabase();
    $stmt = $db->prepare("delete from tasks  where id=:id");
    $stmt->bindValue(":id", $id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    if (!$result)
    {
        $_SESSION["errors"] = [$db->lastErrorMsg()];
        header("Location: ../pages/index.php");
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
