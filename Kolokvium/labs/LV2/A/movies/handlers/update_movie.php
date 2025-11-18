<?php

include "../../utils/helper_functions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $title = trim($_POST["title"]);
    $genre = trim($_POST["genre"]);
    $year = trim($_POST["year"]);
    $id = trim($_POST["id"]);


    $db = connectDatabase();
    $stmt = $db->prepare("update movies set title = :title, year = :year, genre=:genre where id=:id");
    $stmt->bindValue(":title", $title, SQLITE3_TEXT);
    $stmt->bindValue(":genre", $genre, SQLITE3_TEXT);
    $stmt->bindValue(":year", $year, SQLITE3_INTEGER);
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
