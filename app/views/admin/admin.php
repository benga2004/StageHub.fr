<?php

$title = 'Dashboard Admin - StageHub';
$extra_css = '<link rel="stylesheet" href="' . BASE_URL . 'css/admin.css?v=3">';

require BASE_PATH . '/app/views/layout/header.php';
?>

<section class="admin-dashboard">
    <div class="admin-hero">
        <div>
            <span class="admin-kicker">Espace administration</span>
            <h1>Bonjour, <?= htmlspecialchars($adminName, ENT_QUOTES, 'UTF-8') ?></h1>
            <p>Voici l'etat actuel de la plateforme et les points d'entree utiles pour l'administration.</p>
        </div>
        <div class="admin-hero-actions">
            <a href="<?= BASE_URL ?>admin/entreprises" class="admin-btn admin-btn-primary">
                <i class="bi bi-buildings-fill"></i>
                Gerer les entreprises
            </a>
            <a href="<?= BASE_URL ?>offres" class="admin-btn admin-btn-secondary">
                <i class="bi bi-briefcase-fill"></i>
                Voir les offres
            </a>
        </div>
    </div>

    <div class="admin-stats-grid">
        <?php foreach ($stats as $stat): ?>
            <article class="admin-stat-card">
                <div class="admin-stat-icon">
                    <i class="<?= htmlspecialchars($stat['icon'], ENT_QUOTES, 'UTF-8') ?>"></i>
                </div>
                <div class="admin-stat-value"><?= (int) $stat['value'] ?></div>
                <div class="admin-stat-label"><?= htmlspecialchars($stat['label'], ENT_QUOTES, 'UTF-8') ?></div>
                <p class="admin-stat-detail"><?= htmlspecialchars($stat['detail'], ENT_QUOTES, 'UTF-8') ?></p>
            </article>
        <?php endforeach; ?>
    </div>

    <div class="admin-grid">
        <section class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <span class="admin-panel-kicker">Modules</span>
                    <h2>Acces rapides</h2>
                </div>
            </div>

            <div class="admin-modules-grid">
                <?php foreach ($modules as $module): ?>
                    <?php if ($module['is_active']): ?>
                        <a href="<?= htmlspecialchars($module['href'], ENT_QUOTES, 'UTF-8') ?>" class="admin-module-card">
                            <div class="admin-module-icon"><i class="<?= htmlspecialchars($module['icon'], ENT_QUOTES, 'UTF-8') ?>"></i></div>
                            <div class="admin-module-body">
                                <div class="admin-module-topline">
                                    <h3><?= htmlspecialchars($module['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <span class="admin-module-count"><?= (int) $module['count'] ?></span>
                                </div>
                                <p><?= htmlspecialchars($module['description'], ENT_QUOTES, 'UTF-8') ?></p>
                                <span class="admin-module-status"><?= htmlspecialchars($module['status'], ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                        </a>
                    <?php else: ?>
                        <div class="admin-module-card admin-module-card-disabled" aria-disabled="true">
                            <div class="admin-module-icon"><i class="<?= htmlspecialchars($module['icon'], ENT_QUOTES, 'UTF-8') ?>"></i></div>
                            <div class="admin-module-body">
                                <div class="admin-module-topline">
                                    <h3><?= htmlspecialchars($module['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                                    <span class="admin-module-count"><?= (int) $module['count'] ?></span>
                                </div>
                                <p><?= htmlspecialchars($module['description'], ENT_QUOTES, 'UTF-8') ?></p>
                                <span class="admin-module-status admin-module-status-muted"><?= htmlspecialchars($module['status'], ENT_QUOTES, 'UTF-8') ?></span>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </section>

        <section class="admin-panel">
            <div class="admin-panel-head">
                <div>
                    <span class="admin-panel-kicker">Comptes</span>
                    <h2>Repartition</h2>
                </div>
            </div>

            <div class="admin-summary-list">
                <?php foreach ($accountSummary as $item): ?>
                    <div class="admin-summary-row">
                        <div class="admin-summary-label-wrap">
                            <span class="admin-summary-dot <?= htmlspecialchars($item['tone'], ENT_QUOTES, 'UTF-8') ?>"></span>
                            <span class="admin-summary-label"><?= htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>
                        <strong><?= (int) $item['value'] ?></strong>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>

    <section class="admin-panel">
        <div class="admin-panel-head">
            <div>
                <span class="admin-panel-kicker">Utilisateurs</span>
                <h2>Derniers comptes</h2>
            </div>
        </div>

        <?php if (empty($recentUsers)): ?>
            <p class="admin-empty">Aucun compte trouve.</p>
        <?php else: ?>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentUsers as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars(trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')), ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td>
                                    <span class="admin-role-pill admin-role-<?= htmlspecialchars($user['role'] ?? 'etudiant', ENT_QUOTES, 'UTF-8') ?>">
                                        <?= htmlspecialchars(ucfirst($user['role'] ?? 'etudiant'), ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</section>

<?php require BASE_PATH . '/app/views/layout/footer.php'; ?>
