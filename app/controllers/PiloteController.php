<?php
class PiloteController {

    public function dashboard(): void {
        // Guard : doit être connecté en tant que pilote
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'pilote') {
            header('Location: ' . BASE_URL . 'connexion');
            exit;
        }

        $db = Database::connect();

        // ── Infos pilote connecté ──────────────────────────────────────────
        $stmt = $db->prepare('SELECT nom, prenom FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        $pilote = $stmt->fetch(PDO::FETCH_ASSOC);

        // ── Stats ──────────────────────────────────────────────────────────
        $nbEtudiants    = (int)$db->query("SELECT COUNT(*) FROM users WHERE role = 'etudiant'")->fetchColumn();
        $nbCandidatures = (int)$db->query("SELECT COUNT(*) FROM candidatures")->fetchColumn();
        $nbStages       = (int)$db->query("SELECT COUNT(DISTINCT etudiant_id) FROM candidatures WHERE statut = 'acceptee'")->fetchColumn();
        $nbSansCand     = (int)$db->query("
            SELECT COUNT(*) FROM users u
            WHERE u.role = 'etudiant'
            AND NOT EXISTS (SELECT 1 FROM candidatures c WHERE c.etudiant_id = u.id)
        ")->fetchColumn();

        $stats = [
            ['icon' => '<i class="bi bi-mortarboard-fill"></i>',              'val' => $nbEtudiants,    'lbl' => 'Étudiants',    'delta' => 'inscrits',             'color' => 'var(--primary)', 'cls' => 'c1'],
            ['icon' => '<i class="bi bi-check-circle-fill"></i>',             'val' => $nbStages,       'lbl' => 'Stages',        'delta' => 'candidatures acceptées','color' => '#40c057',        'cls' => 'c2'],
            ['icon' => '<i class="bi bi-envelope-fill"></i>',                 'val' => $nbCandidatures, 'lbl' => 'Candidatures',  'delta' => 'au total',             'color' => '#fd7e14',        'cls' => 'c3'],
            ['icon' => '<i class="bi bi-exclamation-triangle-fill"></i>',     'val' => $nbSansCand,     'lbl' => 'Sans cand.',    'delta' => 'à relancer',           'color' => '#fa5252',        'cls' => 'c4'],
        ];

        // ── Liste étudiants ────────────────────────────────────────────────
        $rows = $db->query("
            SELECT u.nom, u.prenom,
                   COUNT(c.id) AS nb_cand,
                   MAX(CASE WHEN c.statut = 'acceptee' THEN 1 ELSE 0 END) AS stage_trouve
            FROM users u
            LEFT JOIN candidatures c ON c.etudiant_id = u.id
            WHERE u.role = 'etudiant'
            GROUP BY u.id, u.nom, u.prenom
            ORDER BY u.nom
        ")->fetchAll(PDO::FETCH_ASSOC);

        $etudiants = array_map(function ($e) {
            $hasStage = (bool)$e['stage_trouve'];
            $noCand   = (int)$e['nb_cand'] === 0;
            return [
                'initiale' => strtoupper(mb_substr($e['prenom'], 0, 1)),
                'nom'      => $e['prenom'] . ' ' . $e['nom'],
                'cand'     => (int)$e['nb_cand'],
                'badge'    => $hasStage ? 'badge-green'  : ($noCand ? 'badge-red'  : 'badge-orange'),
                'statut'   => $hasStage ? 'Stage trouvé' : ($noCand ? 'Sans candidature' : 'En recherche'),
            ];
        }, $rows);

        // ── Barres de progression ──────────────────────────────────────────
        // Ancienne syntaxe (PHP 7.4+):
        // $nbEnRecherche = count(array_filter($etudiants, fn ($e) => $e['statut'] === 'En recherche'));
        $nbEnRecherche = count(array_filter($etudiants, function ($e) {
            return $e['statut'] === 'En recherche';
        }));
        $barres = [];
        if ($nbEtudiants > 0) {
            $barres = [
                ['lbl' => 'Stages trouvés',   'val' => "$nbStages/$nbEtudiants",      'pct' => round($nbStages       / $nbEtudiants * 100), 'color' => '#38a169'],
                ['lbl' => 'En recherche',     'val' => "$nbEnRecherche/$nbEtudiants", 'pct' => round($nbEnRecherche  / $nbEtudiants * 100), 'color' => 'var(--primary)'],
                ['lbl' => 'Sans candidature', 'val' => "$nbSansCand/$nbEtudiants",    'pct' => round($nbSansCand     / $nbEtudiants * 100), 'color' => '#e53e3e'],
            ];
        }

        // ── Candidatures récentes (10 dernières) ──────────────────────────
        $rows = $db->query("
            SELECT u.nom, u.prenom, o.titre AS poste, ent.nom AS entreprise,
                   c.statut, DATE(c.created_at) AS date_cand
            FROM candidatures c
            JOIN users u       ON u.id   = c.etudiant_id
            JOIN offres o      ON o.id   = c.offre_id
            JOIN entreprises ent ON ent.id = o.entreprise_id
            ORDER BY c.created_at DESC
            LIMIT 10
        ")->fetchAll(PDO::FETCH_ASSOC);

        $candidatures = array_map(function ($c) {
            // Ancienne syntaxe (PHP 8+):
            // $badge  = match($c['statut']) { 'acceptee' => 'badge-green', 'refusee' => 'badge-red', default => 'badge-orange' };
            // $statut = match($c['statut']) { 'acceptee' => 'Acceptée',    'refusee' => 'Refusée',   default => 'En attente'   };

            switch ($c['statut']) {
                case 'acceptee':
                    $badge = 'badge-green';
                    $statut = 'Acceptée';
                    break;
                case 'refusee':
                    $badge = 'badge-red';
                    $statut = 'Refusée';
                    break;
                default:
                    $badge = 'badge-orange';
                    $statut = 'En attente';
            }

            return [
                'initiale'   => strtoupper(mb_substr($c['prenom'], 0, 1)),
                'nom'        => $c['prenom'] . ' ' . $c['nom'],
                'poste'      => $c['poste'],
                'entreprise' => $c['entreprise'],
                'date'       => $c['date_cand'],
                'badge'      => $badge,
                'statut'     => $statut,
            ];
        }, $rows);

        // ── Activité récente ───────────────────────────────────────────────
        $activites = [];
        foreach (array_slice($candidatures, 0, 5) as $c) {
            // Ancienne syntaxe (PHP 8+):
            // $dot = match($c['badge']) { 'badge-green' => '#38a169', 'badge-red' => '#fa5252', default => '#fd7e14' };
            // $txt = match($c['statut']) {
            //     'Acceptée' => $c['nom'] . ' a obtenu son stage – ' . $c['entreprise'],
            //     'Refusée'  => $c['nom'] . ' – candidature refusée par ' . $c['entreprise'],
            //     default    => $c['nom'] . ' a postulé chez ' . $c['entreprise'],
            // };

            switch ($c['badge']) {
                case 'badge-green':
                    $dot = '#38a169';
                    break;
                case 'badge-red':
                    $dot = '#fa5252';
                    break;
                default:
                    $dot = '#fd7e14';
                    break;
            }

            switch ($c['statut']) {
                case 'Acceptée':
                    $txt = $c['nom'] . ' a obtenu son stage – ' . $c['entreprise'];
                    break;
                case 'Refusée':
                    $txt = $c['nom'] . ' – candidature refusée par ' . $c['entreprise'];
                    break;
                default:
                    $txt = $c['nom'] . ' a postulé chez ' . $c['entreprise'];
                    break;
            }

            $activites[] = ['dot' => $dot, 'icon' => 'bi-envelope-fill', 'txt' => $txt, 'time' => $c['date']];
        }

        echo twig_render('offers/dashbord_pilote.html.twig', [
            'pilote'       => $pilote,
            'stats'        => $stats,
            'etudiants'    => $etudiants,
            'barres'       => $barres,
            'candidatures' => $candidatures,
            'activites'    => $activites,
        ]);
    }
}
