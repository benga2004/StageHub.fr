<?php $title = "Ajouter une offre - StageHub";
 require __DIR__ . '/../layout/header.php'; ?>

<h1>Nouvelle offre de stage</h1>

<?php $companies = (new Company())->getAll(); ?>

<form action="<?= BASE_URL ?>offres/ajouter" method="POST">

    <fieldset>
        <legend class="sr-only">Statut de l'entreprise</legend>

        <div class="form-group">
            <label for="enterpriseStatut">Offre venant de :</label>

            <select id="enterpriseStatut" name="enterpriseStatut" required>
                <option value="">-- Choisir --</option>
                <option value="hasNoAccount">Nouvelle entreprise</option>
                <option value="hasAccount">Entreprise existante</option>
            </select>
        </div>

    </fieldset>


<div id="existingEnterprise" style="display:none;">
    <fieldset>
        <legend>Rechercher votre entreprise</legend>

        <div class="form-group">
            <label for="enterpriseNameSearch">Nom de l'entreprise *</label>
            <input type="text" id="enterpriseNameSearch" name="enterpriseNameSearch" list="enterpriseList" placeholder="Ex: Airbus" required>
            <datalist id="enterpriseList">
                <?php foreach ($companies as $company): ?>
                    <option value="<?= htmlspecialchars($company['nom']) ?>"></option>
                <?php endforeach; ?>
            </datalist>
        </div>

        <button type="submit" class="btn-submit">Continuer</button>
    </fieldset>
</div>


<div id="createEnterprise" style="display:none;">
    <fieldset>
        <legend>Créer un compte employeur</legend>

        <div class="form-group">
            <label for="enterpriseName">Nom de l'entreprise *</label>
            <input type="text" id="enterpriseName" name="enterpriseName">
        </div>

        <div class="form-group">
            <label for="description">Description de l'entreprise *</label>
            <textarea id="description" name="description" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="email">Email de l'entreprise *</label>
            <input type="email" id="email" name="email">
        </div>

        <div class="form-group">
            <label for="telephone">Téléphone *</label>
            <input type="tel" id="telephone" name="telephone">
        </div>

        <div class="form-group">
            <label for="ville">Ville *</label>
            <input type="text" id="ville" name="ville">
        </div>

        <div class="form-group">
            <label for="secteur">Secteur *</label>
            <input type="text" id="secteur" name="secteur">
        </div>

        <button type="submit" class="btn-submit">Continuer</button>
    </fieldset>
</div>

</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>