<?php
class Candidature { 

    private PDO $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function count(): int {
        return (int) $this->db->query('SELECT COUNT(*) FROM candidatures')->fetchColumn();
    }

    public static function postuler(int $offreId, int $etudiantId, string $cvPath, string $lettre): bool {
        $db   = Database::connect();
        $stmt = $db->prepare(
            "INSERT INTO candidatures (offre_id, etudiant_id, cv_path, lettre_motivation) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$offreId, $etudiantId, $cvPath, $lettre]);
    }

    public static function alreadyApplied(int $offreId, int $etudiantId): bool {
        $db   = Database::connect();
        $stmt = $db->prepare(
            "SELECT COUNT(*) FROM candidatures WHERE offre_id = ? AND etudiant_id = ?"
        );
        $stmt->execute([$offreId, $etudiantId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    /** Récupère toutes les candidatures d'un étudiant avec le titre de l'offre et le nom de l'entreprise */
    public function getByEtudiant(int $etudiantId): array {
        $stmt = $this->db->prepare('
            SELECT c.*, o.titre AS offre_titre, ent.nom AS entreprise_nom
            FROM candidatures c
            JOIN offres o   ON o.id   = c.offre_id
            JOIN entreprises ent ON ent.id = o.entreprise_id
            WHERE c.etudiant_id = :id
            ORDER BY c.created_at DESC
        ');
        $stmt->execute([':id' => $etudiantId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

