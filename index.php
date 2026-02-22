<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';
require_once 'header.php';

$seances = $pdo->query("
    SELECT 
        s.id AS seance_id,
        f.title AS film_titre,
        sa.nom AS salle_nom,
        s.date_heure,
        s.prix
    FROM seance s
    JOIN film f ON s.id_film = f.id
    JOIN salle sa ON s.id_salle = sa.id
    ORDER BY s.date_heure ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="index.scss">
</head>
<body>
    <main class="main">
        <div class="welcome-container">
            <h1 class="welcome">Bienvenue sur WatchThis</h1>
            <p class="subtitle">Découvrez les séances à l'affiche</p>
        </div>

        <?php if (empty($seances)): ?>
            <p class="no-seances">Aucune séance disponible.</p>
        <?php else: ?>
            <ul class="seance-list">
                <?php foreach ($seances as $seance): ?>
                    <li class="seance-item">
                        <h1 class="seance-name"><?= htmlspecialchars($seance['film_titre']) ?></h1>
                        <div class="seance-date"><?= date('d/m/Y à H:i', strtotime($seance['date_heure'])) ?></div>
                        <div class="seance-salle"><?= htmlspecialchars($seance['salle_nom']) ?></div>
                        <div class="seance-price"><?= number_format($seance['prix'], 2) ?> €</div>
                        <img src="" alt="">
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </main>
</body>
</html>
