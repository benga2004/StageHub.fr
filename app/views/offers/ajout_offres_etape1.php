<?php $title = "Ajouter une offre - StageHub"; ?>
<?php require __DIR__ . '/../layout/header.php'; ?>

<h1>Informations de base</h1>

<form action="<?= BASE_URL ?>offres/ajouter/etape1" method="POST">

    <fieldset>
        <legend>Informations du poste</legend>

        <div class="form-group">
            <label for="jobTitle">Intitulé du poste *</label>
            <input type="text" id="jobTitle" name="jobTitle" required>
        </div>

        <div class="form-group">
            <label for="domaine">Domaine *</label>
            <select id="domaine" name="domaine" required>
                <option value="">-- Choisir --</option>
                <option value="informatique">Informatique</option>
                <option value="marketing">Marketing</option>
                <option value="industrie">Industrie</option>
                <option value="design">Design</option>
                <option value="finance">Finance</option>
            </select>
        </div>

        <div class="form-group">
            <label for="jobLocation">Lieu du poste *</label>
            <input type="text" id="jobLocation" name="location" placeholder="Nom de rue ou code postal" required>
        </div>

    </fieldset>

    <fieldset>
        <legend>Objectif de recrutement</legend>

        <div class="form-group">
            <label for="delai">Délai de recrutement *</label>
            <select id="delai" name="delai" required>
                <option value="">-- Choisir --</option>
                <option value="3">1 à 3 jours</option>
                <option value="7">3 à 7 jours</option>
                <option value="14">1 à 2 semaines</option>
                <option value="28">2 à 4 semaines</option>
                <option value="30">Plus de 4 semaines</option>
            </select>
        </div>

        <div class="form-group">
            <label for="nbOfJobs">Nombre de stagiaires souhaités *</label>
            <input type="number" id="nbOfJobs" name="nbOfJobs" value="1" min="1" required>
        </div>

    </fieldset>
    
    <button type="submit" class="btn-submit" >Continuer</button>

</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>