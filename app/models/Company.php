<?php
class Company {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll(): array {
        return $this->db->query('SELECT * FROM entreprises')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): array { #|false
        $stmt = $this->db->prepare('SELECT * FROM entreprises WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function search(string $query): array {
        $stmt = $this->db->prepare('SELECT * FROM entreprises WHERE nom LIKE :query');
        $stmt->execute([':query' => '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByName(string $name): ?array {
        $stmt = $this->db->prepare('SELECT * FROM entreprises WHERE LOWER(nom) = LOWER(:nom)');
        $stmt->execute([':nom' => $name]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare('
            INSERT INTO entreprises (nom, description, email, telephone, ville, secteur)
            VALUES (:nom, :description, :email, :telephone, :ville, :secteur)
        ');
        return $stmt->execute($data);
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare('
            UPDATE entreprises SET nom=:nom, description=:description,
            email=:email, telephone=:telephone, ville=:ville, secteur=:secteur WHERE id=:id
        ');
        $data[':id'] = $id;
        return $stmt->execute($data);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM entreprises WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }
}