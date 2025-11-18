<?php

include "constanst.php";

function connectDatabase()
{

    return new SQLite3(__DIR__."/database/".DB_NAME.".sqlite");
}


function validStatus($status)
{
    $valid_statuses = ["Pending", "Done"];

    return in_array($status, $valid_statuses);

}

function validPriority($priority)
{
    $valid_priorities = ["Low", "Medium", "High"];

    return in_array($priority, $valid_priorities);

}