<?php
class AddOfferController {

    public function step1() {
        // Affiche le formulaire étape 1
        include '../views/offers/ajout_offres_etape1.php';
    }

    public function step2() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=addOffer_step1');
            exit;
        }

        // Stocker les données step1 en session
        session_start();
        $_SESSION['offer_step1'] = [
            'jobTitle'    => htmlspecialchars($_POST['jobTitle']),
            'mobilite'    => htmlspecialchars($_POST['mobilite']),
            'location'    => htmlspecialchars($_POST['location']),
            'delai'       => htmlspecialchars($_POST['delai']),
            'numberOfJob' => (int)$_POST['numberOfJob'],
        ];

        include '../views/offers/ajout_offres_etape2.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=addOffer_step1');
            exit;
        }

        session_start();
        $step1 = $_SESSION['offer_step1'] ?? null;

        if (!$step1) {
            header('Location: index.php?action=addOffer_step1');
            exit;
        }

        $data = array_merge($step1, [
            'description'    => $_POST['jobDescription'],
            'durationNumber' => (int)$_POST['durationNumber'],
            'durationPeriod' => htmlspecialchars($_POST['durationPeriod']),
            'minSalary'      => (int)$_POST['minSalary'],
            'maxSalary'      => (int)$_POST['maxSalary'],
            'frequence'      => htmlspecialchars($_POST['frequence']),
            'avantages'      => $_POST['avantages'] ?? [],
        ]);

        $offerModel = new Offer();
        $offerModel->create($data);

        // Test de la création de l'offre
        echo $data;

        unset($_SESSION['offer_step1']);
        header('Location: index.php?action=offers');
        exit;
    }
}