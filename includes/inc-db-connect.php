<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$dsn = 'mysql:dbname=taskwave;host=127.0.0.1;charset=utf8mb4';
$user = 'root';
$password = '';

$dbh = new PDO($dsn, $user, $password);