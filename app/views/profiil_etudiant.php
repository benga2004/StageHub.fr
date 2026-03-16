<?php $title = "Profil - StageHub"; ?>
<?php include 'layout/header.php'; ?>

<div class="profile-banner">
    <h2>Bangaly Kaba</h2>
    <span class="role-badge">Étudiant</span>
</div>

<div class="card">
    <div class="card-title">Informations personnelles</div>

    <div class="info-row">
        <div>
            <span class="label">Email</span>
            <span>bangalykaba0810@gmail.com</span>
        </div>
    </div>
    <div class="info-row">
        <div>
            <span class="label">Ville</span>
            <span>44600 Saint-Nazaire</span>
        </div>
    </div>
    <div class="info-row">
        <div>
            <span class="label">École</span>
            <span>CESI École d'ingénieur</span>
        </div>
    </div>
    <div class="info-row">
        <div>
            <span class="label">Disponibilité</span>
            <span>Septembre 2025</span>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-title">Compétences</div>
    <div class="skills-wrap">
        <span class="skill-tag">Python</span>
        <span class="skill-tag">C / C++</span>
        <span class="skill-tag">HTML / CSS</span>
        <span class="skill-tag">PHP</span>
        <span class="skill-tag">SQL</span>
        <span class="skill-tag">Arduino</span>
        <span class="skill-tag">Cisco Packet Tracer</span>
    </div>
</div>

<button class="btn-edit">Modifier le profil</button>
<a class="btn-logout" href="connexion.php">Se déconnecter</a>

<?php include 'layout/footer.php'; ?>