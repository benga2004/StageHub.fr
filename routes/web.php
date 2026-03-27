<?php

/* $url = trim($_GET['url'] ?? '', '/');

match($url) {
    '', 'offres'              => (new OfferController())->index(),
    'offres/detail'           => (new OfferController())->detail(),
    'connexion'               => (new AuthController())->login(),
    'deconnexion'             => (new AuthController())->logout(),
    'inscription'             => (new AuthController())->register(),
    'candidature'             => (new CandidatureController())->index(),
    'profil/etudiant'         => (new EtudiantController())->profil(),
    'profil/entreprise'       => (new EntrepriseController())->profil(),
    'admin'                   => (new AdminController())->index(),
    'admin/entreprises'       => (new AdminController())->entreprises(),
    'offres/ajouter/etape1'   => (new OfferController())->addStep1(),
    'offres/ajouter/etape2'   => (new OfferController())->addStep2(),
    'offres/ajouter/store'    => (new OfferController())->store(),
    default                   => require '../app/views/errors/404.php',
}; 

<?php */
$url = trim($_GET['url'] ?? '', '/');

switch ($url) {
    case '':
        (new OfferController())->index();
        break;
    case 'offres':
        (new OfferController())->offres();
        break;
    case 'offres/detail':
        (new OfferController())->detail();
        break;
    case 'connexion':
        (new AuthController())->login();
        break;
    case 'deconnexion':
        (new AuthController())->logout();
        break;
    case 'inscription':
        (new AuthController())->register();
        break;
    case 'candidature':
        (new CandidatureController())->index();
        break;
    case 'profil/etudiant':
        (new EtudiantController())->profil();
        break;
    case 'profil/entreprise':
        (new EntrepriseController())->profil();
        break;
    case 'admin':
        (new AdminController())->index();
        break;
    case 'admin/entreprises':
        (new AdminController())->entreprises();
        break;
    case 'offres/ajouter':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['enterpriseStatut'])) {
                if ($_POST['enterpriseStatut'] === 'hasAccount') {
                    $_SESSION['offer_begin'] = [
                        'enterpriseNameSearch' => htmlspecialchars($_POST['enterpriseNameSearch'] ?? ''),
                    ];
                    $company = (new Company())->getByName($_SESSION['offer_begin']['enterpriseNameSearch']);
                    if (!$company) {
                        $_SESSION['offer_begin2'] = [
                            'enterpriseName' => htmlspecialchars($_POST['enterpriseNameSearch'] ?? ''),
                        ];
                    }
                }
            }
        }
        require BASE_PATH . '/app/views/offers/ajout_offres_debut.php';
        break;
    case 'offres/ajouter/etape1':
        (new OfferController())->addStep1();
        break;
    case 'offres/ajouter/etape2':
        (new OfferController())->addStep2();
        break;
    case 'offres/ajouter/store':
        (new OfferController())->store();
        break;
    default:
        require '../app/views/errors/404.php';
        break;
}