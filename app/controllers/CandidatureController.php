<?php
class CandidatureController {

    public function index(): void {
        $erreurs = [];
        $succes  = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom    = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email  = trim($_POST['email'] ?? '');
            $lettre = trim($_POST['lettre'] ?? '');

            if (!$nom)    $erreurs[] = 'Le nom est obligatoire.';
            if (!$prenom) $erreurs[] = 'Le prénom est obligatoire.';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = 'Email invalide.';
            if (!$lettre) $erreurs[] = 'La lettre de motivation est obligatoire.';

            if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
                $erreurs[] = 'CV obligatoire.';
            } else {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                if (finfo_file($finfo, $_FILES['cv']['tmp_name']) !== 'application/pdf')
                    $erreurs[] = 'Le CV doit être un PDF.';
                finfo_close($finfo);
            }

            if (empty($erreurs)) {
                $dest = BASE_PATH . '/storage/cv/' . uniqid() . '.pdf';
                move_uploaded_file($_FILES['cv']['tmp_name'], $dest);
                $succes = htmlspecialchars("$prenom $nom");
            }
        }

        require BASE_PATH . '/app/views/offers/candidature.php';
    }
}