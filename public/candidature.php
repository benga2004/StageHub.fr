<?php
session_start();

$succes  = '';
$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom    = isset($_POST['nom'])    ? trim($_POST['nom'])    : '';
    $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
    $email  = isset($_POST['email'])  ? trim($_POST['email'])  : '';
    $lettre = isset($_POST['lettre']) ? trim($_POST['lettre']) : '';

    if (empty($nom))    $erreurs[] = "Le nom est obligatoire.";
    if (empty($prenom)) $erreurs[] = "Le prénom est obligatoire.";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))
        $erreurs[] = "L'adresse email est invalide.";
    if (empty($lettre)) $erreurs[] = "La lettre de motivation est obligatoire.";

    if (!isset($_FILES['cv']) || $_FILES['cv']['error'] === UPLOAD_ERR_NO_FILE) {
        $erreurs[] = "Veuillez déposer votre CV en PDF.";
    } elseif ($_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
        $erreurs[] = "Erreur lors du téléversement du fichier.";
    } elseif ($_FILES['cv']['size'] > 2 * 1024 * 1024) {
        $erreurs[] = "Le CV ne doit pas dépasser 2 Mo.";
    } else {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $_FILES['cv']['tmp_name']);
        finfo_close($finfo);
        if ($mime !== 'application/pdf') {
            $erreurs[] = "Le fichier doit être un PDF.";
        }
    }

    if (empty($erreurs)) {
        if (!is_dir('uploads/cv')) mkdir('uploads/cv', 0755, true);
        $nomFich  = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '_', $nom));
        $prenFich = ucfirst(strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $prenom)));
        $dest     = 'uploads/cv/' . $nomFich . '_' . $prenFich . '_' . time() . '.pdf';
        if (move_uploaded_file($_FILES['cv']['tmp_name'], $dest)) {
            $succes = htmlspecialchars($prenom . ' ' . strtoupper($nom), ENT_QUOTES, 'UTF-8');
        } else {
            $erreurs[] = "Impossible de sauvegarder le fichier. Réessayez.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidature</title>
</head>
<body>

<h1>Ma candidature</h1>

<?php if (!empty($erreurs)): ?>
    <ul>
        <?php foreach ($erreurs as $e): ?>
            <li>⚠️ <?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (!empty($succes)): ?>

    <p>✅ Candidature envoyée avec succès !</p>
    <p>Merci <strong><?= $succes ?></strong>, votre dossier a bien été reçu.</p>
    <a href="offre_de_stage.php">← Retour aux offres</a>

<?php else: ?>

<form method="POST" action="" enctype="multipart/form-data">

    <label for="prenom">Prénom</label><br>
    <input type="text" id="prenom" name="prenom"
        placeholder="Votre prénom"
        value="<?= htmlspecialchars($_POST['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        required><br><br>

    <label for="nom">Nom</label><br>
    <input type="text" id="nom" name="nom"
        placeholder="Votre nom"
        value="<?= htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        required><br><br>

    <label for="email">Email</label><br>
    <input type="email" id="email" name="email"
        placeholder="votre@email.com"
        value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
        required><br><br>

    <label for="cv">CV (PDF — 2 Mo max)</label><br>
    <input type="file" id="cv" name="cv" accept=".pdf" required><br><br>

    <label for="lettre">Lettre de motivation</label><br>
    <textarea id="lettre" name="lettre"
        placeholder="Expliquez vos motivations..."
        required><?= htmlspecialchars($_POST['lettre'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea><br><br>

    <input type="submit" value="Envoyer ma candidature">

</form>

<a href="offre_de_stage.php">← Retour aux offres</a>

<?php endif; ?>

</body>
</html>