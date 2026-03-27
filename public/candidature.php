<?php
session_start();

$base_url = '/StageHub/public/';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=stagehub;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$message = '';
$success = false;

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . $base_url . 'connexion');
    exit;
}

$offre_id = isset($_GET['offre_id']) ? (int)$_GET['offre_id'] : 0;

if ($offre_id <= 0) {
    $message = "Offre invalide.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $offre_id > 0) {
    $lettre = trim($_POST['lettre'] ?? '');
    $cv_path = '';

    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/uploads/cv/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $filename = uniqid() . '_' . basename($_FILES['cv']['name']);
        $cv_path = $upload_dir . $filename;
        move_uploaded_file($_FILES['cv']['tmp_name'], $cv_path);
        $cv_path = 'uploads/cv/' . $filename;
    }

    if (empty($lettre)) {
        $message = "Veuillez remplir la lettre de motivation.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO candidatures (user_id, offre_id, lettre_motivation, cv_path, date_candidature) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$_SESSION['user_id'], $offre_id, $lettre, $cv_path]);
        $success = true;
        $message = "Votre candidature a été envoyée avec succès !";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidature - StageHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $base_url ?>css/style.css">
    <link rel="stylesheet" href="<?= $base_url ?>css/animations.css">
    <link rel="stylesheet" href="<?= $base_url ?>css/candidature.css">
</head>
<body>

<!-- Space animation background -->
<div class="stars"></div>
<div class="shooting-star"></div>
<div class="shooting-star"></div>
<div class="shooting-star"></div>
<div class="shooting-star"></div>
<div class="shooting-star"></div>

<div class="site-container">

<header class="header">
    <div class="logo">
        <div class="logo-icon">Logo</div>
        <span>StageHub</span>
    </div>
    <nav class="nav-menu">
        <ul>
            <li><a href="<?= $base_url ?>">Accueil</a></li>
            <li><a href="<?= $base_url ?>offres">Offres</a></li>
            <li><a href="<?= $base_url ?>profil">Profil</a></li>
        </ul>
    </nav>
</header>

<main class="content">
    <div class="candidature-form">
        <h2><i class="fas fa-paper-plane"></i> Postuler à cette offre</h2>

        <?php if ($message): ?>
            <div class="message <?= $success ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <?php if (!$success && $offre_id > 0): ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="cv">Votre CV (PDF) :</label>
            <input type="file" name="cv" id="cv" accept=".pdf,.doc,.docx">

            <label for="lettre">Lettre de motivation :</label>
            <textarea name="lettre" id="lettre" 
                placeholder="Expliquez vos motivations..."
                required><?= htmlspecialchars($_POST['lettre'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

            <input type="submit" value="Envoyer ma candidature">
        </form>
        <?php endif; ?>

        <a href="<?= $base_url ?>offres" class="back-link">← Retour aux offres</a>
    </div>
</main>

<footer class="footer">
    <p>&copy; <?= date('Y') ?> StageHub - Tous droits réservés</p>
</footer>

</div>

<script src="<?= $base_url ?>js/main.js"></script>
</body>
</html>