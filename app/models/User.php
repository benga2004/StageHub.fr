<?php
class User {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function findByEmail(string $email): array {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email');
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function findById(int $id): array {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function emailExists(string $email): bool {
        $stmt = $this->db->prepare('SELECT 1 FROM users WHERE email = :email LIMIT 1');
        $stmt->execute([':email' => $email]);
        return (bool) $stmt->fetchColumn();
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare('
            INSERT INTO users (nom, prenom, email, password, role)
            VALUES (:nom, :prenom, :email, :password, :role)
        ');
        return $stmt->execute([
            ':nom'      => $data['nom'],
            ':prenom'   => $data['prenom'],
            ':email'    => $data['email'],
            ':password' => password_hash($data['password'], PASSWORD_BCRYPT),
            ':role'     => $data['role'] ?? 'etudiant',
        ]);
    }

    public function getAll(): array {
        return $this->db->query('SELECT id, nom, prenom, email, role, created_at FROM users')->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function update(int $id, array $data): bool {
        $fields = ['nom = :nom', 'prenom = :prenom', 'email = :email'];
        $params = [':nom' => $data['nom'], ':prenom' => $data['prenom'], ':email' => $data['email'], ':id' => $id];

        if (!empty($data['password'])) {
            $fields[] = 'password = :password';
            $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $stmt = $this->db->prepare('UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id');
        return $stmt->execute($params);
    }

    public function updateCv(int $id, string $cvPath): bool {
        $stmt = $this->db->prepare('UPDATE users SET cv_path = :cv WHERE id = :id');
        return $stmt->execute([':cv' => $cvPath, ':id' => $id]);
    }

    public function getByRole(string $role): array {
        $stmt = $this->db->prepare('SELECT id, nom, prenom, email, role, created_at FROM users WHERE role = :role ORDER BY nom, prenom');
        $stmt->execute([':role' => $role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchByRole(string $role, string $search): array {
        $stmt = $this->db->prepare('
            SELECT id, nom, prenom, email, role, created_at FROM users
            WHERE role = :role
              AND (nom LIKE :s OR prenom LIKE :s OR email LIKE :s)
            ORDER BY nom, prenom
        ');
        $stmt->execute([':role' => $role, ':s' => '%' . $search . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}