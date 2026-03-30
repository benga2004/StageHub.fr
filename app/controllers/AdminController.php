<?php

class AdminController
{
    private function ensureAdmin(): void
    {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: ' . BASE_URL . 'connexion');
            exit;
        }
    }

    public function index(): void
    {
        $this->ensureAdmin();

        $db = Database::connect();

        $nbEntreprises = (int) $db->query('SELECT COUNT(*) FROM entreprises')->fetchColumn();
        $nbOffres = (int) $db->query('SELECT COUNT(*) FROM offres')->fetchColumn();
        $nbEtudiants = (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'etudiant'")->fetchColumn();
        $nbPilotes = (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'pilote'")->fetchColumn();
        $nbAdmins = (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
        $nbCandidatures = (int) $db->query('SELECT COUNT(*) FROM candidatures')->fetchColumn();
        $nbWishlists = (int) $db->query('SELECT COUNT(*) FROM wishlist')->fetchColumn();

        $stats = [
            [
                'icon' => 'bi bi-buildings-fill',
                'value' => $nbEntreprises,
                'label' => 'Entreprises',
                'detail' => 'fiches partenaires',
            ],
            [
                'icon' => 'bi bi-briefcase-fill',
                'value' => $nbOffres,
                'label' => 'Offres',
                'detail' => 'publiees sur la plateforme',
            ],
            [
                'icon' => 'bi bi-mortarboard-fill',
                'value' => $nbEtudiants,
                'label' => 'Etudiants',
                'detail' => 'comptes en suivi',
            ],
            [
                'icon' => 'bi bi-envelope-fill',
                'value' => $nbCandidatures,
                'label' => 'Candidatures',
                'detail' => 'envoyees au total',
            ],
        ];

        $accountSummary = [
            [
                'label' => 'Admins',
                'value' => $nbAdmins,
                'tone' => 'tone-admin',
            ],
            [
                'label' => 'Pilotes',
                'value' => $nbPilotes,
                'tone' => 'tone-pilote',
            ],
            [
                'label' => 'Etudiants',
                'value' => $nbEtudiants,
                'tone' => 'tone-etudiant',
            ],
            [
                'label' => 'Wishlists',
                'value' => $nbWishlists,
                'tone' => 'tone-wishlist',
            ],
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
                'href' => null,
                'status' => 'Bientot',
                'icon' => 'bi bi-mortarboard-fill',
                'is_active' => false,
            ],
        ];

        $recentUsersStmt = $db->query("SELECT nom, prenom, email, role FROM users ORDER BY id DESC LIMIT 5");
        $recentUsers = $recentUsersStmt ? $recentUsersStmt->fetchAll(PDO::FETCH_ASSOC) : [];

        $adminName = $_SESSION['user_prenom'] ?? 'Admin';

        require BASE_PATH . '/app/views/admin/admin.php';
    }

    public function entreprises(): void
    {
        $this->ensureAdmin();

        $companyModel = new Company();
        $entreprises = $companyModel->getAll();

        require BASE_PATH . '/app/views/admin/entreprises.php';
    }
}

