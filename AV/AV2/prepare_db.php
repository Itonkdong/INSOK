<?php
include 'db_connection.php';

$db = connectDatabase();

$db->exec("create table if not exists `students` (
    `name` varchar(30) not null,
    `email` varchar(30) not null,
    `age` integer not null
)");


//phpinfo();
