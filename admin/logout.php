<?php
session_start();

require_once __DIR__ . '/../app/services/AuthService.php';

AuthService::logout();
header('Location: login.php');
exit();
?>

