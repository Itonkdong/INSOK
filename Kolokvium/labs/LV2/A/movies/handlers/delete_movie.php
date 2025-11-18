<?php

include "../../utils/helper_functions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $id = trim($_POST["id"]);

    $db = connectDatabase();

    $stmt = $db->prepare("delete from movies where id=:id");
    $stmt->bindValue(":id", $id, SQLITE3_INTEGER);

    if ($stmt->execute())
    {
        header("Location: ../pages/index.php");
        exit();
    }
    else
    {
        echo "Error: {$db->lastErrorMsg()}";
    }

}
else
{
    echo "Invalid Method";
}
