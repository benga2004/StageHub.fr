<?php $title = "Ajouter une offre - StageHub"; ?>
<?php include '../layout/header.php'; ?>

<h1>Nouvelle offre de stage</h1>

<form action="#" method="POST">

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
            <input type="text" id="enterpriseNameSearch" name="enterpriseNameSearch" placeholder="Ex: Airbus" require>
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
            <input type="text" id="description" name="description">
        </div>

        <div class="form-group">
            <label for="email">Email de l'entreprise *</label>
            <input type="email" id="email" name="email">
        </div>

        <div class="form-group">
            <label for="telephone">Téléphone *</label>
            <input type="tel" id="telephone" name="telephone">
        </div>

        <button type="submit" class="btn-submit">Continuer</button>
    </fieldset>
</div>

</form>

<?php include '../layout/footer.php'; ?>