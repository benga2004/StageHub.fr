<?php
class AdminController {

    private function guard(): void {
        if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: ' . BASE_URL . 'connexion');
            exit;
        }
    }

    private function setEnterpriseFlash(string $type, string $message): void {
        $_SESSION['admin_entreprises_flash'] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    private function consumeEnterpriseFlash(): ?array {
        $flash = $_SESSION['admin_entreprises_flash'] ?? null;
        unset($_SESSION['admin_entreprises_flash']);
        return $flash;
    }

    private function redirectToRoute(string $route, array $query = []): void {
        $url = BASE_URL . ltrim($route, '/');
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }
        header('Location: ' . $url);
        exit;
    }

    private function isValidCsrf(): bool {
        return !empty($_POST['csrf_token'])
            && !empty($_SESSION['csrf_token'])
            && hash_equals($_SESSION['csrf_token'], (string) $_POST['csrf_token']);
    }

    private function getCompanyFormDataFromPost(): array {
        return [
            'nom' => trim($_POST['nom'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telephone' => trim($_POST['telephone'] ?? ''),
            'ville' => trim($_POST['ville'] ?? ''),
            'secteur' => trim($_POST['secteur'] ?? ''),
        ];
    }

    private function mapCompanyFormDataToQueryParams(array $data): array {
        return [
            ':nom' => $data['nom'],
            ':description' => $data['description'] !== '' ? $data['description'] : null,
            ':email' => $data['email'] !== '' ? $data['email'] : null,
            ':telephone' => $data['telephone'] !== '' ? $data['telephone'] : null,
            ':ville' => $data['ville'] !== '' ? $data['ville'] : null,
            ':secteur' => $data['secteur'] !== '' ? $data['secteur'] : null,
        ];
    }

    private function validateCompanyFormData(array $data, Company $companyModel, ?int $companyId = null): array {
        $errors = [];

        if ($data['nom'] === '') {
            $errors[] = 'Le nom de l entreprise est obligatoire.';
        }

        if ($data['email'] !== '' && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'L email de contact n est pas valide.';
        }

        $existingCompany = $data['nom'] !== '' ? $companyModel->getByName($data['nom']) : null;
        if ($existingCompany && (int) $existingCompany['id'] !== (int) $companyId) {
            $errors[] = 'Une entreprise avec ce nom existe deja.';
        }

        return $errors;
    }

    public function index(): void {
        $this->guard();

        $db = Database::connect();

        $nbEntreprises = (int) $db->query('SELECT COUNT(*) FROM entreprises')->fetchColumn();
        $nbOffres = (int) $db->query('SELECT COUNT(*) FROM offres')->fetchColumn();
        $nbEtudiants = (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'etudiant'")->fetchColumn();
        $nbPilotes = (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'pilote'")->fetchColumn();
        $nbAdmins = (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
        $nbCandidatures = (int) $db->query('SELECT COUNT(*) FROM candidatures')->fetchColumn();
        $nbWishlists = (int) $db->query('SELECT COUNT(*) FROM wishlist')->fetchColumn();

        $stats = [
            ['icon' => 'bi bi-buildings-fill', 'value' => $nbEntreprises, 'label' => 'Entreprises', 'detail' => 'fiches partenaires'],
            ['icon' => 'bi bi-briefcase-fill', 'value' => $nbOffres, 'label' => 'Offres', 'detail' => 'publiees sur la plateforme'],
            ['icon' => 'bi bi-mortarboard-fill', 'value' => $nbEtudiants, 'label' => 'Etudiants', 'detail' => 'comptes en suivi'],
            ['icon' => 'bi bi-envelope-fill', 'value' => $nbCandidatures, 'label' => 'Candidatures', 'detail' => 'envoyees au total'],
        ];

        $accountSummary = [
            ['label' => 'Admins', 'value' => $nbAdmins, 'tone' => 'tone-admin'],
            ['label' => 'Pilotes', 'value' => $nbPilotes, 'tone' => 'tone-pilote'],
            ['label' => 'Etudiants', 'value' => $nbEtudiants, 'tone' => 'tone-etudiant'],
            ['label' => 'Wishlists', 'value' => $nbWishlists, 'tone' => 'tone-wishlist'],
        ];

        $modules = [
            [
                'title' => 'Entreprises',
                'description' => 'Gerer les fiches entreprise et consulter les coordonnees utiles.',
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

        $adminName = trim((string) ($_SESSION['user_prenom'] ?? '')) ?: 'Admin';

        echo twig_render('admin/admin.html.twig', [
            'adminName' => $adminName,
            'stats' => $stats,
            'accountSummary' => $accountSummary,
            'modules' => $modules,
            'recentUsers' => $recentUsers,
        ]);
    }

    public function entreprises(): void {
        $this->guard();

        $companyModel = new Company();
        $enterpriseFlash = $this->consumeEnterpriseFlash();
        $formErrors = [];
        $formMode = 'create';
        $selectedCompanyId = (int) ($_GET['entreprise'] ?? 0);
        $editCompanyId = (int) ($_GET['edit'] ?? 0);
        $companyForm = [
            'nom' => '',
            'description' => '',
            'email' => '',
            'telephone' => '',
            'ville' => '',
            'secteur' => '',
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $selectedCompanyId = (int) ($_POST['selected_company_id'] ?? $selectedCompanyId);

            if (!$this->isValidCsrf()) {
                $formErrors[] = 'Le token de securite est invalide. Recharge la page et recommence.';
            } elseif ($action === 'create') {
                $companyForm = $this->getCompanyFormDataFromPost();
                $formErrors = $this->validateCompanyFormData($companyForm, $companyModel);

                if (empty($formErrors)) {
                    $created = $companyModel->create($this->mapCompanyFormDataToQueryParams($companyForm));
                    if ($created) {
                        $newCompany = $companyModel->getByName($companyForm['nom']);
                        $this->setEnterpriseFlash('success', 'Entreprise creee avec succes.');
                        $this->redirectToRoute('admin/entreprises', ['entreprise' => (int) ($newCompany['id'] ?? 0)]);
                    }

                    $formErrors[] = 'Impossible de creer cette entreprise.';
                }
            } elseif ($action === 'update') {
                $editCompanyId = (int) ($_POST['entreprise_id'] ?? 0);
                $selectedCompanyId = $editCompanyId;
                $formMode = 'update';
                $companyForm = $this->getCompanyFormDataFromPost();
                $formErrors = $this->validateCompanyFormData($companyForm, $companyModel, $editCompanyId);

                if ($editCompanyId <= 0) {
                    $formErrors[] = 'Entreprise introuvable pour la modification.';
                }

                if (empty($formErrors)) {
                    $updated = $companyModel->update($editCompanyId, $this->mapCompanyFormDataToQueryParams($companyForm));
                    if ($updated) {
                        $this->setEnterpriseFlash('success', 'Entreprise mise a jour avec succes.');
                        $this->redirectToRoute('admin/entreprises', ['entreprise' => $editCompanyId]);
                    }

                    $formErrors[] = 'Impossible de mettre a jour cette entreprise.';
                }
            } elseif ($action === 'delete') {
                $companyId = (int) ($_POST['entreprise_id'] ?? 0);

                if ($companyId <= 0 || empty($companyModel->getById($companyId))) {
                    $this->setEnterpriseFlash('error', 'Entreprise introuvable.');
                    $this->redirectToRoute('admin/entreprises');
                }

                $offerCount = $companyModel->countOffers($companyId);
                $evaluationCount = $companyModel->countEvaluations($companyId);

                if ($offerCount > 0 || $evaluationCount > 0) {
                    $this->setEnterpriseFlash('error', 'Suppression bloquee : retire d abord les offres et evaluations liees a cette entreprise.');
                    $this->redirectToRoute('admin/entreprises', ['entreprise' => $companyId]);
                }

                if ($companyModel->delete($companyId)) {
                    $this->setEnterpriseFlash('success', 'Entreprise supprimee avec succes.');
                } else {
                    $this->setEnterpriseFlash('error', 'Impossible de supprimer cette entreprise.');
                }

                $this->redirectToRoute('admin/entreprises');
            }
        }

        $filters = [
            'q' => trim($_GET['q'] ?? ''),
            'ville' => trim($_GET['ville'] ?? ''),
            'secteur' => trim($_GET['secteur'] ?? ''),
        ];

        $entreprises = $companyModel->searchForAdmin($filters);
        $villes = $companyModel->getDistinctCities();
        $secteurs = $companyModel->getDistinctSectors();

        if ($selectedCompanyId <= 0 && !empty($entreprises)) {
            $selectedCompanyId = (int) $entreprises[0]['id'];
        }

        if ($editCompanyId > 0 && $formMode !== 'update' && empty($formErrors)) {
            $companyToEdit = $companyModel->getById($editCompanyId);
            if (!empty($companyToEdit)) {
                $formMode = 'update';
                $companyForm = [
                    'nom' => $companyToEdit['nom'] ?? '',
                    'description' => $companyToEdit['description'] ?? '',
                    'email' => $companyToEdit['email'] ?? '',
                    'telephone' => $companyToEdit['telephone'] ?? '',
                    'ville' => $companyToEdit['ville'] ?? '',
                    'secteur' => $companyToEdit['secteur'] ?? '',
                ];
            }
        }

        $selectedCompany = $selectedCompanyId > 0 ? $companyModel->findForAdmin($selectedCompanyId) : null;
        $companyOffers = $selectedCompany ? $companyModel->getOffersForAdmin((int) $selectedCompany['id']) : [];
        $selectedAverageNote = '-';

        if ($selectedCompany && $selectedCompany['average_note'] !== null) {
            $selectedAverageNote = number_format((float) $selectedCompany['average_note'], 1, '.', ' ');
        }

        $canDeleteSelected = $selectedCompany
            ? ((int) ($selectedCompany['offer_count'] ?? 0) === 0 && (int) ($selectedCompany['evaluation_count'] ?? 0) === 0)
            : false;

        echo twig_render('admin/entreprises.html.twig', [
            'enterpriseFlash' => $enterpriseFlash,
            'formErrors' => $formErrors,
            'formMode' => $formMode,
            'selectedCompanyId' => $selectedCompanyId,
            'editCompanyId' => $editCompanyId,
            'filters' => $filters,
            'villes' => $villes,
            'secteurs' => $secteurs,
            'entreprises' => $entreprises,
            'companyForm' => $companyForm,
            'selectedCompany' => $selectedCompany,
            'selectedAverageNote' => $selectedAverageNote,
            'companyOffers' => $companyOffers,
            'canDeleteSelected' => $canDeleteSelected,
        ]);
    }
}