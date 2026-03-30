<?php
class EtudiantController {

    public function profil(): void {
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'etudiant') {
            header('Location: ' . BASE_URL . 'connexion');
            exit;
        }

        $userId  = (int)$_SESSION['user_id'];
        $user    = (new User())->findById($userId);
        $candidatures   = (new Candidature())->getByEtudiant($userId);
        $wishlistOffres = (new Wishlist())->getOffresByEtudiant($userId);

        // Traitement modification du profil
        $succes_profil = '';
        $erreur_profil = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profil') {
            $nom      = trim($_POST['nom']      ?? '');
            $prenom   = trim($_POST['prenom']   ?? '');
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');
            $confirm  = trim($_POST['password_confirm'] ?? '');

            if (!$nom || !$prenom || !$email) {
                $erreur_profil = 'Nom, prénom et email sont obligatoires.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreur_profil = 'Adresse email invalide.';
            } elseif ($email !== $user['email'] && (new User())->emailExists($email)) {
                $erreur_profil = 'Cette adresse email est déjà utilisée.';
            } elseif ($password && strlen($password) < 8) {
                $erreur_profil = 'Le mot de passe doit faire au moins 8 caractères.';
            } elseif ($password && $password !== $confirm) {
                $erreur_profil = 'Les mots de passe ne correspondent pas.';
            } else {
                $data = ['nom' => $nom, 'prenom' => $prenom, 'email' => $email];
                if ($password) $data['password'] = $password;

                (new User())->update($userId, $data);

                // Upload CV si fourni
                if (isset($_FILES['cv_profile']) && $_FILES['cv_profile']['error'] === UPLOAD_ERR_OK) {
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mime  = finfo_file($finfo, $_FILES['cv_profile']['tmp_name']);
                    finfo_close($finfo);

                    if ($mime !== 'application/pdf') {
                        $erreur_profil = 'Le CV doit être un fichier PDF.';
                    } elseif ($_FILES['cv_profile']['size'] > 3 * 1024 * 1024) {
                        $erreur_profil = 'Le CV ne doit pas dépasser 3 Mo.';
                    } else {
                        $uploadDir = BASE_PATH . '/public/uploads/cv/';
                        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                        $filename = 'cv_user_' . $userId . '_' . time() . '.pdf';
                        if (move_uploaded_file($_FILES['cv_profile']['tmp_name'], $uploadDir . $filename)) {
                            (new User())->updateCv($userId, 'uploads/cv/' . $filename);
                        }
                    }
                }

                // Mettre à jour la session
                $_SESSION['user_prenom'] = htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8');

                // Recharger l'utilisateur mis à jour
                $user = (new User())->findById($userId);
                if (empty($erreur_profil)) $succes_profil = 'Profil mis à jour avec succès.';
            }
        }

        echo twig_render('Profil_etudiant.html.twig', [
            'user'           => $user,
            'candidatures'   => $candidatures,
            'wishlistOffres' => $wishlistOffres,
            'succes_profil'  => $succes_profil,
            'erreur_profil'  => $erreur_profil,
            'post_prenom'    => $_POST['prenom'] ?? '',
            'post_nom'       => $_POST['nom']    ?? '',
            'post_email'     => $_POST['email']  ?? '',
        ]);
    }
}
