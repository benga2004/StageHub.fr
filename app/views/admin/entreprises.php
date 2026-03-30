<?php

$title = 'Administration des entreprises - StageHub';
$extra_css = '<link rel="stylesheet" href="' . BASE_URL . 'css/admin.css?v=3">';

require BASE_PATH . '/app/views/layout/header.php';
?>

<section class="admin-dashboard">
    <div class="admin-hero admin-hero-compact">
        <div>
            <span class="admin-kicker">Administration</span>
            <h1>Entreprises</h1>
            <p><?= count($entreprises) ?> entreprise(s) referencee(s) sur la plateforme.</p>
        </div>
        <div class="admin-hero-actions">
            <a href="<?= BASE_URL ?>admin" class="admin-btn admin-btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Retour au dashboard
            </a>
        </div>
    </div>

    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <span class="admin-panel-kicker">Annuaire</span>
                <h2>Liste des entreprises</h2>
            </div>
        </div>

        <?php if (empty($entreprises)): ?>
            <p class="admin-empty">Aucune entreprise n'est disponible pour le moment.</p>
        <?php else: ?>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Ville</th>
                            <th>Secteur</th>
                            <th>Email</th>
                            <th>Telephone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($entreprises as $entreprise): ?>
                            <tr>
                                <td><?= htmlspecialchars($entreprise['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($entreprise['ville'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($entreprise['secteur'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($entreprise['email'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($entreprise['telephone'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</section>

<?php require BASE_PATH . '/app/views/layout/footer.php'; ?>
