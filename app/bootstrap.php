<?php
session_start();
define('BASE_PATH', dirname(__DIR__));
define('BASE_URL', '/');

// Twig
require_once BASE_PATH . '/vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader(BASE_PATH . '/app/views');
$twig   = new \Twig\Environment($loader, ['autoescape' => 'html']);
$GLOBALS['twig'] = $twig;

// Filtre nl2br (échappe le HTML puis ajoute les <br>)
$twig->addFilter(new \Twig\TwigFilter('nl2br', function (string $str): string {
    return nl2br(htmlspecialchars($str, ENT_QUOTES, 'UTF-8'));
}, ['is_safe' => ['html']]));

/**
 * Rend un template Twig en injectant les variables globales (base_url, session, flash).
 */
function twig_render(string $template, array $vars = []): string {
    $flash = null;
    if (!empty($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
    }
    return $GLOBALS['twig']->render($template, array_merge([
        'base_url' => BASE_URL,
        'session'  => $_SESSION,
        'flash'    => $flash,
    ], $vars));
}

// Modèles
require_once BASE_PATH . '/app/models/Database.php';
require_once BASE_PATH . '/app/models/Offer.php';
require_once BASE_PATH . '/app/models/User.php';
require_once BASE_PATH . '/app/models/Company.php';
require_once BASE_PATH . '/app/models/Candidature.php';
require_once BASE_PATH . '/app/models/Wishlist.php';

// Contrôleurs
require_once BASE_PATH . '/app/controllers/AuthController.php';
require_once BASE_PATH . '/app/controllers/OfferController.php';
require_once BASE_PATH . '/app/controllers/AdminController.php';
require_once BASE_PATH . '/app/controllers/CandidatureController.php';
require_once BASE_PATH . '/app/controllers/EtudiantController.php';
require_once BASE_PATH . '/app/controllers/EntrepriseController.php';
require_once BASE_PATH . '/app/controllers/WishlistController.php';
require_once BASE_PATH . '/app/controllers/PiloteController.php';

// hashage CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}