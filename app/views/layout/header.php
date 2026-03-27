
<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content= <?= $content ?? "StageHub - Trouvez votre stage idéal" ?> >
<title><?= $title ?? "StageHub" ?></title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
<link rel="stylesheet" href="<?= BASE_URL ?>css/inscription.css">
<link rel="stylesheet" href="<?= BASE_URL ?>css/offres.css">
<link rel="stylesheet" href="<?= BASE_URL ?>css/profil.css">
<link rel="stylesheet" href="<?= BASE_URL ?>css/ajout_offres.css">
<link rel="stylesheet" href="<?= BASE_URL ?>css/dashboard-pilote.css">
<link rel="stylesheet" href="<?= BASE_URL ?>css/admin.css">
<link rel="stylesheet" href="<?= BASE_URL ?>css/sidebar.css">
<link rel="stylesheet" href="<?= BASE_URL ?>css/bg-animations.css">

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
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
    <div class="particle"></div>
</div>

<?php if (!empty($custom_header)): ?>
<?= $custom_header ?>
<?php else: ?>
<header class="header">
    <div class="logo">
        <div class="logo-icon">Logo</div>
        <span>StageHub</span>
    </div>
    <nav class="nav-menu" id="nav-menu">
        <ul>
            <li><a href="<?= BASE_URL ?>">Accueil</a></li>
            <li><a href="<?= BASE_URL ?>offres">Offres</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="<?= BASE_URL ?>profil/etudiant">Mon Profil</a></li>
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
        <a href="<?= BASE_URL ?>">🏠 Accueil</a>
        <a href="<?= BASE_URL ?>offres">📋 Offres</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?= BASE_URL ?>profil/etudiant">👤 Mon Profil</a>
            <a href="<?= BASE_URL ?>deconnexion" class="logout-btn">🚪 Déconnexion</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>inscription">📝 Inscription</a>
            <a href="<?= BASE_URL ?>connexion">🔐 Connexion</a>
        <?php endif; ?>
    </div>
</nav>
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<?php endif; ?>

<main class="content">