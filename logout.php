<?php
session_start();
session_destroy();

header("Location: login-paginas/res-login.php");
exit;
?>