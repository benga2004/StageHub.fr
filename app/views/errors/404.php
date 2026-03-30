<?php 

$title = "Offre introuvable - StageHub.fr";
$content = "L'offre de stage que vous recherchez n'existe pas ou a été supprimée. Découvrez nos autres offres de stage et trouvez celle qui correspond à votre profil.";
require __DIR__ . '/../layout/header.php';

?>
    <style>
        .error-404 { text-align: center; padding: 80px 20px; color: #2b2d42; }
        .error-404 h1 { font-size: 3rem; margin-bottom: 0.5rem; }
        .error-404 .emoji { font-size: 5rem; display: inline-block; animation: bounce 1.6s ease-in-out infinite; margin-bottom: 1rem; }
        .error-404 .emoji-alt { font-size: 2rem; display: inline-block; animation: shake 1.8s ease-in-out infinite; margin-left: 0.5rem; }
        .error-404 p { font-size: 1.15rem; margin: 0.6rem auto 1.2rem; max-width: 520px; }
        .error-404 a.cta { display: inline-block; margin-top: 1rem; padding: 0.8rem 1.3rem; color: #fff; background: #ef233c; border-radius: 0.45rem; text-decoration: none; font-weight: 700; transition: transform 0.2s ease, background 0.2s ease; }
        .error-404 a.cta:hover { background: #d90429; transform: translateY(-2px); }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-14px); } }
        @keyframes shake { 0%, 100% { transform: rotate(0deg); } 15%, 45%, 75% { transform: rotate(10deg); } 30%, 60%, 90% { transform: rotate(-10deg); } }
    </style>

    <section class="error-404">
        <div class="emoji" role="img" aria-label="Lunettes de recherche">🔎</div>
        <h1>404 - Page introuvable</h1>
        <div class="emoji-alt" role="img" aria-label="Désolé">😕</div>

        <p>Nous n'avons pas trouvé le contenu demandé. La page a peut-être été déplacée ou supprimée. Pas de panique, nous avons plein d'offres de stage à vous proposer.</p>
        <p>Conseils :</p>
        <ul style="list-style: none; padding: 0; line-height: 1.5; max-width: 500px; margin: auto;">
            <li>• Vérifiez l'URL pour éviter une faute de frappe.</li>
            <li>• Retournez à la page d'accueil des offres.</li>
            <li>• Utilisez le menu pour découvrir d'autres opportunités.</li>
        </ul>

        <a class="cta" href="<?= BASE_URL ?>offres">Découvrir les offres de stage</a>
    </section>

<?php require __DIR__ . '/../layout/footer.php'; ?>