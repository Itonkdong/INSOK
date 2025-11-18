<?php

include_once "helper_functions.php";

$tables = [
    "movies" => <<<SQL
create table if not exists movies(
    id integer primary key autoincrement,
    title text not null,
    genre text not null,
    year integer not null 
)
SQL];

$db = connectDatabase();

foreach ($tables as $table_name => $stmt)
{
    if ($db->exec($stmt))
    {
        echo "Table {$table_name} successfully created \n";
    }
    else
    {
        echo "Error creating table {$table_name}\n";
    }
}


