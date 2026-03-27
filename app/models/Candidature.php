<?php
class Candidature {

    public static function postuler(int $offre_id, string $prenom, string $nom, string $email, string $cvPath, string $lettre): bool {
        $db   = Database::connect();
        $stmt = $db->prepare("
            INSERT INTO candidatures (offre_id, prenom, nom, email, cv_path, lettre)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$offre_id, $prenom, $nom, $email, $cvPath, $lettre]);
    }

}
