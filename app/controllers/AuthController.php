<?php
class AuthController {

    public function login(): void {
        $erreur = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $user = (new User())->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user;
                header('Location: ' . BASE_URL);
                exit;
            } else {
            $erreur = 'Email ou mot de passe incorrect.';
            echo $erreur;
            }

        }
        require BASE_PATH . '/app/views/auth/connexion.php';
    }

    public function logout(): void {
        session_destroy();
        header('Location: ' . BASE_URL . 'connexion');
        exit;
    }

    public function register(): void {
        $title = "Inscription - StageHub";
        include BASE_PATH . '/app/views/layout/header.php';

        $erreur = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // validation + création
            (new User())->create($_POST);
            header('Location: ' . BASE_URL . 'connexion');
            exit;
        }
        require BASE_PATH . '/app/views/auth/inscription.php';
        include BASE_PATH . '/app/views/layout/footer.php';
    }
}