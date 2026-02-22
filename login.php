<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'header.php';

require_once 'db.php'; 

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'email' => $user['email'],
            'admin' => $user['admin']
        ];

        if ($user['admin'] == 1) {
            header('Location: admin.php');
        } else {
            header('Location: index.php'); 
        }
        exit;
    } else {
        $message = "Email ou mot de passe incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="login.scss">
</head>
<body>
    <h1 class="title">Connexion</h1>

    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Email :</label>
        <input type="email" name="email" required><br><br>

        <label>Mot de passe :</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Se connecter</button>
    </form>
    <a href="inscription.php">S'inscrire</a>
</body>
</html>
