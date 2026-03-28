<!-- Page d'accueil StageHub_ -->
<section class="home-hero">
  <div class="hero-content">
    <h1>StageHub, votre tremplin vers le monde professionnel</h1>
    <p>Découvrez et postulez aux meilleures offres de stage en quelques clics.
      Des entreprises référencées, des profils qualifiés et un parcours simplifié.
    </p>
    <div class="hero-actions">
      <a class="btn btn-primary" href="<?= BASE_URL ?>offres">Voir les offres</a>
      <a class="btn btn-secondary" href="<?= BASE_URL ?>inscription">Créer un compte</a>
    </div>
  </div>
</section>

<section class="home-features" aria-label="Fonctionnalités de la plateforme">
  <h2>Ce que vous pouvez faire sur StageHub</h2>
  <div class="feature-grid">
    <article class="feature-card">
      <i class="fas fa-search fa-2x" aria-hidden="true"></i>
      <h3>Recherche avancée</h3>
      <p>Filtrez par domaine, ville, durée et entreprise pour trouver le stage qui vous correspond.</p>
    </article>
    <article class="feature-card">
      <i class="fas fa-user-check fa-2x" aria-hidden="true"></i>
      <h3>Postulez vite</h3>
      <p>Postulez directement depuis votre espace, suivez l’état de vos candidatures en temps réel.</p>
    </article>
    <article class="feature-card">
      <i class="fas fa-building fa-2x" aria-hidden="true"></i>
      <h3>Entreprises engagées</h3>
      <p>Plus de 100 entreprises partenaires publient des offres régulièrement.</p>
    </article>
  </div>
</section>

<section class="home-stats" aria-label="Statistiques StageHub">
  <h2>Nos chiffres clés</h2>
  <div class="stats-grid">
    <div class="stat-item">
      <p class="stat-value"><?= @$total ?? '---' ?></p>
      <p class="stat-label">Offres disponibles</p>
    </div>
    <div class="stat-item">
      <p class="stat-value">+200</p>
      <p class="stat-label">Entreprises actives</p>
    </div>
    <div class="stat-item">
      <p class="stat-value">+1000</p>
      <p class="stat-label">Candidatures envoyées</p>
    </div>
  </div>
</section>

<section class="home-cta">
  <h2>Prêt à lancer votre carrière ?</h2>
  <p>Inscrivez-vous, complétez votre profil et trouvez le stage qui vous fera grandir.</p>
  <a class="btn btn-primary" href="<?= BASE_URL ?>inscription">Inscription étudiant</a>
</section>