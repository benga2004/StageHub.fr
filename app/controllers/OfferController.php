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
        $model   = new Offer();

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

        $companyModel = new Company();
        foreach ($offresPage as &$offre) {
            $co = $companyModel->getById($offre['entreprise_id']);
            $offre['entreprise_nom'] = $co['nom'] ?? '';
        }
        unset($offre);

        echo twig_render('Accueil.html.twig', [
            'offresPage'  => $offresPage,
            'total'       => $total,
            'pages'       => $pages,
            'page'        => $page,
            'query'       => $query,
            'ville'       => $ville,
            'domaine'     => $domaine,
            'wishlistIds' => $wishlistIds,
        ]);
    }


    public function offres(): void {
        $model   = new Offer();
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

        $companyModel = new Company();
        foreach ($offresPage as &$offre) {
            $co = $companyModel->getById($offre['entreprise_id']);
            $offre['entreprise_nom'] = $co['nom'] ?? '';
        }
        unset($offre);

        echo twig_render('offers/list.html.twig', [
            'offresPage'     => $offresPage,
            'total'          => $total,
            'pages'          => $pages,
            'page'           => $page,
            'query'          => $query,
            'ville'          => $ville,
            'domaine'        => $domaine,
            'wishlistIds'    => $wishlistIds,
            'successMessage' => $successMessage,
        ]);
    }


    public function detail(): void {
        $id    = (int)($_GET['id'] ?? 0);
        $offre = (new Offer())->getById($id);
        if (!$offre) {
            http_response_code(404);
            echo twig_render('errors/404.html.twig', []);
            return;
        }
        $company = (new Company())->getById($offre['entreprise_id']);
        echo twig_render('offers/detail.html.twig', [
            'offre'   => $offre,
            'company' => $company,
            'id'      => $id,
        ]);
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
        echo twig_render('offers/ajout_offres_debut.html.twig', []);
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
        echo twig_render('offers/ajout_offres_etape1.html.twig', []);
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
        echo twig_render('offers/ajout_offres_etape2.html.twig', []);
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