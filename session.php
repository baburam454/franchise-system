<?php
session_start(); // Start the session

if (!isset($_SESSION['user_status']) && !isset($_SESSION['view_only'])) {
    header('Location: login.php');
    exit();
}


?>
