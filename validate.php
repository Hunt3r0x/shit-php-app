<?php
session_start();
require_once('db_connection.php');

function validateUserSession() {
    if (isset($_SESSION['user_id'])) {
        return;
    }

    $currentPage = basename($_SERVER['PHP_SELF']);
    $allowedPages = ['login.php', 'dashboard.php'];

    if (!in_array($currentPage, $allowedPages)) {
        header("Location: login.php");
        exit();
    }
}

validateUserSession();
?>
