<?php
class AuthController {
    private bool $loggedIn = false;

    public function login(): void {
        $erreur = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $user = (new User())->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $this->loggedIn = true;
                $_SESSION['user']        = $user;
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_role']  = $user['role'];
                $_SESSION['user_prenom'] = htmlspecialchars($user['prenom'], ENT_QUOTES, 'UTF-8');

                // Redirection selon le rôle
                switch ($user['role']) {
                    case 'admin':   header('Location: ' . BASE_URL . 'admin');             break;
                    case 'pilote':  header('Location: ' . BASE_URL . 'dashboard');          break;
                    default:        header('Location: ' . BASE_URL . 'profil/etudiant');    break;
                }
                exit;
            } else {
                $erreur = 'Email ou mot de passe incorrect.';
            }

        }
        echo twig_render('auth/connexion.html.twig', [
            'erreur'     => $erreur,
            'post_email' => $_POST['email'] ?? '',
        ]);
    }

    public function logout(): void {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . 'connexion');
        exit;
    }

    public function register(): void {
        $title  = "Inscription - StageHub";
        $erreur = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom      = trim($_POST['nom']      ?? '');
            $prenom   = trim($_POST['prenom']   ?? '');
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');
            // Seuls etudiant et pilote peuvent s'inscrire (pas admin)
            $roleRaw  = $_POST['role'] ?? 'etudiant';
            $role     = in_array($roleRaw, ['etudiant', 'pilote'], true) ? $roleRaw : 'etudiant';

            if (!$nom || !$prenom || !$email || !$password) {
                $erreur = 'Tous les champs sont obligatoires.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreur = 'Adresse email invalide.';
            } elseif (strlen($password) < 8) {
                $erreur = 'Le mot de passe doit faire au moins 8 caractères.';
            } elseif (trim($_POST['password_confirm'] ?? '') !== $password) {
                $erreur = 'Les mots de passe ne correspondent pas.';
            } elseif ((new User())->emailExists($email)) {
                $erreur = 'Cette adresse email est déjà utilisée. <a href="' . BASE_URL . 'connexion">Connectez-vous</a> ou utilisez une autre adresse.';
            } else {
                (new User())->create([
                    'nom'      => $nom,
                    'prenom'   => $prenom,
                    'email'    => $email,
                    'password' => $password,
                    'role'     => $role,
                ]);
                header('Location: ' . BASE_URL . 'connexion');
                exit;
            }
        }

        echo twig_render('auth/inscription.html.twig', [
            'erreur'         => $erreur,
            'post_nom'       => $_POST['nom']       ?? '',
            'post_prenom'    => $_POST['prenom']    ?? '',
            'post_telephone' => $_POST['telephone'] ?? '',
            'post_email'     => $_POST['email']     ?? '',
            'post_role'      => $_POST['role']      ?? 'etudiant',
        ]);
    }
}