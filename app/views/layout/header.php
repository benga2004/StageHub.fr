
<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content= <?= $content ?? "StageHub - Trouvez votre stage idéal" ?> >
<title><?= $title ?? "StageHub" ?></title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>css/style.css?v=2">
<link rel="stylesheet" href="<?= BASE_URL ?>css/inscription.css?v=2">
<link rel="stylesheet" href="<?= BASE_URL ?>css/offres.css?v=2">
<link rel="stylesheet" href="<?= BASE_URL ?>css/profil.css?v=2">
<link rel="stylesheet" href="<?= BASE_URL ?>css/ajout_offres.css?v=2">
<link rel="stylesheet" href="<?= BASE_URL ?>css/sidebar.css?v=2">
<link rel="stylesheet" href="<?= BASE_URL ?>css/bg-animations.css?v=3">

<?php if (!empty($extra_css)) echo $extra_css; ?>

</head>

<body>

<!-- Arrière-plan animé -->
<div class="bg-grid"></div>

<div class="bg-rings">
    <div class="ring ring-1"></div>
    <div class="ring ring-2"></div>
    <div class="ring ring-3"></div>
    <div class="ring ring-4"></div>
</div>

<div class="bg-orbs">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="orb orb-4"></div>
    <div class="orb orb-5"></div>
</div>

<div class="bg-particles">
    <?php for ($i = 1; $i <= 360; $i++): ?>
        <div class="particle" style="--i: <?= $i ?>;"></div>
    <?php endfor; ?>
</div>

<?php if (!empty($custom_header)): ?>
<?= $custom_header ?>
<?php else: ?>
<header class="header">
    <div class="logo">
        <img src="<?= BASE_URL ?>images/Logo.png?v=2" alt="StageHub logo" class="logo-icon">
        <span>StageHub</span>
    </div>
    <nav class="nav-menu" id="nav-menu">
        <ul>
            <li><a href="<?= BASE_URL ?>">Accueil</a></li>
            <li><a href="<?= BASE_URL ?>offres">Offres</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php $role = $_SESSION['user_role'] ?? 'etudiant'; ?>
                <?php if ($role === 'admin'): ?>
                    <li><a href="<?= BASE_URL ?>admin"><i class="fas fa-shield-alt"></i> Administration</a></li>
                <?php elseif ($role === 'pilote'): ?>
                    <li><a href="<?= BASE_URL ?>dashboard"><i class="fas fa-chart-line"></i> Dashboard</a></li>
                    <li><a href="<?= BASE_URL ?>offres/ajouter"><i class="fas fa-plus-circle"></i> Ajouter une offre</a></li>
                <?php else: ?>
                    <li><a href="<?= BASE_URL ?>profil/etudiant"><i class="fas fa-user"></i> Mon Profil</a></li>
                    <li><a href="<?= BASE_URL ?>wishlist"><i class="fas fa-bookmark"></i> Wishlist</a></li>
                <?php endif; ?>
                <li>
                    <span class="nav-username">👋 <?= $_SESSION['user_prenom'] ?? '' ?></span>
                </li>
                <li><a href="<?= BASE_URL ?>deconnexion" class="btn-nav-logout">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="<?= BASE_URL ?>inscription">Inscription</a></li>
                <li><a href="<?= BASE_URL ?>connexion" class="btn-nav-login">Connexion</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <button class="menu-icon" id="menu-toggle" aria-label="Menu">☰</button>
</header>

<!-- Sidebar mobile -->
<nav class="sidebar" id="mobile-menu">
    <button class="sidebar-close" id="sidebar-close">✕</button>
    <div class="sidebar-items">
        <a href="<?= BASE_URL ?>"><i class="bi bi-house-door-fill"></i> Accueil</a>
        <a href="<?= BASE_URL ?>offres"><i class="bi bi-briefcase-fill"></i> Offres</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php $role = $_SESSION['user_role'] ?? 'etudiant'; ?>
            <?php if ($role === 'admin'): ?>
                <a href="<?= BASE_URL ?>admin"><i class="bi bi-shield-fill"></i> Administration</a>
            <?php elseif ($role === 'pilote'): ?>
                <a href="<?= BASE_URL ?>dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="<?= BASE_URL ?>offres/ajouter"><i class="bi bi-plus-circle-fill"></i> Ajouter une offre</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>profil/etudiant"><i class="bi bi-person-fill"></i> Mon Profil</a>
                <a href="<?= BASE_URL ?>wishlist"><i class="bi bi-bookmark-fill"></i> Wishlist</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>deconnexion" class="logout-btn"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>inscription"><i class="bi bi-person-plus-fill"></i> Inscription</a>
            <a href="<?= BASE_URL ?>connexion"><i class="bi bi-lock-fill"></i> Connexion</a>
        <?php endif; ?>
    </div>
</nav>
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<?php endif; ?>

<main class="content">