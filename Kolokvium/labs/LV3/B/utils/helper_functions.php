<?php

include_once 'constants.php';


function connectDatabase()
{
    return new SQLite3(__DIR__."/database/".DB_NAME.".sqlite");
}
