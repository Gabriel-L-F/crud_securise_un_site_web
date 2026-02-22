<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$pdo = new PDO(
    "mysql:host=localhost;dbname=cinema;charset=utf8mb4",
    "root",
    "123",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
);

if (!empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['password'])) {

    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO user (`nom`, `email`, `password`)
            VALUES (:nom, :email, :password)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom' => $nom,
        'email' => $email,
        'password' => $password
    ]);

    header('Location: index.php'); 
}
