<?php

function validType(string $type):bool
{
    $valid_type = ["Public", "Private"];
    if (!in_array($type, $valid_type))
    {
        return false;
    }
    return true;
}

function loginRequired()
{
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

// Require JWT helper and validate session JWT before any output
    require __DIR__ . '/../auth/jwt_helper.php';
    if (!isset($_SESSION['jwt']) || !decodeJWT($_SESSION['jwt'])) {
        header("Location: ../auth/login.php");
        exit;
    }
}