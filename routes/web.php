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
    case 'dashboard':
        (new PiloteController())->dashboard();
        break;
    case 'admin':
        (new AdminController())->index();
        break;
    case 'admin/entreprises':
        (new AdminController())->entreprises();
        break;
    case 'offres/ajouter':
        if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 'pilote' || $_SESSION['user_role'] == 'admin')) {
                (new OfferController())->add();
        } else {
            // Ajout d’un message de type flash pour affichage via CSS côté page
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'Accès refusé : vous devez être connecté en tant que pilote ou admin pour ajouter une offre.'
            ];
            header('Location: ' . BASE_URL . 'connexion');
            exit;
        }
        (new OfferController())->add();
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
    case 'wishlist':
        (new WishlistController())->index();
        break;
    case 'wishlist/toggle':
        (new WishlistController())->toggle();
        break;
    case 'mentions-legales':
        require BASE_PATH . '/app/views/mentions-legales.php';
        break;
    case 'politique-confidentialite':
        require BASE_PATH . '/app/views/politique-confidentialite.php';
        break;
    case 'conditions-generales':
        require BASE_PATH . '/app/views/conditions-generales.php';
        break;
    case 'contact':
        require BASE_PATH . '/app/views/contact.php';
        break;
    default:
        require '../app/views/errors/404.php';
        break;
}