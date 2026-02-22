<?php
require_once 'check_admin.php';
require_once 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* CSRF */
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/* Vérifier ID */
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    die('ID invalide');
}

$id = (int) $_GET['id'];

/* Charger le film */
$stmt = $pdo->prepare("SELECT * FROM film WHERE id = :id");
$stmt->execute(['id' => $id]);
$film = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$film) {
    die("Film introuvable");
}

/* Traitement POST */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF invalide");
    }

    $title = $_POST['title'] ?? '';
    $realisateur = $_POST['realisateur'] ?? '';
    $synopsis = $_POST['synopsis'] ?? '';
    $date_sortie = $_POST['date_sortie'] ?? '';

    if ($title && $realisateur && $synopsis && $date_sortie) {

        $stmt = $pdo->prepare("
            UPDATE film
            SET title = :title,
                realisateur = :realisateur,
                synopsis = :synopsis,
                date_sortie = :date_sortie
            WHERE id = :id
        ");

        $stmt->execute([
            'title' => $title,
            'realisateur' => $realisateur,
            'synopsis' => $synopsis,
            'date_sortie' => $date_sortie,
            'id' => $id
        ]);

        header('Location: admin.php?success=film_modifie');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le film</title>
</head>
<body>

<h1>Modifier le film</h1>

<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

    <label>Titre :</label>
    <input type="text" name="title"
           value="<?= htmlspecialchars($film['title']) ?>" required><br><br>

    <label>Réalisateur :</label>
    <input type="text" name="realisateur"
           value="<?= htmlspecialchars($film['realisateur']) ?>" required><br><br>

    <label>Synopsis :</label>
    <textarea name="synopsis" required><?= htmlspecialchars($film['synopsis']) ?></textarea><br><br>

    <label>Date de sortie :</label>
    <input type="date" name="date_sortie"
           value="<?= htmlspecialchars($film['date_sortie']) ?>" required><br><br>

    <button type="submit">Enregistrer</button>
</form>

</body>
</html>
