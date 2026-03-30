<?php
$title   = "Candidature - StageHub";
$content = "Postuler à une offre de stage sur StageHub";
$extra_css = '<link rel="stylesheet" href="' . BASE_URL . 'css/candidature.css?v=3">';

require __DIR__ . '/../layout/header.php';
?>

<div class="cand-page">

    <!-- ── Bannière hero ── -->
    <div class="cand-hero">
        <a href="<?= BASE_URL ?>offres" class="back-link">← Retour aux offres</a>
        <div class="cand-hero-icon">
            <i class="fas fa-paper-plane" aria-hidden="true"></i>
        </div>
        <h1>Postuler à cette offre</h1>
        <p class="cand-hero-sub">Remplissez ce formulaire pour envoyer votre candidature directement à l'entreprise.</p>
    </div>

    <!-- ── Corps (alertes + formulaire) ── -->
    <div class="cand-body">

        <?php if (!empty($erreurs)): ?>
        <div class="cand-alert cand-alert--error" role="alert">
            <i class="fas fa-exclamation-triangle cand-alert-icon" aria-hidden="true"></i>
            <ul class="cand-alert-list">
                <?php foreach ($erreurs as $e): ?>
                    <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <?php if (!empty($succes)): ?>

        <div class="cand-success" role="status">
            <div class="cand-success-icon"><i class="fas fa-check-circle" aria-hidden="true"></i></div>
            <h2>Candidature envoyée avec succès !</h2>
            <p>Merci <strong><?= $succes ?></strong>, votre dossier a bien été transmis.</p>
            <a href="<?= BASE_URL ?>offres" class="btn btn-primary">← Retour aux offres</a>
        </div>

        <?php else: ?>

        <form method="POST" action="" enctype="multipart/form-data" novalidate>
            <input type="hidden" name="offre_id" value="<?= htmlspecialchars($offre_id ?? '', ENT_QUOTES, 'UTF-8') ?>">

            <!-- Identité -->
            <p class="cand-section-title"><i class="fas fa-id-card" aria-hidden="true"></i> Vos informations</p>

            <div class="cand-row">
                <div class="form-group">
                    <label for="prenom">Prénom <span class="cand-required">*</span></label>
                    <input type="text" id="prenom" name="prenom"
                        placeholder="Votre prénom"
                        value="<?= htmlspecialchars($_POST['prenom'] ?? $user['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required>
                </div>
                <div class="form-group">
                    <label for="nom">Nom <span class="cand-required">*</span></label>
                    <input type="text" id="nom" name="nom"
                        placeholder="Votre nom"
                        value="<?= htmlspecialchars($_POST['nom'] ?? $user['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                        required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Adresse email <span class="cand-required">*</span></label>
                <input type="email" id="email" name="email"
                    placeholder="votre@email.com"
                    value="<?= htmlspecialchars($_POST['email'] ?? $user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    required>
            </div>

            <!-- Documents -->
            <p class="cand-section-title"><i class="fas fa-paperclip" aria-hidden="true"></i> Vos documents</p>

            <div class="form-group">
                <label for="cv">CV (PDF — 2 Mo max) <span class="cand-required">*</span></label>
                <div class="cand-file-drop">
                    <i class="fas fa-cloud-upload-alt cand-file-icon" aria-hidden="true"></i>
                    <span class="cand-file-label">Glissez votre PDF ici ou <span class="cand-file-browse">parcourez</span></span>
                    <input type="file" id="cv" name="cv" accept=".pdf" required aria-label="Déposer votre CV en PDF">
                </div>
            </div>

            <!-- Lettre -->
            <p class="cand-section-title"><i class="fas fa-pen-nib" aria-hidden="true"></i> Lettre de motivation</p>

            <div class="form-group">
                <label for="lettre">Votre message <span class="cand-required">*</span></label>
                <textarea id="lettre" name="lettre" rows="8"
                    placeholder="Expliquez vos motivations, vos compétences et ce qui vous attire dans ce poste..."
                    required><?= htmlspecialchars($_POST['lettre'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-paper-plane" aria-hidden="true"></i>&nbsp; Envoyer ma candidature
            </button>
        </form>

        <script>
        (function () {
            var input = document.getElementById('cv');
            if (!input) return;
            input.addEventListener('change', function () {
                var drop  = this.closest('.cand-file-drop');
                var icon  = drop.querySelector('.cand-file-icon');
                var label = drop.querySelector('.cand-file-label');
                if (this.files && this.files[0]) {
                    var name = this.files[0].name;
                    drop.classList.add('has-file');
                    icon.className = 'fas fa-check-circle cand-file-icon';
                    label.innerHTML = name;
                } else {
                    drop.classList.remove('has-file');
                    icon.className = 'fas fa-cloud-upload-alt cand-file-icon';
                    label.innerHTML = 'Glissez votre PDF ici ou <span class="cand-file-browse">parcourez</span>';
                }
            });
        })();
        </script>

        <?php endif; ?>

    </div><!-- /.cand-body -->
</div><!-- /.cand-page -->

<?php require __DIR__ . '/../layout/footer.php'; ?>
