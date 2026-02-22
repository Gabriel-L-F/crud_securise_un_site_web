<?php
require_once 'check_admin.php';
require_once 'db.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    die('ID invalide');
}

$id = (int) $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM salle WHERE id = :id");
$stmt->execute(['id' => $id]);
$salle = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$salle) {
    die("Salle introuvable");
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport">
    <title>Document</title>
</head>
<body>
    <h1>Modifier la salle</h1>

    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
        <input type="hidden" name="id" value="<?= $salle['id'] ?>">

        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($salle['nom']) ?>" required>

        <label>Nombre de places :</label>
        <input type="number" name="nb_place" value="<?= $salle['nb_place'] ?>" min="1" required>

        <label>3D :</label>
        <input type="checkbox" name="is_3d" <?= $salle['is_3d'] ? 'checked' : '' ?>>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF invalide");
    }

    $nom = $_POST['nom'] ?? '';
    $nb_place = (int) ($_POST['nb_place'] ?? 0);
    $is_3d = isset($_POST['is_3d']) ? 1 : 0;

    if ($nom && $nb_place > 0) {
        $stmt = $pdo->prepare("
            UPDATE salle
            SET nom = :nom, nb_place = :nb_place, is_3d = :is_3d
            WHERE id = :id
        ");
        $stmt->execute([
            'nom' => $nom,
            'nb_place' => $nb_place,
            'is_3d' => $is_3d,
            'id' => $id
        ]);

        header('Location: admin.php?success=1');
        exit;
    }
}
?>