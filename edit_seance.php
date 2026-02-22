<?php
require_once 'check_admin.php';
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    die('ID invalide');
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("
    SELECT * FROM seance WHERE id = :id
");
$stmt->execute(['id' => $id]);
$seance = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$seance) {
    die("Séance introuvable");
}

$films = $pdo->query("SELECT id, title FROM film ORDER BY title")->fetchAll(PDO::FETCH_ASSOC);
$salles = $pdo->query("SELECT id, nom FROM salle ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF invalide");
    }

    $film_id = (int)($_POST['film_id'] ?? 0);
    $salle_id = (int)($_POST['salle_id'] ?? 0);
    $date_seance = $_POST['date_seance'] ?? '';
    $heure_seance = $_POST['heure_seance'] ?? '';
    $prix = (float)($_POST['prix'] ?? 0);

    if ($film_id && $salle_id && $date_seance && $heure_seance && $prix >= 0) {

        $date_heure = date(
            'Y-m-d H:i:s',
            strtotime("$date_seance $heure_seance")
        );

        $stmt = $pdo->prepare("
            UPDATE seance
            SET id_film = :film_id,
                id_salle = :salle_id,
                date_heure = :date_heure,
                prix = :prix
            WHERE id = :id
        ");

        $stmt->execute([
            'film_id' => $film_id,
            'salle_id' => $salle_id,
            'date_heure' => $date_heure,
            'prix' => $prix,
            'id' => $id
        ]);

        header('Location: admin.php?success=seance_modifiee');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la séance</title>
</head>
<body>

<h1>Modifier la séance</h1>

<form method="POST">
    <input type="hidden" name="csrf_token"
           value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

    <label>Film :</label>
    <select name="film_id" required>
        <?php foreach ($films as $film): ?>
            <option value="<?= $film['id'] ?>"
                <?= $film['id'] == $seance['id_film'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($film['title']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Salle :</label>
    <select name="salle_id" required>
        <?php foreach ($salles as $salle): ?>
            <option value="<?= $salle['id'] ?>"
                <?= $salle['id'] == $seance['id_salle'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($salle['nom']) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Date :</label>
    <input type="date" name="date_seance"
           value="<?= date('Y-m-d', strtotime($seance['date_heure'])) ?>"
           required><br><br>

    <label>Heure :</label>
    <input type="time" name="heure_seance"
           value="<?= date('H:i', strtotime($seance['date_heure'])) ?>"
           required><br><br>

    <label>Prix (€) :</label>
    <input type="number" step="0.01" min="0"
           name="prix" value="<?= $seance['prix'] ?>" required><br><br>

    <button type="submit">Enregistrer</button>
</form>

</body>
</html>
