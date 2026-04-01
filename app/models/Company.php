<?php
class Company {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function count(): int {
        return (int) $this->db->query('SELECT COUNT(*) FROM entreprises')->fetchColumn();
    }

    public function getAll(): array {
        return $this->db->query('SELECT * FROM entreprises ORDER BY nom ASC')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array {
        $stmt = $this->db->prepare('SELECT * FROM entreprises WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function search(string $query): array {
        $stmt = $this->db->prepare('SELECT * FROM entreprises WHERE nom LIKE :query ORDER BY nom ASC');
        $stmt->execute([':query' => '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByName(string $name): ?array {
        $stmt = $this->db->prepare('SELECT * FROM entreprises WHERE LOWER(nom) = LOWER(:nom)');
        $stmt->execute([':nom' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO entreprises (nom, description, email, telephone, ville, secteur)
                VALUES (:nom, :description, :email, :telephone, :ville, :secteur)
            ');
            return $stmt->execute($data);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return false;
            }
            throw $e;
        }
    }

    public function update(int $id, array $data): bool {
        try {
            $stmt = $this->db->prepare('
                UPDATE entreprises
                SET nom = :nom,
                    description = :description,
                    email = :email,
                    telephone = :telephone,
                    ville = :ville,
                    secteur = :secteur
                WHERE id = :id
            ');
            $data[':id'] = $id;
            return $stmt->execute($data);
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                return false;
            }
            throw $e;
        }
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM entreprises WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function searchForAdmin(array $filters = []): array {
        $sql = 'SELECT ' . $this->adminSelect() . ' FROM entreprises e WHERE 1=1';
        $params = [];

        $query = trim($filters['q'] ?? '');
        $ville = trim($filters['ville'] ?? '');
        $secteur = trim($filters['secteur'] ?? '');

        if ($query !== '') {
            $sql .= " AND (
                e.nom LIKE :query_nom
                OR COALESCE(e.description, '') LIKE :query_description
                OR COALESCE(e.email, '') LIKE :query_email
                OR COALESCE(e.telephone, '') LIKE :query_telephone
                OR COALESCE(e.ville, '') LIKE :query_ville
                OR COALESCE(e.secteur, '') LIKE :query_secteur
            )";
            $searchValue = '%' . $query . '%';
            $params[':query_nom'] = $searchValue;
            $params[':query_description'] = $searchValue;
            $params[':query_email'] = $searchValue;
            $params[':query_telephone'] = $searchValue;
            $params[':query_ville'] = $searchValue;
            $params[':query_secteur'] = $searchValue;
        }

        if ($ville !== '') {
            $sql .= " AND COALESCE(e.ville, '') = :ville";
            $params[':ville'] = $ville;
        }

        if ($secteur !== '') {
            $sql .= " AND COALESCE(e.secteur, '') = :secteur";
            $params[':secteur'] = $secteur;
        }

        $sql .= ' ORDER BY e.nom ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findForAdmin(int $id): ?array {
        $stmt = $this->db->prepare('SELECT ' . $this->adminSelect() . ' FROM entreprises e WHERE e.id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getOffersForAdmin(int $companyId): array {
        $stmt = $this->db->prepare('
            SELECT id, titre, ville, domaine, date_offre
            FROM offres
            WHERE entreprise_id = :id
            ORDER BY date_offre DESC, id DESC
        ');
        $stmt->execute([':id' => $companyId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countOffers(int $companyId): int {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM offres WHERE entreprise_id = :id');
        $stmt->execute([':id' => $companyId]);
        return (int) $stmt->fetchColumn();
    }

    public function countEvaluations(int $companyId): int {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM evaluations WHERE entreprise_id = :id');
        $stmt->execute([':id' => $companyId]);
        return (int) $stmt->fetchColumn();
    }

    public function getDistinctCities(): array {
        return $this->getDistinctColumnValues('ville');
    }

    public function getDistinctSectors(): array {
        return $this->getDistinctColumnValues('secteur');
    }

    private function getDistinctColumnValues(string $column): array {
        $allowed = ['ville', 'secteur'];
        if (!in_array($column, $allowed, true)) {
            return [];
        }

        $rows = $this->db->query("SELECT DISTINCT $column FROM entreprises WHERE $column IS NOT NULL AND $column <> '' ORDER BY $column ASC")
            ->fetchAll(PDO::FETCH_COLUMN);

        return array_values(array_filter($rows, static function ($value) {
            return $value !== null && $value !== '';
        }));
    }

    private function adminSelect(): string {
        return "
            e.*,
            (
                SELECT COUNT(*)
                FROM offres o
                WHERE o.entreprise_id = e.id
            ) AS offer_count,
            (
                SELECT COUNT(DISTINCT c.etudiant_id)
                FROM offres o
                LEFT JOIN candidatures c ON c.offre_id = o.id
                WHERE o.entreprise_id = e.id
            ) AS applicant_count,
            (
                SELECT ROUND(AVG(ev.note), 1)
                FROM evaluations ev
                WHERE ev.entreprise_id = e.id AND ev.note IS NOT NULL
            ) AS average_note,
            (
                SELECT COUNT(*)
                FROM evaluations ev
                WHERE ev.entreprise_id = e.id
            ) AS evaluation_count
        ";
    }
}