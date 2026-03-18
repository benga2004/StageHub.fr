<?php $title = "Ajouter une offre - StageHub"; ?>
<?php include 'layout/header.php'; ?>

<h1>Informations de base</h1>

<form action="#" method="POST">

    <fieldset>
        <legend>Informations du poste</legend>

        <div class="form-group">
            <label for="jobTitle">Intitulé du poste *</label>
            <input type="text" id="jobTitle" name="jobTitle" required>
        </div>

        <div class="form-group">
            <label for="mobilite">Mobilité du poste *</label>
            <select id="mobilite" name="mobilite" required>
                <option value="">-- Choisir --</option>
                <option value="inPerson">En présentiel</option>
                <option value="fullTelework">Télétravail complet</option>
                <option value="hybridTelework">Travail hybride</option>
                <option value="oftenMoving">Déplacements fréquents</option>
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
                <option value="delai1">1 à 3 jours</option>
                <option value="delai2">3 à 7 jours</option>
                <option value="delai3">1 à 2 semaines</option>
                <option value="delai4">2 à 4 semaines</option>
                <option value="delai5">Plus de 4 semaines</option>
            </select>
        </div>

        <div class="form-group">
            <label for="numberOfJob">Nombre de personnes à recruter (30 prochains jours) *</label>
            <input type="number" id="numberOfJob" name="numberOfJob" value="1" min="1" required>
        </div>

    </fieldset>

    <button type="submit" class="btn-submit" >Continuer</button>

</form>

<?php include 'layout/footer.php'; ?>