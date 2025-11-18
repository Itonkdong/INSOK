<?php

include_once "constansts.php";

function connectDatabase()
{
    return new SQLite3(__DIR__."/database/".DB_NAME.".sqlite");
}
