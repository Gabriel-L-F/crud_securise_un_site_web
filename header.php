<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="header.scss">
</head>
<body>
    <header class="header" stylesheet>
        <div class="container">
            <nav class="navigation">
                <ul class="liste">
                    <li><a href="index.php">A l'affiche</a></li>
                    <?php if (isset($_SESSION['user']) && (int)$_SESSION['user']['admin'] === 1): ?>
                        <li><a href="admin.php">Admin</a></li>
                        <?php endif; ?>
                    <?php if (isset($_SESSION['user'])): ?>
                            <li><a href="logout.php">Se déconnecter</a></li>
                    <?php else: ?>
                    <li><a href="login.php">Connexion</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            
            <div class="connexion">
                <?php if (isset($_SESSION['user'])): ?>
                    Connecté en tant que :
                    <strong><?= htmlspecialchars($_SESSION['user']['email']) ?></strong>
                    <?php if ((int)$_SESSION['user']['admin'] === 1): ?>
                        <?php endif; ?>
                        <?php else: ?>
                            Non connecté
                        <?php endif; ?>
            </div>
        </div>
    </header>          
</body>
</html>