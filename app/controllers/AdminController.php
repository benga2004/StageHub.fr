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

    private function setPilotFlash(string $type, string $message): void {
        $_SESSION['admin_pilotes_flash'] = [
            'type' => $type,
            'message' => $message,
        ];
    }

    private function consumePilotFlash(): ?array {
        $flash = $_SESSION['admin_pilotes_flash'] ?? null;
        unset($_SESSION['admin_pilotes_flash']);
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

    private function getPilotFormDataFromPost(): array {
        return [
            'nom' => trim($_POST['nom'] ?? ''),
            'prenom' => trim($_POST['prenom'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => trim($_POST['password'] ?? ''),
        ];
    }

    private function validatePilotFormData(array $data, User $userModel, ?int $pilotId = null, bool $requirePassword = false): array {
        $errors = [];

        if ($data['prenom'] === '') {
            $errors[] = 'Le prenom est obligatoire.';
        }

        if ($data['nom'] === '') {
            $errors[] = 'Le nom est obligatoire.';
        }

        if ($data['email'] === '') {
            $errors[] = 'L email est obligatoire.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'L email n est pas valide.';
        }

        if ($requirePassword && $data['password'] === '') {
            $errors[] = 'Le mot de passe est obligatoire.';
        }

        if ($data['password'] !== '' && strlen($data['password']) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caracteres.';
        }

        if ($data['email'] !== '') {
            $existingUser = $userModel->findByEmail($data['email']);
            if (!empty($existingUser) && (int) $existingUser['id'] !== (int) $pilotId) {
                $errors[] = 'Cette adresse email est deja utilisee.';
            }
        }

        return $errors;
    }

    private function getPilotById(User $userModel, int $pilotId): array {
        $pilot = $pilotId > 0 ? $userModel->findById($pilotId) : [];
        if (empty($pilot) || ($pilot['role'] ?? '') !== 'pilote') {
            return [];
        }

        return $pilot;
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
                'description' => 'Gerer les comptes pilotes et consulter leurs acces.',
                'count' => $nbPilotes,
                'href' => BASE_URL . 'admin/pilotes',
                'status' => 'Disponible',
                'icon' => 'bi bi-person-badge-fill',
                'is_active' => true,
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

    public function pilotes(): void {
        $this->guard();

        $userModel = new User();
        $allowedActions = ['liste', 'voir', 'creer', 'modifier', 'supprimer', 'supprimer_ok'];
        $action = (string) ($_POST['action'] ?? $_GET['action'] ?? 'liste');
        if (!in_array($action, $allowedActions, true)) {
            $action = 'liste';
        }

        $search = trim((string) ($_GET['q'] ?? ''));
        $pilotId = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        $pilotFlash = $this->consumePilotFlash();
        $formErrors = [];
        $pilotForm = [
            'nom' => '',
            'prenom' => '',
            'email' => '',
            'password' => '',
        ];
        $pilot = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->isValidCsrf()) {
                $formErrors[] = 'Le token de securite est invalide. Recharge la page et recommence.';
                if ($action === 'supprimer_ok') {
                    $action = 'supprimer';
                }
            } elseif ($action === 'creer') {
                $pilotForm = $this->getPilotFormDataFromPost();
                $formErrors = $this->validatePilotFormData($pilotForm, $userModel, null, true);

                if (empty($formErrors)) {
                    $created = $userModel->create([
                        'nom' => $pilotForm['nom'],
                        'prenom' => $pilotForm['prenom'],
                        'email' => $pilotForm['email'],
                        'password' => $pilotForm['password'],
                        'role' => 'pilote',
                    ]);

                    if ($created) {
                        $newPilot = $userModel->findByEmail($pilotForm['email']);
                        $this->setPilotFlash('success', 'Pilote cree avec succes.');
                        $this->redirectToRoute('admin/pilotes', ['action' => 'voir', 'id' => (int) ($newPilot['id'] ?? 0)]);
                    }

                    $formErrors[] = 'Impossible de creer ce pilote.';
                }
            } elseif ($action === 'modifier') {
                $pilot = $this->getPilotById($userModel, $pilotId);
                $pilotForm = $this->getPilotFormDataFromPost();

                if (empty($pilot)) {
                    $formErrors[] = 'Pilote introuvable.';
                } else {
                    $formErrors = $this->validatePilotFormData($pilotForm, $userModel, $pilotId, false);
                }

                if (empty($formErrors)) {
                    $data = [
                        'nom' => $pilotForm['nom'],
                        'prenom' => $pilotForm['prenom'],
                        'email' => $pilotForm['email'],
                    ];

                    if ($pilotForm['password'] !== '') {
                        $data['password'] = $pilotForm['password'];
                    }

                    if ($userModel->update($pilotId, $data)) {
                        $this->setPilotFlash('success', 'Pilote mis a jour avec succes.');
                        $this->redirectToRoute('admin/pilotes', ['action' => 'voir', 'id' => $pilotId]);
                    }

                    $formErrors[] = 'Impossible de mettre a jour ce pilote.';
                }
            } elseif ($action === 'supprimer_ok') {
                $pilot = $this->getPilotById($userModel, $pilotId);

                if (empty($pilot)) {
                    $this->setPilotFlash('error', 'Pilote introuvable.');
                    $this->redirectToRoute('admin/pilotes');
                }

                if ($userModel->delete($pilotId)) {
                    $this->setPilotFlash('success', 'Pilote supprime avec succes.');
                } else {
                    $this->setPilotFlash('error', 'Impossible de supprimer ce pilote.');
                }

                $this->redirectToRoute('admin/pilotes');
            }
        }

        if (in_array($action, ['voir', 'modifier', 'supprimer'], true)) {
            $pilot = $this->getPilotById($userModel, $pilotId);
            if (empty($pilot)) {
                $this->setPilotFlash('error', 'Pilote introuvable.');
                $this->redirectToRoute('admin/pilotes');
            }

            if ($action === 'modifier' && empty($formErrors)) {
                $pilotForm = [
                    'nom' => $pilot['nom'] ?? '',
                    'prenom' => $pilot['prenom'] ?? '',
                    'email' => $pilot['email'] ?? '',
                    'password' => '',
                ];
            }
        }

        $pilotes = [];
        if ($action === 'liste') {
            $pilotes = $search !== ''
                ? $userModel->searchByRole('pilote', $search)
                : $userModel->getByRole('pilote');
        }

        echo twig_render('admin/pilote.html.twig', [
            'action' => $action,
            'pilot' => $pilot,
            'pilotes' => $pilotes,
            'search' => $search,
            'pilotFlash' => $pilotFlash,
            'formErrors' => $formErrors,
            'pilotForm' => $pilotForm,
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
            $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Le mot de passe doit faire au moins 8 caracteres.'];
        } elseif ((new User())->emailExists($email)) {
            $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Cette adresse email est deja utilisee.'];
        } else {
            (new User())->create([
                'nom'      => $nom,
                'prenom'   => $prenom,
                'email'    => $email,
                'password' => $password,
                'role'     => 'etudiant',
            ]);
            $_SESSION['flash_etudiants'] = ['type' => 'success', 'message' => 'Etudiant cree avec succes.'];
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

        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Identifiant invalide.'];
        } else {
            $user = (new User())->findById($id);
            if (empty($user) || $user['role'] !== 'etudiant') {
                $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Etudiant introuvable.'];
            } else {
                (new User())->delete($id);
                $_SESSION['flash_etudiants'] = ['type' => 'success', 'message' => 'Etudiant supprime avec succes.'];
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

        $id       = (int) ($_POST['id'] ?? 0);
        $nom      = trim($_POST['nom'] ?? '');
        $prenom   = trim($_POST['prenom'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($id <= 0) {
            $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Identifiant invalide.'];
        } else {
            $user = (new User())->findById($id);
            if (empty($user) || $user['role'] !== 'etudiant') {
                $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Etudiant introuvable.'];
            } elseif (!$nom || !$prenom || !$email) {
                $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Nom, prenom et email sont obligatoires.'];
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Adresse email invalide.'];
            } elseif ($email !== $user['email'] && (new User())->emailExists($email)) {
                $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Cette adresse email est deja utilisee.'];
            } elseif ($password && strlen($password) < 8) {
                $_SESSION['flash_etudiants'] = ['type' => 'error', 'message' => 'Le mot de passe doit faire au moins 8 caracteres.'];
            } else {
                $data = ['nom' => $nom, 'prenom' => $prenom, 'email' => $email];
                if ($password) {
                    $data['password'] = $password;
                }
                (new User())->update($id, $data);
                $_SESSION['flash_etudiants'] = ['type' => 'success', 'message' => 'Etudiant modifie avec succes.'];
            }
        }

        header('Location: ' . BASE_URL . 'admin/etudiants');
        exit;
    }
}