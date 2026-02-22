<?php
require_once 'check_admin.php';
require_once 'db.php';
require_once 'header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// CSRF
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérifier CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token CSRF invalide !");
    }

    $form_type = $_POST['form_type'] ?? '';

    switch($form_type) {
        case 'add_seance':
            $film_id = (int)($_POST['film_id'] ?? 0);
            $salle_id = (int)($_POST['salle_id'] ?? 0);
            $date_seance = $_POST['date_seance'] ?? '';
            $heure_seance = $_POST['heure_seance'] ?? '';
            $prix = (float)($_POST['prix'] ?? 0);

            if ($film_id && $salle_id && $date_seance && $heure_seance && $prix >= 0) {
                $date_heure = $date_seance . ' ' . $heure_seance;

                $sql = "INSERT INTO seance (id_film, id_salle, date_heure, prix)
                        VALUES (:film_id, :salle_id, :date_heure, :prix)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':film_id' => $film_id,
                    ':salle_id' => $salle_id,
                    ':date_heure' => $date_heure,
                    ':prix' => $prix
                ]);
                $message = "Séance ajoutée avec succès !";
            } else {
                $message = "Veuillez remplir tous les champs correctement.";
            }
            break;

        case 'add_film':
            $title = $_POST['title'] ?? '';
            $realisateur = $_POST['realisateur'] ?? '';
            $synopsis = $_POST['synopsis'] ?? '';
            $date_sortie = $_POST['date_sortie'] ?? '';

            if ($title && $realisateur && $synopsis && $date_sortie) {
                $sql = "INSERT INTO film (title, realisateur, synopsis, date_sortie)
                        VALUES (:title, :realisateur, :synopsis, :date_sortie)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':title' => $title,
                    ':realisateur' => $realisateur,
                    ':synopsis' => $synopsis,
                    ':date_sortie' => $date_sortie
                ]);
                $message = "Film ajouté avec succès !";
            } else {
                $message = "Veuillez remplir tous les champs du film.";
            }
            break;

        case 'add_salle':
            $nom = $_POST['nom'] ?? '';
            $nb_place = (int)($_POST['nb_place'] ?? 0);
            $is_3d = isset($_POST['is_3d']) ? 1 : 0;

            if ($nom && $nb_place > 0) {
                $sql = "INSERT INTO salle (nom, nb_place, is_3d)
                        VALUES (:nom, :nb_place, :is_3d)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nom' => $nom,
                    ':nb_place' => $nb_place,
                    ':is_3d' => $is_3d
                ]);
                $message = "Salle ajoutée avec succès !";
            }
            break;

        
        case 'delete_salle':
            $salle_id = (int)($_POST['salle_id'] ?? 0);
            if ($salle_id > 0) {
                $stmt = $pdo->prepare("DELETE FROM salle WHERE id = :id");
                $stmt->execute([':id' => $salle_id]);
                $message = "Salle supprimée avec succès !";
            } else {
                $message = "ID de salle invalide.";
            }
            break;

        case 'delete_film':
                $film_id = (int)($_POST['film_id'] ?? 0);
                if ($film_id > 0) {
                    $stmt = $pdo->prepare("DELETE FROM film WHERE id = :id");
                    $stmt->execute([':id' => $film_id]);
                    $message = "film supprimée avec succès !";
                } else {
                    $message = "ID de salle invalide.";
                }
                break;

        case 'delete_seance':
                $seance_id = (int)($_POST['seance_id'] ?? 0);
                if ($seance_id > 0) {
                    $stmt = $pdo->prepare("DELETE FROM seance WHERE id = :id");
                    $stmt->execute([':id' => $seance_id]);
                    $message = "Seance supprimée avec succès !";
                } else {
                    $message = "ID de salle invalide.";
                }
                break;

        default:
            $message = "Formulaire inconnu.";
    }

    // Régénérer CSRF
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<?php if ($message): ?>
    <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<?php
$films = $pdo->query("SELECT id, title, realisateur, synopsis, date_sortie FROM film ORDER BY title ASC")->fetchAll(PDO::FETCH_ASSOC);

$salles = $pdo->query("SELECT id, nom, is_3d, nb_place FROM salle ORDER BY nom ASC")->fetchAll(PDO::FETCH_ASSOC);

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
    <link rel="stylesheet" href="admin.scss">
</head>

<body>
    <div class="container">

    <!-- Formulaire Seance -->
    <form class="form" method="POST" action="">
        <h1>Ajouter une séance</h1>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="form_type" value="add_seance">

        <label for="film_seance">Film :</label>
        <select name="film_id" id="film_seance" required>
            <option value="">-- Choisir un film --</option>
            <?php foreach ($films as $film): ?>
                <option value="<?= $film['id'] ?>"><?= htmlspecialchars($film['title'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="salle_seance">Salle :</label>
        <select name="salle_id" id="salle_seance" required>
            <option value="">-- Choisir une salle --</option>
            <?php foreach ($salles as $salle): ?>
                <option value="<?= $salle['id'] ?>"><?= htmlspecialchars($salle['nom'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="date_seance">Date :</label>
        <input type="date" name="date_seance" id="date_seance" required>
        <br><br>

        <label for="heure_seance">Heure :</label>
        <input type="time" name="heure_seance" id="heure_seance" required>
        <br><br>

        <label for="prix">Prix (€) :</label>
        <input type="number" name="prix" step="0.01" min="0" required>
        <br><br>

        <button type="submit">Ajouter la séance</button>
    </form>

    <!-- Formulaire Film -->
    <form class="form" method="POST" action="">
        <h1>Ajouter un film</h1>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="form_type" value="add_film">

        <label for="title">Titre :</label>
        <input type="text" name="title" id="title" required>
        <br><br>

        <label for="realisateur">Réalisateur :</label>
        <input type="text" name="realisateur" id="realisateur" required>
        <br><br>

        <label for="synopsis">Synopsis :</label>
        <textarea name="synopsis" id="synopsis" required></textarea>
        <br><br>

        <label for="date_sortie">Date de sortie :</label>
        <input type="date" name="date_sortie" id="date_sortie" required>
        <br><br>

        <button type="submit">Ajouter le film</button>
    </form>


    <!-- Formulaire Salle -->
    <form class="form" method="POST" action="">
        <h1>Ajouter une salle</h1>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
        <input type="hidden" name="form_type" value="add_salle">

        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required>
        <br><br>

        <label for="nb_place">Nombre de places :</label>
        <input type="number" name="nb_place" min="1" required>
        <br><br>

        <label for="3d">3D :</label>
        <input type="checkbox" name="3d" id="is_3d">
        <br><br>

        <button type="submit">Ajouter la salle</button>
    </form>

    </div>

<h2>Liste des salle</h2>

<?php foreach ($salles as $salle): ?>
    <ul value="<?= $salle['id'] ?>">
        <li>
            Nom : <?= htmlspecialchars($salle['nom'], ENT_QUOTES, 'UTF-8')?> 
            Nombre de place : <?= htmlspecialchars($salle['nb_place'],ENT_QUOTES, 'UTF-8') ?>
            <?= isset($salle['is_3d']) && $salle['is_3d'] ? ' - Salle 3D' : ' - Salle 2D' ?>

            <form method="POST" action="">
                <input type="hidden" name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="form_type" value="delete_salle">
                <input type="hidden" name="salle_id" value="<?= $salle['id'] ?>">
                <button type="submit">Supprimer</button>
            </form>
            <a href="edit_salle.php?id=<?= $salle['id'] ?>">Modifier</a>
        </li>
    </ul>
<?php endforeach; ?>

<h2>Liste des films</h2>

<?php foreach ($films as $film): ?>
    <ul value="<?= $film['id'] ?>">
        <li>
            Titre :<?= htmlspecialchars($film['title'], ENT_QUOTES, 'UTF-8') ?> 
            Realisateur : <?= htmlspecialchars($film['realisateur'], ENT_QUOTES, 'UTF-8') ?>
            Synospsis : <?= htmlspecialchars($film['synopsis'], ENT_QUOTES, 'UTF-8') ?>
            Date de sortie : <?= htmlspecialchars($film['date_sortie'], ENT_QUOTES, 'UTF-8') ?>

        <form method="POST" action="">
            <input type="hidden" name="csrf_token"
                value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
            <input type="hidden" name="form_type" value="delete_film">
            <input type="hidden" name="film_id" value="<?= $film['id'] ?>">
            <button type="submit">Supprimer</button>
            <a href="edit_film.php?id=<?= $film['id'] ?>">Modifier</a>
        </form>
    </ul>
<?php endforeach; ?>

<h2>Liste des séances</h2>

<?php foreach ($seances as $seance): ?>
    <ul>
        <li>
            Film : <?= htmlspecialchars($seance['film_titre'], ENT_QUOTES, 'UTF-8') ?> <br>
            Salle : <?= htmlspecialchars($seance['salle_nom'], ENT_QUOTES, 'UTF-8') ?> <br>
            Date et heure : <?= htmlspecialchars($seance['date_heure'], ENT_QUOTES, 'UTF-8') ?> <br>
            Prix : <?= number_format($seance['prix'], 2) ?> € <br>

            <form method="POST" action="">
                <input type="hidden" name="csrf_token"
                       value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="form_type" value="delete_seance">
                <input type="hidden" name="seance_id" value="<?= $seance['seance_id'] ?>">
                <button type="submit">Supprimer</button>
                <a href="edit_seance.php?id=<?= $seance['seance_id'] ?>">Modifier</a>
            </form>
        </li>
    </ul>
<?php endforeach; ?>

</body>
</html>
