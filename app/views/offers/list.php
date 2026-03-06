<?php include '../../controllers/OfferController.php'; ?>

<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Offres de stage</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<link rel="stylesheet" href="../../../public/css/inscription.css">
<link rel="stylesheet" href="../../../public/css/offres.css">


</head>

<body>

<div class="phone-frame">

<header class="header">

<div class="logo">
<div class="logo-icon">Logo</div>
<span>StageHub</span>
</div>

</header>

<h1>Offres de stage</h1>


<main class="content">
    
<section id="search-bar" aria-label="Rechercher une offre">
    <form action="" method="get" class="search-form" autocomplete="off">
        <div class="field">
            <label for="company-search" class="field-label">
                <i class="fas fa-building" aria-hidden="true"></i>
            </label>
            <input id="company-search" class="field-input" type="search" name="query" placeholder="Entreprise, profil, compétence...">
        </div>

        <div class="field">
            <label for="city-search" class="field-label">
                <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
            </label>
            <input id="city-search" class="field-input" type="text" name="ville" placeholder="Ville">
        </div>

        <div class="field">
            <label for="domain-search" class="field-label">
                <i class="fas fa-briefcase" aria-hidden="true"></i>
            </label>
            <select id="domain-search" name="domaine" class="field-input">
                <option value="">Tous les domaines</option>
                <option value="informatique">Informatique</option>
                <option value="marketing">Marketing</option>
                <option value="industrie">Industrie</option>
                <option value="design">Design</option>
                <option value="finance">Finance</option>
            </select>
        </div>

        <button type="submit" class="search-button">Rechercher</button>
    </form>
</section>

<p class="info">
<?= $total ?> offres disponibles
</p>



<?php foreach ($offresPage as $offre): ?>

<div class="offer-card">

<h2><?= htmlspecialchars($offre['titre']) ?></h2>

<p class="company"><?= htmlspecialchars($offre['entreprise']) ?></p>

<p>Ville : <?= htmlspecialchars($offre['ville']) ?></p>

<p>Durée : <?= htmlspecialchars($offre['duree']) ?></p>

<p>Domaine : <?= htmlspecialchars($offre['domaine']) ?></p>

<button class="btn-submit">Détails</button>

</div>

<?php endforeach; ?>

</main>

</div>
<!-- Pagination -->
<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>">&#8249; Précédent</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $pages; $i++): ?>
        <?php if ($i === $page): ?>
            <strong><?= $i ?></strong>
        <?php else: ?>
            <a href="?page=<?= $i ?>"><?= $i ?></a>
        <?php endif; ?>
    <?php endfor; ?>

    <?php if ($page < $pages): ?>
        <a href="?page=<?= $page + 1 ?>">Suivant &#8250;</a>
    <?php endif; ?>
</div>

</body>

</html>