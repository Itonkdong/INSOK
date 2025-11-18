<?php

include "../../utils/helper_functions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
    $title = trim($_POST["title"]);
    $genre = trim($_POST["genre"]);
    $year = trim($_POST["year"]);


    $db = connectDatabase();
    $stmt = $db->prepare("insert into movies (title, genre, year) values (:title, :genre, :year)");
    $stmt->bindValue(":title", $title, SQLITE3_TEXT);
    $stmt->bindValue(":genre", $genre, SQLITE3_TEXT);
    $stmt->bindValue(":year", $year, SQLITE3_INTEGER);

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
