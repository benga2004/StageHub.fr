<?php
$title = "Candidature - StageHub";
$content = "Postuler à une offre de stage";
$extra_css = '<link rel="stylesheet" href="' . BASE_URL . 'css/candidature.css">';

require __DIR__ . '/../layout/header.php';
?>

<h1>Ma candidature</h1>

<?php if (!empty($erreurs)): ?>
    <div class="error-messages">
        <ul>
            <?php foreach ($erreurs as $e): ?>
                <li>⚠️ <?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if (!empty($succes)): ?>
    <div class="success-message">
        <p>✅ Candidature envoyée avec succès !</p>
        <p>Merci <strong><?= $succes ?></strong>, votre dossier a bien été reçu.</p>
    </div>
    <a href="<?= BASE_URL ?>offres" class="btn btn-secondary">← Retour aux offres</a>
<?php else: ?>

<form method="POST" action="" enctype="multipart/form-data">
    <input type="hidden" name="offre_id" value="<?= htmlspecialchars($offre_id ?? '', ENT_QUOTES, 'UTF-8') ?>">

    <div class="form-group">
        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom"
            placeholder="Votre prénom"
            value="<?= htmlspecialchars($_POST['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            required>
    </div>

    <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom"
            placeholder="Votre nom"
            value="<?= htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            required>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"
            placeholder="votre@email.com"
            value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
            required>
    </div>

    <div class="form-group">
        <label for="cv">CV (PDF — 2 Mo max)</label>
        <input type="file" id="cv" name="cv" accept=".pdf" required>
    </div>

    <div class="form-group">
        <label for="lettre">Lettre de motivation</label>
        <textarea id="lettre" name="lettre" rows="8"
            placeholder="Expliquez vos motivations..."
            required><?= htmlspecialchars($_POST['lettre'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <button type="submit" class="btn-submit">Envoyer ma candidature</button>
</form>

<a href="<?= BASE_URL ?>offres" class="btn btn-secondary" style="margin-top: 20px; display: inline-block;">← Retour aux offres</a>

<?php endif; ?>

<?php require __DIR__ . '/../layout/footer.php'; ?>