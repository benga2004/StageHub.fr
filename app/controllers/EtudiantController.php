<?php
class EtudiantController {

    public function profil(): void {
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'etudiant') {
            header('Location: ' . BASE_URL . 'connexion');
            exit;
        }

        $userId  = (int)$_SESSION['user_id'];
        $user    = (new User())->findById($userId);
        $candidatures   = (new Candidature())->getByEtudiant($userId);
        $wishlistOffres = (new Wishlist())->getOffresByEtudiant($userId);

        echo twig_render('Profil_etudiant.html.twig', [
            'user'           => $user,
            'candidatures'   => $candidatures,
            'wishlistOffres' => $wishlistOffres,
        ]);
    }
}
