<?php

function validPriority(string $priority):bool
{
    $valid_priority = ["Low", "Medium", "High"];
    if (!in_array($priority, $valid_priority))
    {
        return false;
    }
    return true;
}

function validStatus(string $status):bool
{
    $valid_status = ["Pending", "Done"];
    if (!in_array($status, $valid_status))
    {
        return false;
    }
    return true;
}
