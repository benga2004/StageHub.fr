<?php
class Offer {
    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function getAll(int $limit, int $offset): array {
        $stmt = $this->db->prepare('SELECT * FROM offres LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(): int {
        return (int) $this->db->query('SELECT COUNT(*) FROM offres')->fetchColumn();
    }

    # @return array|bool
    
    public function getById(int $id): array { 
        $stmt = $this->db->prepare('SELECT * FROM offres WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool {
        $stmt = $this->db->prepare('
            INSERT INTO offres (titre, description, entreprise_id, remuneration, avantage, date_offre)
            VALUES (:titre, :description, :entreprise_id, :remuneration, :avantage, NOW())
        ');
        return $stmt->execute([
            ':titre'         => $data['jobTitle'],
            ':description'   => $data['description'],
            ':entreprise_id' => $data['entreprise_id'],
            ':remuneration'  => $data['minSalary'],
            ':avantage'      => implode(',', $data['avantages'] ?? []),
        ]);
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare('
            UPDATE offres SET titre=:titre, description=:description WHERE id=:id
        ');
        return $stmt->execute([':titre' => $data['titre'], ':description' => $data['description'], ':id' => $id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM offres WHERE id = :id');
        return $stmt->execute([':id' => $id]);
    }

    public function search(string $query, string $ville, string $domaine, int $limit, int $offset): array {
        $sql = 'SELECT * FROM offres WHERE 1=1';
        $params = [];
        if ($query) { $sql .= ' AND (titre LIKE :q OR description LIKE :q)'; $params[':q'] = "%$query%"; }
        if ($ville)  { $sql .= ' AND ville LIKE :v';   $params[':v'] = "%$ville%"; }
        if ($domaine){ $sql .= ' AND domaine = :d';    $params[':d'] = $domaine; }
        $sql .= ' LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) $stmt->bindValue($k, $v);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}