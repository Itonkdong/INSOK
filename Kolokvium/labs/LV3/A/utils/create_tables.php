<?php

include "helper_functions.php";

$tables = [
    "cameras" => <<<SQL
create table if not exists cameras (
    id integer primary key  autoincrement,
    name text not null,
    location text not null,
    date text not null,
    price real not null,
    type text not null
)
SQL,
    "user" => <<<SQL

create table if not exists users(
    id integer primary key autoincrement,
    username text not null,
    password text not null,
    role text not null 
)
SQL
];

$db = connectDatabase();

foreach ($tables as $table_name => $stmt)
{
    if ($db->exec($stmt))
    {
        echo "Successfully created table $table_name\n";
    }
    else
    {
        echo "Error: {$db->lastErrorMsg()}\n";
    }
}

