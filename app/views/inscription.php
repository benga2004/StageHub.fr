<?php $title = "Inscription - StageHub"; ?>
<?php include 'layout/header.php'; ?>

<h1>Inscription</h1>

<form action="#" method="POST">
    <fieldset>
        <legend class="sr-only">Formulaire d'inscription</legend>
                    
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" required>
        </div>

        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" required>
        </div>

        <div class="form-group">
            <label for="telephone">Téléphone</label>
            <input type="tel" id="telephone" name="telephone" required>
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password-confirm">Confirmation de mot de passe</label>
            <input type="password" id="password-confirm" name="password-confirm" required>
        </div>

        <button type="submit" class="btn-submit">S'Inscrire</button>
    </fieldset>

    <div class="login-section">
        <p>Vous avez déjà un compte?</p>
        <a href="connexion.php">Connectez-vous</a>
    </div>
</form>

<?php include 'layout/footer.php'; ?>
