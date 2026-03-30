<?php

// include '../models/Offer.php';

// $offres = Offer::getAll();

// $parPage = 5;
// $total   = count($offres);
// $pages   = ceil($total / $parPage);

// $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
// $page = max(1, min($page, $pages));

// $debut      = ($page - 1) * $parPage;
// $offresPage = array_slice($offres, $debut, $parPage);

// require "../views/offers/list.php";

class OfferController {
    private int $parPage = 8;

    public function index(): void {

        $title = "Accueil - StageHub";
        $content = "Bienvenue sur StageHub, votre plateforme de référence pour trouver les meilleures offres de stage en entreprise. Explorez notre large sélection d'opportunités de stage, postulez facilement et lancez votre carrière dès aujourd'hui !";

        require BASE_PATH . '/app/views/layout/header.php';
        
        $model   = new Offer();
        $companyModel = new Company();

        $page    = max(1, (int)($_GET['page'] ?? 1));
        $query   = trim($_GET['query'] ?? '');
        $ville   = trim($_GET['ville'] ?? '');
        $domaine = trim($_GET['domaine'] ?? '');
        $offset  = ($page - 1) * $this->parPage;
        $offresPage = $model->search($query, $ville, $domaine, $this->parPage, $offset);
        $total      = $model->countFiltered($query, $ville, $domaine);
        $pages      = (int)ceil(max(1, $total) / $this->parPage);        $wishlistIds = isset($_SESSION['user_id'])
            ? (new Wishlist())->getIdsByEtudiant((int)$_SESSION['user_id'])
            : [];        $wishlistIds = isset($_SESSION['user_id'])
            ? (new Wishlist())->getIdsByEtudiant((int)$_SESSION['user_id'])
            : [];

        require BASE_PATH . '/app/views/Accueil.php';
        require BASE_PATH . '/app/views/offers/list.php';
        require BASE_PATH . '/app/views/layout/footer.php';
    }


    public function offres(): void {

        $title = "Offres de stage";
        $content = "Découvrez les dernières offres de stage disponibles sur StageHub. Que vous soyez à la recherche d'un stage en informatique, marketing, industrie, design ou finance, notre plateforme vous propose une variété d'opportunités pour lancer votre carrière. Postulez dès maintenant et trouvez le stage qui correspond à vos aspirations professionnelles !";
        require BASE_PATH . '/app/views/layout/header.php';

        $model   = new Offer();
        $companyModel = new Company();
        $page    = max(1, (int)($_GET['page'] ?? 1));
        $query   = trim($_GET['query'] ?? '');
        $ville   = trim($_GET['ville'] ?? '');
        $domaine = trim($_GET['domaine'] ?? '');
        $offset  = ($page - 1) * $this->parPage;

        $offresPage = $model->search($query, $ville, $domaine, $this->parPage, $offset);
        $total      = $model->countFiltered($query, $ville, $domaine);
        $pages      = (int)ceil(max(1, $total) / $this->parPage);
        $wishlistIds = isset($_SESSION['user_id'])
            ? (new Wishlist())->getIdsByEtudiant((int)$_SESSION['user_id'])
            : [];

        $successMessage = $_SESSION['success_message'] ?? null;
        unset($_SESSION['success_message']);

        require BASE_PATH . '/app/views/offers/list.php';
        require BASE_PATH . '/app/views/layout/footer.php';

    }


    public function detail(): void {
        $id    = (int)($_GET['id'] ?? 0);
        $offre = (new Offer())->getById($id);
        $company = (new Company())->getById($offre['entreprise_id']);
        if (!$offre) { http_response_code(404); require BASE_PATH . '/app/views/errors/404.php'; return; }
        require BASE_PATH . '/app/views/offers/detail.php';
    }

    public function add(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (empty($_POST['enterpriseStatut'])) {
                header('Location: ' . BASE_URL . 'offres/ajouter');
                exit;
            } else {
                if ($_POST['enterpriseStatut'] === 'hasAccount') {
                    $_SESSION['offer_begin1'] = [
                        'enterpriseNameSearch' => htmlspecialchars($_POST['enterpriseNameSearch'] ?? ''),
                    ];
                    $entreprise = (new Company())->getByName($_SESSION['offer_begin1']['enterpriseNameSearch']);
                    if (!$entreprise) {
                        echo "<script>alert('Entreprise non trouvée. Veuillez vérifier le nom ou créer un compte pour votre entreprise.');</script>";
                        header('Location: ' . BASE_URL . 'offres/ajouter');
                        exit;
                    }
                    $_SESSION['offer_begin1']['entreprise_id'] = $entreprise['id'];

                    header('Location: ' . BASE_URL . 'offres/ajouter/etape1');
                    exit;
                }
                if ($_POST['enterpriseStatut'] === 'hasNoAccount') {
                    $enterpriseName = htmlspecialchars($_POST['enterpriseName'] ?? '');
                    $_SESSION['offer_begin2'] = [
                        'enterpriseName' => $enterpriseName,
                        'description' => htmlspecialchars($_POST['description'] ?? ''),
                        'email' => htmlspecialchars($_POST['email'] ?? ''),
                        'telephone' => htmlspecialchars($_POST['telephone'] ?? ''),
                        'ville' => htmlspecialchars($_POST['ville'] ?? ''),
                        'secteur' => htmlspecialchars($_POST['secteur'] ?? ''),
                    ];

                    (new Company())->create([
                        'nom' => $_SESSION['offer_begin2']['enterpriseName'],
                        'description' => $_SESSION['offer_begin2']['description'],
                        'email' => $_SESSION['offer_begin2']['email'],
                        'telephone' => $_SESSION['offer_begin2']['telephone'],
                        'ville' => $_SESSION['offer_begin2']['ville'],
                        'secteur' => $_SESSION['offer_begin2']['secteur'],
                    ]);

                    $entreprise = (new Company())->getByName($enterpriseName);
                    if ($entreprise) {
                        $_SESSION['offer_begin1']['entreprise_id'] = $entreprise['id'];
                    }

                    header('Location: ' . BASE_URL . 'offres/ajouter/etape1');
                    exit;
                }
            }
            
        }
        require BASE_PATH . '/app/views/offers/ajout_offres_debut.php';
    }

    public function addStep1(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_SESSION['offer_step1'] = [
                'entreprise_id' => $_SESSION['offer_begin1']['entreprise_id'] ?? null,
                'jobTitle'    => htmlspecialchars($_POST['jobTitle'] ?? ''),
                'ville'       => htmlspecialchars($_POST['location'] ?? ''),
                'domaine'     => htmlspecialchars($_POST['domaine'] ?? ''),
                'delai'       => htmlspecialchars($_POST['delai'] ?? ''),
                'numberOfJob' => (int)($_POST['numberOfJob'] ?? 0),
            ];
            header('Location: ' . BASE_URL . 'offres/ajouter/etape2');
            exit;
        }
        require BASE_PATH . '/app/views/offers/ajout_offres_etape1.php';
    }

    public function addStep2(): void {
        if (!isset($_SESSION['offer_step1'])) {
            header('Location: ' . BASE_URL . 'offres/ajouter/etape1');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->store();
            return;
        }
        require BASE_PATH . '/app/views/offers/ajout_offres_etape2.php';
    }

    public function store(): void {
        $step1 = $_SESSION['offer_step1'] ?? null;
        if (!$step1) { header('Location: ' . BASE_URL . 'offres/ajouter/etape1'); exit; }

        $data = array_merge($step1, [
            'description'  => strip_tags($_POST['jobDescription'],'<p><strong><ul><li><br><em><h2><h3>'),
            'duree'       => htmlspecialchars($_POST['durationNumber']) . ' ' . htmlspecialchars($_POST['durationPeriod']),
            'minSalary'    => (int)($_POST['minSalary'] ?? 0),
            'frequence'     => htmlspecialchars($_POST['frequence'] ?? ''),
            'avantages'    => $_POST['avantages'] ?? [],
        ]);

        (new Offer())->create($data);
        unset($_SESSION['offer_step1']);
        $_SESSION['success_message'] = '✅ Offre de stage ajoutée avec succès !';
        header('Location: ' . BASE_URL . 'offres');
        exit;
    }
}