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
    case 'admin/pilotes':
        (new AdminController())->pilotes();
        break;
    case 'admin/etudiants':
        (new AdminController())->etudiants();
        break;
    case 'admin/etudiants/create':
        (new AdminController())->createEtudiant();
        break;
    case 'admin/etudiants/delete':
        (new AdminController())->deleteEtudiant();
        break;
    case 'admin/etudiants/edit':
        (new AdminController())->editEtudiant();
        break;
    case 'offres/ajouter':
        if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 'pilote' || $_SESSION['user_role'] == 'admin')) {
                (new OfferController())->add();
        } else {
            // Ajout dÃ¢â‚¬â„¢un message de type flash pour affichage via CSS cÃƒÂ´tÃƒÂ© page
            $_SESSION['flash'] = [
                'type' => 'error',
                'message' => 'AccÃƒÂ¨s refusÃƒÂ© : vous devez ÃƒÂªtre connectÃƒÂ© en tant que pilote ou admin pour ajouter une offre.'
            ];
            header('Location: ' . BASE_URL . 'connexion');
            exit;
        }
        break;
    case 'offres/modifier':
        (new OfferController())->edit();
        break;
    case 'offres/modifier/update':
        (new OfferController())->update();
        break;
    case 'offres/supprimer':
        (new OfferController())->delete();
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
        echo twig_render('mentions-legales.html.twig', []);
        break;
    case 'politique-confidentialite':
        echo twig_render('politique-confidentialite.html.twig', []);
        break;
    case 'conditions-generales':
        echo twig_render('conditions-generales.html.twig', []);
        break;
    case 'contact':
        $succes      = false;
        $erreurs     = [];
        $post_nom    = $post_email = $post_sujet = $post_message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post_nom     = trim($_POST['nom']     ?? '');
            $post_email   = trim($_POST['email']   ?? '');
            $post_sujet   = trim($_POST['sujet']   ?? '');
            $post_message = trim($_POST['message'] ?? '');
            if (!$post_nom) $erreurs[] = 'Votre nom est obligatoire.';
            if (!filter_var($post_email, FILTER_VALIDATE_EMAIL)) $erreurs[] = 'Adresse email invalide.';
            if (!$post_sujet) $erreurs[] = 'Veuillez choisir un sujet.';
            if (strlen($post_message) < 20) $erreurs[] = 'Votre message doit faire au moins 20 caractÃƒÂ¨res.';
            if (empty($erreurs)) $succes = true;
        }
        echo twig_render('contact.html.twig', compact('succes', 'erreurs', 'post_nom', 'post_email', 'post_sujet', 'post_message'));
        break;
    default:
        http_response_code(404);
        echo twig_render('errors/404.html.twig', []);
        break;
}