<?php

$offres = [
    ['titre' => 'Développeur Web Front-end',    'entreprise' => 'NovaTech Solutions',       'ville' => 'Paris',        'duree' => '3 mois', 'domaine' => 'Informatique'],
    ['titre' => 'Stage Marketing Digital',       'entreprise' => 'BrandWave Agency',         'ville' => 'Lyon',         'duree' => '4 mois', 'domaine' => 'Marketing'],
    ['titre' => 'Ingénieur Automatisme',         'entreprise' => 'IndusTrial Group',         'ville' => 'Nantes',       'duree' => '6 mois', 'domaine' => 'Industrie'],
    ['titre' => 'UI/UX Designer',                'entreprise' => 'Pixel Studio',             'ville' => 'Bordeaux',     'duree' => '3 mois', 'domaine' => 'Design'],
    ['titre' => 'Analyste Financier Junior',     'entreprise' => 'CapitalEdge Finance',      'ville' => 'Paris',        'duree' => '5 mois', 'domaine' => 'Finance'],
    ['titre' => 'Développeur Back-end PHP',      'entreprise' => 'Cesi École d\'Ingénieurs', 'ville' => 'Saint-Nazaire','duree' => '3 mois', 'domaine' => 'Informatique'],
    ['titre' => 'Chargé de Communication',       'entreprise' => 'ComMedia Group',           'ville' => 'Lille',        'duree' => '4 mois', 'domaine' => 'Marketing'],
    ['titre' => 'Technicien Maintenance',        'entreprise' => 'SteelPro Industries',      'ville' => 'Dunkerque',    'duree' => '6 mois', 'domaine' => 'Industrie'],
    ['titre' => 'Développeur Mobile iOS',        'entreprise' => 'AppForge Labs',            'ville' => 'Toulouse',     'duree' => '4 mois', 'domaine' => 'Informatique'],
    ['titre' => 'Graphiste Motion Design',       'entreprise' => 'Lumi Creative',            'ville' => 'Montpellier',  'duree' => '3 mois', 'domaine' => 'Design'],
    ['titre' => 'Contrôleur de Gestion',         'entreprise' => 'Nexio Holding',            'ville' => 'Strasbourg',   'duree' => '5 mois', 'domaine' => 'Finance'],
    ['titre' => 'Data Analyst',                  'entreprise' => 'DataSphere',               'ville' => 'Paris',        'duree' => '4 mois', 'domaine' => 'Informatique'],
    ['titre' => 'Chef de Projet Digital',        'entreprise' => 'AgileWorks',               'ville' => 'Rennes',       'duree' => '5 mois', 'domaine' => 'Marketing'],
    ['titre' => 'Ingénieur Qualité',             'entreprise' => 'AeroLink Systèmes',        'ville' => 'Toulouse',     'duree' => '6 mois', 'domaine' => 'Industrie'],
    ['titre' => 'Designer Produit',              'entreprise' => 'Forma Design Studio',      'ville' => 'Paris',        'duree' => '4 mois', 'domaine' => 'Design'],
    ['titre' => 'Développeur Full Stack',        'entreprise' => 'SoftLink Technologies',    'ville' => 'Grenoble',     'duree' => '3 mois', 'domaine' => 'Informatique'],
    ['titre' => 'Auditeur Junior',               'entreprise' => 'Veritas Audit & Conseil',  'ville' => 'Lyon',         'duree' => '5 mois', 'domaine' => 'Finance'],
    ['titre' => 'Responsable Réseaux Sociaux',   'entreprise' => 'ViralBoost Studio',        'ville' => 'Nice',         'duree' => '3 mois', 'domaine' => 'Marketing'],
    ['titre' => 'Ingénieur Logistique',          'entreprise' => 'SupplyChain Pro',          'ville' => 'Le Havre',     'duree' => '6 mois', 'domaine' => 'Industrie'],
    ['titre' => 'Développeur Cybersécurité',     'entreprise' => 'CyberShield France',       'ville' => 'Lille',        'duree' => '4 mois', 'domaine' => 'Informatique'],
];

$parPage = 5;
$total   = count($offres);
$pages   = ceil($total / $parPage);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($page, $pages));

$debut      = ($page - 1) * $parPage;
$offresPage = array_slice($offres, $debut, $parPage);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Offres de stage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Reset léger */
        * { box-sizing: border-box; }

        :root {
            --bg: #ffffff;
            --border: #dbe6f1;
            --input: #f6f9fe;
            --text: #2b3a4a;
            --muted: #6b7a8c;
            --primary: #6d8bd5;
        }

        body {
            background-color: #eef2f6;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            margin: 0;
            color: #334155;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            background: white;
            border-bottom: 1px solid #e2e8f0;
        }

        h1 {
            font-size: 28px;
            padding: 20px;
            margin: 0;
            color: #2d3748;
        }

        #search-bar {
            background: white;
            margin: 15px;
            padding: 25px 20px;
            border: 1px solid #d1d9e0;
            border-radius: 4px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        #search {
            width: 90%;
            padding: 12px 12px 12px 40px;
            border: 1px solid #cbd5e0;
            border-radius: 5px;
            font-size: 14px;
        }

        #filter {
            appearance: none;
            -webkit-appearance: none;
            background-color: #e2e8f0;
            border: 1px solid #cbd5e0;
            border-radius: 30px;
            padding: 10px 60px;
            font-size: 18px;
            color: #475569;
            cursor: pointer;
            text-align: center;
        }

        .offer {
            background: white;
            margin: 15px;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            position: relative;
        }

        .offer h2 { margin: 0 0 10px 0; color: #1a202c; font-size: 20px; }
        .offer h3 { margin: 0 0 15px 0; color: #3182ce; font-size: 16px; font-weight: 500; }
        .offer p  { margin: 5px 0; font-size: 15px; color: #4a5568; }

        .offer p strong {
            color: #718096;
            display: inline-block;
            width: 80px;
        }

        .date {
            margin-top: 20px !important;
            color: #a0aec0 !important;
            font-size: 14px !important;
        }

        #details {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background-color: #ebf8ff;
            color: #2b6cb0;
            border: 1px solid #3182ce;
            padding: 8px 18px;
            border-radius: 20px;
            font-weight: bold;
            cursor: pointer;
        }

        label[for="details"] { display: none; }

        i {
            display: inline-block;
            line-height: 1;
            vertical-align: middle;
            color: #2b6cb0;
            font-size: 1.1em;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Form layout */
        .search-form {
            display: grid;
            gap: 14px;
            width: 90%;
        }

        .field {
            display: grid;
            grid-template-columns: 1.5em 1fr;
            align-items: center;
            gap: 8px;
            padding: 6px 8px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--input);
        }

        .field-label {
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted);
        }

        .field-input {
            width: 100%;
            border: none;
            background: transparent;
            outline: none;
            font: inherit;
            padding: 8px 6px;
            border-radius: 6px;
            color: var(--text);
        }

        .field-input::placeholder { color: #9aa4b2; }

        .search-button {
            grid-column: 1 / -1;
            width: 100%;
            padding: 12px 16px;
            border: none;
            border-radius: 26px;
            background: #eaf1fb;
            color: #2a4a8a;
            font-weight: 600;
            cursor: pointer;
            transition: transform .05s ease-in-out, background .2s;
        }
        .search-button:hover  { background: #dbe7fb; }
        .search-button:active { transform: translateY(1px); }

        .field-input:focus,
        .search-button:focus {
            outline: 2px solid #6d8bd5;
            outline-offset: 2px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin: 20px 15px;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination strong {
            display: inline-block;
            padding: 7px 13px;
            border-radius: 6px;
            border: 1px solid #cbd5e0;
            text-decoration: none;
            font-size: 14px;
            color: #2b6cb0;
            background: white;
        }

        .pagination strong {
            background: #2b6cb0;
            color: white;
            border-color: #2b6cb0;
        }

        .pagination a:hover { background: #ebf8ff; }
    </style>
</head>
<body>

<header>
    <h1>Page D'Accueil</h1>
</header>

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

<p style="padding: 0 15px; color: #718096; font-size: 14px;">
    <?= $total ?> offres disponibles — Page <?= $page ?> / <?= $pages ?>
</p>

<?php foreach ($offresPage as $offre): ?>
    <section class="offer">
        <h2><?= htmlspecialchars($offre['titre'],      ENT_QUOTES, 'UTF-8') ?></h2>
        <h3><?= htmlspecialchars($offre['entreprise'], ENT_QUOTES, 'UTF-8') ?></h3>
        <p><strong>Ville :</strong>   <?= htmlspecialchars($offre['ville'],   ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Durée :</strong>   <?= htmlspecialchars($offre['duree'],   ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Domaine :</strong> <?= htmlspecialchars($offre['domaine'], ENT_QUOTES, 'UTF-8') ?></p>
        <input type="button" id="details" value="Détails">
    </section>
<?php endforeach; ?>

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