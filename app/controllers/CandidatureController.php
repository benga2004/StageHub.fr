<?php
class CandidatureController {

    public function index(): void {
        // Guard: doit être connecté
        if (empty($_SESSION['user_id'])) {
            $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            header('Location: ' . BASE_URL . 'connexion' . ($id ? '?redirect=candidature%3Fid%3D' . $id : ''));
            exit;
        }

        $erreurs     = [];
        $succes      = '';
        $offre_id    = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $etudiant_id = (int)$_SESSION['user_id'];

        // Charger les infos utilisateur pour pré-remplir le formulaire
        $user = (new User())->findById($etudiant_id);

        if (!$offre_id) {
            $erreurs[] = 'Aucune offre sélectionnée.';
        }

        // PRG : afficher le message de succès après redirect
        if ($offre_id && isset($_GET['succes'])) {
            $prenom = $user['prenom'] ?? ($_SESSION['user_prenom'] ?? 'Étudiant');
            $succes = htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $offre_id && empty($succes)) {
            $lettre = trim($_POST['lettre'] ?? '');

            if (!$lettre) $erreurs[] = 'La lettre de motivation est obligatoire.';

            // Vérifier doublon
            if (empty($erreurs) && Candidature::alreadyApplied($offre_id, $etudiant_id)) {
                $erreurs[] = 'Vous avez déjà postulé à cette offre.';
            }

            // Validation fichier CV
            if (!isset($_FILES['cv']) || $_FILES['cv']['error'] !== UPLOAD_ERR_OK) {
                $erreurs[] = 'CV obligatoire (PDF).';
            } else {
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                if (finfo_file($finfo, $_FILES['cv']['tmp_name']) !== 'application/pdf') {
                    $erreurs[] = 'Le CV doit être un fichier PDF.';
                }
                finfo_close($finfo);
            }

            // Sauvegarde si pas d'erreurs
            if (empty($erreurs)) {
                $uploadDir = BASE_PATH . '/public/uploads/cv/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $filename = uniqid() . '.pdf';
                $cvPath   = 'uploads/cv/' . $filename;
                $fullPath = BASE_PATH . '/public/' . $cvPath;

                if (move_uploaded_file($_FILES['cv']['tmp_name'], $fullPath)) {
                    if (Candidature::postuler($offre_id, $etudiant_id, $cvPath, $lettre)) {
                        header('Location: ' . BASE_URL . 'candidature?id=' . $offre_id . '&succes=1');
                        exit;
                    } else {
                        $erreurs[] = 'Erreur lors de l\'enregistrement de la candidature.';
                    }
                } else {
                    $erreurs[] = 'Erreur lors du téléversement du CV.';
                }
            }
        }

        echo twig_render('offers/candidature.html.twig', [
            'erreurs'       => $erreurs,
            'succes'        => $succes,
            'offre_id'      => $offre_id,
            'user'          => $user,
            'post_prenom'   => $_POST['prenom']  ?? '',
            'post_nom'      => $_POST['nom']     ?? '',
            'post_email'    => $_POST['email']   ?? '',
            'post_lettre'   => $_POST['lettre']  ?? '',
        ]);
    }
}