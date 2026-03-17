<?php

$title      = "Dashboard Admin - StageHub";
$extra_css  = '<link rel="stylesheet" href="../css/admin.css">';

$custom_header = '
<header class="header">
    <div class="header-logo">
        <div class="header-logo-icon">S</div>
        StageHub
    </div>
    <div class="header-right">
        <div class="header-user">
            <div>Admin</div>
            <span class="role-badge">Administrateur</span>
        </div>
        <button class="btn-logout">&#x23FB;</button>
    </div>
</header>';

require_once '../../app/views/layout/header.php';

?>

            <!-- Bannière de bienvenue -->
            <div class="welcome-banner">
                <h2>Bonjour, Admin &#128075;</h2>
                <p>Voici un aperçu de votre plateforme</p>
            </div>

            <!-- Statistiques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">&#127970;</div>
                    <div class="stat-number">24</div>
                    <div class="stat-label">Entreprises</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">&#128203;</div>
                    <div class="stat-number">156</div>
                    <div class="stat-label">Offres</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">&#127891;</div>
                    <div class="stat-number">89</div>
                    <div class="stat-label">Étudiants</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">&#128232;</div>
                    <div class="stat-number">342</div>
                    <div class="stat-label">Candidatures</div>
                </div>
            </div>

            <!-- Modules -->
            <div class="section-title">Gestion des modules</div>
            <div class="modules-list">

                <a href="entreprises/" class="module-card">
                    <div class="module-icon-wrap">&#127970;</div>
                    <div class="module-info">
                        <div class="module-name">Entreprises</div>
                        <div class="module-desc">Ajouter, modifier, supprimer</div>
                    </div>
                    <span class="module-badge">24</span>
                    <span class="module-arrow">&#8250;</span>
                </a>

                <a href="offres/" class="module-card">
                    <div class="module-icon-wrap">&#128203;</div>
                    <div class="module-info">
                        <div class="module-name">Offres de Stage</div>
                        <div class="module-desc">Gérer les offres proposées</div>
                    </div>
                    <span class="module-badge">156</span>
                    <span class="module-arrow">&#8250;</span>
                </a>

                <a href="pilotes/" class="module-card">
                    <div class="module-icon-wrap">&#128084;</div>
                    <div class="module-info">
                        <div class="module-name">Pilotes</div>
                        <div class="module-desc">Gestion des managers</div>
                    </div>
                    <span class="module-badge">8</span>
                    <span class="module-arrow">&#8250;</span>
                </a>

                <a href="etudiants/" class="module-card">
                    <div class="module-icon-wrap">&#127891;</div>
                    <div class="module-info">
                        <div class="module-name">Étudiants</div>
                        <div class="module-desc">Gérer les profils</div>
                    </div>
                    <span class="module-badge">89</span>
                    <span class="module-arrow">&#8250;</span>
                </a>

                <a href="candidatures/" class="module-card">
                    <div class="module-icon-wrap">&#128232;</div>
                    <div class="module-info">
                        <div class="module-name">Candidatures</div>
                        <div class="module-desc">Suivi des candidatures</div>
                    </div>
                    <span class="module-badge">342</span>
                    <span class="module-arrow">&#8250;</span>
                </a>

                <a href="wishlist/" class="module-card">
                    <div class="module-icon-wrap">&#11088;</div>
                    <div class="module-info">
                        <div class="module-name">Wish-list</div>
                        <div class="module-desc">Favoris des étudiants</div>
                    </div>
                    <span class="module-badge">234</span>
                    <span class="module-arrow">&#8250;</span>
                </a>

            </div>

<?php require_once '../../app/views/layout/footer.php'; ?>
