<?php 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'header.php'; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="ajouter_utilisateur.php" method="POST">

    <label for="nom">Nom :</label><br>
    <input type="text" id="nom" name="nom" required maxlength="250"><br><br>

    <label for="email">Email :</label><br>
    <input type="email" id="email" name="email" required maxlength="250"><br><br>

    <label for="password">Mot de passe :</label><br>
    <input type="password" id="password" name="password" required maxlength="250"><br><br>

    <button type="submit">S'inscrire</button>

    <a href="login.php">Connexion</a>
</form>

</body>
</html>