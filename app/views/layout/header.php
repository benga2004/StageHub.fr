
<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content= <?= $content ?? "StageHub - Trouvez votre stage idéal" ?> >
<title><?= $title ?? "StageHub" ?></title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../../../public/css/style.css">
<link rel="stylesheet" href="../../../public/css/inscription.css">
<link rel="stylesheet" href="../../../public/css/offres.css">
<link rel="stylesheet" href="../../../public/css/profil.css">
<?php if (!empty($extra_css)) echo $extra_css; ?>

</head>

<body>

<div class="phone-frame">

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
    <button class="menu-icon" aria-label="Menu">☰</button>
</header>
<?php endif; ?>

<main class="content">