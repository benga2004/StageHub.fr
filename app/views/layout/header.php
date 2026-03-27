
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
<?php if (!empty($extra_css)) echo $extra_css; ?>

</head>

<body>

<div>

<div class="status-bar">
    <div class="status-bar-left">3:03</div>
    <div class="status-bar-right">A B</div>
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
            <li><a href="<?= BASE_URL ?>inscription">Inscription</a></li>
            <li><a href="<?= BASE_URL ?>connexion">Connexion</a></li>
        </ul>
    </nav>
    <button class="menu-icon" id="menu-toggle" aria-label="Menu">☰</button>
</header>
<?php endif; ?>

<main class="content">