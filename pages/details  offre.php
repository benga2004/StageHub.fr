<?php

/*$offres = [
    0 => [
        'titre'       => 'Développeur Web',
        'entreprise'  => 'Cesi École d\'Ingénieurs',
        'ville'       => 'Saint-Nazaire',
        'duree'       => '3 mois',
        'domaine'     => 'Développement Web',
        'description' => 'Le CESI École d\'Ingénieurs recherche un stagiaire en développement web motivé pour rejoindre son équipe digitale. Vous participerez à la conception et au développement de nouvelles fonctionnalités sur les plateformes internes.',
        'missions'    => [
            'Développement de pages web en HTML, CSS, JavaScript',
            'Intégration de maquettes Figma',
            'Développement back-end en PHP avec base de données MySQL',
            'Participation aux réunions d\'équipe et aux revues de code',
        ],
        'profil'      => [
            'Étudiant en informatique ou développement web',
            'Connaissances en HTML, CSS, PHP',
            'Autonomie et rigueur',
            'Bon relationnel et esprit d\'équipe',
        ],
        'remuneration'=> '600 €/mois',
        'date'        => 'Publié il y a 2 jours',
        'contact'     => 'recrutement@cesi.fr',
    ],
    1 => [
        'titre'       => 'Stage Marketing Digital',
        'entreprise'  => 'BrandWave Agency',
        'ville'       => 'Lyon',
        'duree'       => '4 mois',
        'domaine'     => 'Marketing',
        'description' => 'BrandWave Agency recherche un stagiaire en marketing digital pour renforcer son équipe. Vous travaillerez sur la gestion des réseaux sociaux, les campagnes SEA et la création de contenus.',
        'missions'    => [
            'Gestion et animation des réseaux sociaux (Instagram, LinkedIn, TikTok)',
            'Rédaction de contenus et newsletters',
            'Suivi et analyse des campagnes Google Ads',
            'Veille concurrentielle et reporting hebdomadaire',
        ],
        'profil'      => [
            'Étudiant en marketing, communication ou digital',
            'Maîtrise des outils Google (Ads, Analytics)',
            'Sens créatif et plume rédactionnelle',
            'Curiosité et réactivité',
        ],
        'remuneration'=> '550 €/mois',
        'date'        => 'Publié il y a 3 jours',
        'contact'     => 'rh@brandwave.fr',
    ],
    2 => [
        'titre'       => 'Ingénieur Automatisme',
        'entreprise'  => 'IndusTrial Group',
        'ville'       => 'Nantes',
        'duree'       => '6 mois',
        'domaine'     => 'Industrie',
        'description' => 'IndusTrial Group recherche un stagiaire pour participer au développement de programmes PLC et à la mise en service de lignes de production automatisées dans un environnement industriel stimulant.',
        'missions'    => [
            'Développement et tests de programmes automates (Siemens, Schneider)',
            'Mise en service de lignes de production',
            'Rédaction de documentation technique',
            'Support aux équipes de maintenance',
        ],
        'profil'      => [
            'Étudiant en génie électrique, automatisme ou robotique',
            'Connaissances en programmation PLC',
            'Rigueur et sens de l\'analyse',
            'Capacité à travailler en environnement industriel',
        ],
        'remuneration'=> '700 €/mois',
        'date'        => 'Publié il y a 4 jours',
        'contact'     => 'stage@industrial-group.fr',
    ],
];

// ── Récupération de l'id ──────────────────────────────────
$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;

// ── Vérification que l'offre existe ──────────────────────
if (!isset($offres[$id])) {
    $offre = null;
} else {
    $offre = $offres[$id];
}*/

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'offre</title>
</head>
<body>

<a href="offre_de_stage.php">← Retour aux offres</a>

<?php if ($offre === null): ?>

    <h1>Offre introuvable</h1>
    <p>Cette offre n'existe pas ou a été supprimée.</p>

<?php else: ?>

    <h1><?= htmlspecialchars($offre['titre'],      ENT_QUOTES, 'UTF-8') ?></h1>
    <h2><?= htmlspecialchars($offre['entreprise'], ENT_QUOTES, 'UTF-8') ?></h2>

    <hr>

    <p><strong>Ville :</strong>        <?= htmlspecialchars($offre['ville'],        ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Durée :</strong>        <?= htmlspecialchars($offre['duree'],        ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Domaine :</strong>      <?= htmlspecialchars($offre['domaine'],      ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Rémunération :</strong> <?= htmlspecialchars($offre['remuneration'], ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Contact :</strong>      <?= htmlspecialchars($offre['contact'],      ENT_QUOTES, 'UTF-8') ?></p>
    <p class="date"><?= htmlspecialchars($offre['date'], ENT_QUOTES, 'UTF-8') ?></p>

    <hr>

    <h3>Description</h3>
    <p><?= htmlspecialchars($offre['description'], ENT_QUOTES, 'UTF-8') ?></p>

    <h3>Missions</h3>
    <ul>
        <?php foreach ($offre['missions'] as $mission): ?>
            <li><?= htmlspecialchars($mission, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>

    <h3>Profil recherché</h3>
    <ul>
        <?php foreach ($offre['profil'] as $p): ?>
            <li><?= htmlspecialchars($p, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>

    <hr>

    <a href="candidature.php?id=<?= $id ?>">Postuler à cette offre</a>

<?php endif; ?>

</body>
</html>