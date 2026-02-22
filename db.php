<?php
$pdo = new PDO(
    "mysql:host=localhost;dbname=cinema;charset=utf8",
    "root",
    "123",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
