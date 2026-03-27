<?php
class CandidatureController {

    public function index(): void {
        $erreurs = [];
        $succes  = '';
        $offre_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if (!$offre_id) {
            $erreurs[] = 'Aucune offre sélectionnée.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $offre_id) {
            $nom    = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');
            $email  = trim($_POST['email'] ?? '');
            $lettre = trim($_POST['lettre'] ?? '');

            // Validation
            if (!$nom)    $erreurs[] = 'Le nom est obligatoire.';
            if (!$prenom) $erreurs[] = 'Le prénom est obligatoire.';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erreurs[] = 'Email invalide.';
            if (!$lettre) $erreurs[] = 'La lettre de motivation est obligatoire.';

            // Validation fichier CV
            if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
                $erreurs[] = 'CV obligatoire.';
            } else {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                if (finfo_file($finfo, $_FILES['cv']['tmp_name']) !== 'application/pdf') {
                    $erreurs[] = 'Le CV doit être un PDF.';
                }
                finfo_close($finfo);
            }

            // Si pas d'erreurs, sauvegarder
            if (empty($erreurs)) {
                $uploadDir = BASE_PATH . '/storage/cv/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $cvPath = 'storage/cv/' . uniqid() . '.pdf';
                $fullPath = BASE_PATH . '/' . $cvPath;

                if (move_uploaded_file($_FILES['cv']['tmp_name'], $fullPath)) {
                    // Sauvegarder en base de données
                    if (Candidature::postuler($offre_id, $prenom, $nom, $email, $cvPath, $lettre)) {
                        $succes = htmlspecialchars("$prenom $nom");
                    } else {
                        $erreurs[] = 'Erreur lors de l\'enregistrement de la candidature.';
                    }
                } else {
                    $erreurs[] = 'Erreur lors du téléversement du CV.';
                }
            }
        }

        require BASE_PATH . '/app/views/offers/candidature.php';
    }
}