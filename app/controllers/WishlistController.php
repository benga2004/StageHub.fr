<?php
class WishlistController {

    /** POST /wishlist/toggle — retourne du JSON */
    public function toggle(): void {
        // Nettoie tout output accidentel (notices PHP, etc.)
        if (ob_get_level()) ob_end_clean();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        header('Content-Type: application/json; charset=utf-8');

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'not_logged_in']);
            exit;
        }

        $offreId = (int)($_POST['offre_id'] ?? 0);
        if (!$offreId) {
            http_response_code(400);
            echo json_encode(['error' => 'invalid_id']);
            exit;
        }

        $inWishlist = (new Wishlist())->toggle((int)$_SESSION['user_id'], $offreId);
        echo json_encode(['in_wishlist' => $inWishlist]);
        exit;
    }

    /** GET /wishlist — page wishlist de l'étudiant connecté */
    public function index(): void {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'connexion');
            exit;
        }

        $offres  = (new Wishlist())->getOffresByEtudiant((int)$_SESSION['user_id']);

        echo twig_render('wishlist.html.twig', ['offres' => $offres]);
    }
}
