<?php 

$title = "Offre introuvable - StageHub.fr";
$content = "L'offre de stage que vous recherchez n'existe pas ou a été supprimée. Découvrez nos autres offres de stage et trouvez celle qui correspond à votre profil.";
require __DIR__ . '/../layout/header.php';

?>
    <h1>Page introuvable</h1>
    <p>Cette page n'a pas été trouvée.</p> 
    <a href="<?= BASE_URL ?>offres">Voir les offres de stage</a>