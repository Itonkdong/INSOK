<?php

include 'db_connection.php';

$db = connectDatabase();


$name = $_POST["name"];
$email = $_POST["email"];
$age = $_POST["age"];

$stmt = $db->prepare("INSERT INTO students (name, email, age)
                            VALUES (:name, :email, :age)");

$stmt->bindValue(':name', $name, SQLITE3_TEXT);
$stmt->bindValue(':email', $email, SQLITE3_TEXT);
$stmt->bindValue(':age', $age, SQLITE3_INTEGER);

if ($stmt->execute())
{
    header("Location: add_student_form.php");
}
else
{
    echo "Error Adding Student";
}

$db->close();


