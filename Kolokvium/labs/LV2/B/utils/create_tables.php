<?php

include "helper_functions.php";


$tables = [
    "tasks" => <<<SQL
create table if not exists tasks(
    id integer primary key autoincrement,
    title text not null,
    date text not null,
    priority text not null,
    status text not null
)
SQL
];

foreach ($tables as $table_name => $stmt)
{
    $db = connectDatabase();
    if ($db->exec($stmt))
    {
        echo "Successfuly created table: $table_name";
    }
    else
    {
        echo "Error: {$db->lastErrorMsg()}";
    }
}

