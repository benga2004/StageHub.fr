<?php

$title = "Détails de l'offre - StageHub.fr";
$content = "Découvrez les missions, le profil recherché et les conditions de l'offre de stage. Postulez dès maintenant pour rejoindre une entreprise dynamique et enrichir votre expérience professionnelle.";
require __DIR__ . '/../layout/header.php';

?>
<main class="offer-detail container">
    <a class="back-link" href="<?= BASE_URL ?>offres">← Retour aux offres</a>

    <section class="offer-hero">
        <div>
            <h1><?= htmlspecialchars($offre['titre'], ENT_QUOTES, 'UTF-8') ?></h1>
            <h2><?= htmlspecialchars($company['nom'], ENT_QUOTES, 'UTF-8') ?></h2>
            <p class="meta-date">Publié le <?= htmlspecialchars($offre['date_offre'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <a class="cta-btn" href="<?= BASE_URL ?>candidature?id=<?= $id ?>">Postuler</a>
    </section>

    <section class="offer-meta">
        <div>
            <span>Ville</span>
            <strong><?= htmlspecialchars($offre['ville'], ENT_QUOTES, 'UTF-8') ?></strong>
        </div>
        <div>
            <span>Durée</span>
            <strong><?= htmlspecialchars($offre['duree'], ENT_QUOTES, 'UTF-8') ?></strong>
        </div>
        <div>
            <span>Domaine</span>
            <strong><?= htmlspecialchars($offre['domaine'], ENT_QUOTES, 'UTF-8') ?></strong>
        </div>
        <div>
            <span>Rémunération</span>
            <strong><?= htmlspecialchars($offre['remuneration'], ENT_QUOTES, 'UTF-8') ?>€ par <?= htmlspecialchars($offre['r_period'], ENT_QUOTES, 'UTF-8') ?></strong>
        </div>
    </section>

    <section class="offer-section">
        <h3>Description</h3>
        <p><?= nl2br($offre['description']) ?></p>
    </section>

    <?php if (!empty($offre['avantages'])) : ?>
        <?php
        $avantages = explode(',', $offre['avantages'] ?? '');
        
        $avantagesLabels = [
            "transport"     => "Prise en charge du transport quotidien",
            "interessement" => "Intéressement et participation",
            "cantine"       => "Accès à une cantine ou tickets restaurant",
            "rtt"           => "RTT",
            "flextime"      => "Flextime",
            "vehicule"      => "Véhicule de fonction",
        ];
        
        if (!empty(trim($offre['avantages'] ?? ''))) : ?>
            <section class="offer-section">
                <h3>Avantages</h3>
                <div class="avantages">
                    <?php foreach ($avantages as $avantage): 
                        $key = trim($avantage);
                        $label = $avantagesLabels[$key] ?? $key; // personnalisé ou fallback
                    ?>
                        <span class="badge"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endif; ?>

    <section class="offer-actions">
        <a class="cta-btn cta-btn--wide" href="<?= BASE_URL ?>candidature?id=<?= $id ?>">Postuler maintenant</a>
    </section>
</main>

<?php require __DIR__ . '/../layout/footer.php'; ?>