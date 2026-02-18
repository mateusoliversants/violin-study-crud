<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login-paginas/res-login.php");
    exit;
}
?>