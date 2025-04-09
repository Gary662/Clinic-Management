<?php
session_start();
include '../config/db.php';
include 'config/auth.php';

if ($_GET['token']) {
    $token = $_GET['token'];
    if (verifyEmailToken($token)) {
        confirmEmail($token);
        echo "Email verified successfully!";
    } else {
        echo "Invalid token.";
    }
}
?>
