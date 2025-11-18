<?php

include_once 'helper_functions.php';

$tables = [
    "events" => <<<SQL
create table if not exists events(
    id integer primary key autoincrement,
    name text not null,
    location text not null,
    date text not null,
    type text not null
)
SQL,
    "users" => <<<SQL
create table if not exists users(
    id integer primary key autoincrement,
    username text not null ,
    password text not null,
    role text not null
)


SQL
];

$db = connectDatabase();

foreach ($tables as $tableName => $query)
{
    if ($db->exec($query))
    {
        echo "Table $tableName successfully created\n";
    }
    else
    {
        echo "Error: {$db->lastErrorMsg()}\n";
    }
}
