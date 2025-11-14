<?php
function connectDatabase(): SQLite3 {
    return new SQLite3(__DIR__ . '/database/tasks_db.sqlite');
}
?>