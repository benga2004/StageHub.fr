<?php
class AdminController {

    private function guard(): void {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: ' . BASE_URL . 'connexion');
            exit;
        }
    }

    private function guardAdminOrPilote(): void {
        $role = $_SESSION['user_role'] ?? '';
        if (empty($_SESSION['user_id']) || !in_array($role, ['admin', 'pilote'], true)) {
            header('Location: ' . BASE_URL . 'connexion');
            exit;
        }
    }

    public function index(): void {
        $this->guard();

        $db = Database::connect();

        $nbEntreprises  = (int) $db->query('SELECT COUNT(*) FROM entreprises')->fetchColumn();
        $nbOffres       = (int) $db->query('SELECT COUNT(*) FROM offres')->fetchColumn();
        $nbEtudiants    = (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'etudiant'")->fetchColumn();
        $nbPilotes      = (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'pilote'")->fetchColumn();
        $nbAdmins       = (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
        $nbCandidatures = (int) $db->query('SELECT COUNT(*) FROM candidatures')->fetchColumn();
        $nbWishlists    = (int) $db->query('SELECT COUNT(*) FROM wishlist')->fetchColumn();

        $stats = [
            ['icon' => 'bi bi-buildings-fill',  'value' => $nbEntreprises,  'label' => 'Entreprises',  'detail' => 'fiches partenaires'],
            ['icon' => 'bi bi-briefcase-fill',   'value' => $nbOffres,       'label' => 'Offres',       'detail' => 'publiées sur la plateforme'],
            ['icon' => 'bi bi-mortarboard-fill', 'value' => $nbEtudiants,    'label' => 'Étudiants',   'detail' => 'comptes en suivi'],
            ['icon' => 'bi bi-envelope-fill',    'value' => $nbCandidatures, 'label' => 'Candidatures','detail' => 'envoyées au total'],
        ];

        $accountSummary = [
            ['label' => 'Admins',     'value' => $nbAdmins,     'tone' => 'tone-admin'],
            ['label' => 'Pilotes',    'value' => $nbPilotes,    'tone' => 'tone-pilote'],
            ['label' => 'Étudiants',  'value' => $nbEtudiants,  'tone' => 'tone-etudiant'],
            ['label' => 'Wishlists',  'value' => $nbWishlists,  'tone' => 'tone-wishlist'],
        ];

        $modules = [
            [
                'title' => 'Entreprises',
                'description' => 'Gerer les fiches entreprise et consulter les coordonnees clefs.',
                'count' => $nbEntreprises,
                'href' => BASE_URL . 'admin/entreprises',
                'status' => 'Disponible',
                'icon' => 'bi bi-buildings-fill',
                'is_active' => true,
            ],
            [
                'title' => 'Catalogue des offres',
                'description' => 'Acceder a la liste complete des offres visibles sur le site.',
                'count' => $nbOffres,
                'href' => BASE_URL . 'offres',
                'status' => 'Consultation',
                'icon' => 'bi bi-briefcase-fill',
                'is_active' => true,
            ],
            [
                'title' => 'Comptes pilotes',
                'description' => 'Vue d ensemble des responsables de promotion.',
                'count' => $nbPilotes,
                'href' => null,
                'status' => 'Bientot',
                'icon' => 'bi bi-person-badge-fill',
                'is_active' => false,
            ],
            [
                'title' => 'Comptes etudiants',
                'description' => 'Suivi global des etudiants inscrits et de leur activite.',
                'count' => $nbEtudiants,
                'href' => BASE_URL . 'admin/etudiants',
                'status' => 'Disponible',
                'icon' => 'bi bi-mortarboard-fill',
                'is_active' => true,
            ],
        ];

        $recentUsersStmt = $db->query("SELECT nom, prenom, email, role FROM users ORDER BY id DESC LIMIT 5");
        $recentUsers = $recentUsersStmt ? $recentUsersStmt->fetchAll(PDO::FETCH_ASSOC) : [];

        $adminName = $_SESSION['user_prenom'] ?? 'Admin';

        echo twig_render('admin/admin.html.twig', [
            'adminName'      => $adminName,
            'stats'          => $stats,
            'accountSummary' => $accountSummary,
            'modules'        => $modules,
            'recentUsers'    => $recentUsers,
        ]);
    }

    public function entreprises(): void {
        $this->guard();

        $entreprises = (new Company())->getAll();

        echo twig_render('admin/entreprises.html.twig', [
            'entreprises' => $entreprises,
        ]);
    }

    public function etudiants(): void {
        $this->guardAdminOrPilote();

        $userModel = new User();
        $search = trim($_GET['q'] ?? '');
        $message = '';
        $messageType = '';

        if (!empty($_SESSION['flash_etudiants'])) {
            $message = $_SESSION['flash_etudiants']['message'];
            $messageType = $_SESSION['flash_etudiants']['type'];
            unset($_SESSION['flash_etudiants']);
        }

        if ($search !== '') {
            $etudiants = $userModel->searchByRole('etudiant', $search);
        } else {
            $etudiants = $userModel->getByRole('etudiant');
        }

        echo twig_render('admin/etudiants.html.twig', [
            'etudiants'   => $etudiants,
            'search'      => $search,
            'message'     => $message,
            'messageType' => $messageType,
        ]);
    }

    public function createEtudiant(): void {
        $this->guardAdminOrPilote();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/etudiants');
            exit;
        }

        $nom      = trim($_POST['nom'] ?? '');
        $prenom   = trim($_POST['prenom'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$nom || !$prenom || !$email || !$password) {
            $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Tous les champs sont obligatoires.'];
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Adresse email invalide.'];
        } elseif (strlen($password) < 8) {
            $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Le mot de passe doit faire au moins 8 caractères.'];
        } elseif ((new User())->emailExists($email)) {
            $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Cette adresse email est déjà utilisée.'];
        } else {
            (new User())->create([
                'nom'      => $nom,
                'prenom'   => $prenom,
                'email'    => $email,
                'password' => $password,
                'role'     => 'etudiant',
            ]);
            $_SESSION['flash_etudiants'] = ['type' => 'success', 'message' => 'Étudiant créé avec succès.'];
        }

        header('Location: ' . BASE_URL . 'admin/etudiants');
        exit;
    }

    public function deleteEtudiant(): void {
        $this->guardAdminOrPilote();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/etudiants');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Identifiant invalide.'];
        } else {
            $user = (new User())->findById($id);
            if (empty($user) || $user['role'] !== 'etudiant') {
                $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Étudiant introuvable.'];
            } else {
                (new User())->delete($id);
                $_SESSION['flash_etudiants'] = ['type' => 'success', 'message' => 'Étudiant supprimé avec succès.'];
            }
        }

        header('Location: ' . BASE_URL . 'admin/etudiants');
        exit;
    }

    public function editEtudiant(): void {
        $this->guardAdminOrPilote();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/etudiants');
            exit;
        }

        $id       = (int)($_POST['id'] ?? 0);
        $nom      = trim($_POST['nom'] ?? '');
        $prenom   = trim($_POST['prenom'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($id <= 0) {
            $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Identifiant invalide.'];
        } else {
            $user = (new User())->findById($id);
            if (empty($user) || $user['role'] !== 'etudiant') {
                $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Étudiant introuvable.'];
            } elseif (!$nom || !$prenom || !$email) {
                $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Nom, prénom et email sont obligatoires.'];
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Adresse email invalide.'];
            } elseif ($email !== $user['email'] && (new User())->emailExists($email)) {
                $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Cette adresse email est déjà utilisée.'];
            } elseif ($password && strlen($password) < 8) {
                $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Le mot de passe doit faire au moins 8 caractères.'];
            } else {
                $data = ['nom' => $nom, 'prenom' => $prenom, 'email' => $email];
                if ($password) $data['password'] = $password;
                (new User())->update($id, $data);
                $_SESSION['flash_etudiants'] = ['type' => 'success', 'message' => 'Étudiant modifié avec succès.'];
            }
        }

        header('Location: ' . BASE_URL . 'admin/etudiants');
        exit;
    }
}
