


<div class="auth-card" data-page="inscription">
<h1>Inscription</h1>

<?php if (!empty($erreur)): ?>
<div class="auth-error" role="alert">
    <i class="fas fa-exclamation-triangle"></i>
    <?= $erreur /* already escaped or intentional HTML link */ ?>
</div>
<?php endif; ?>

<form action="" method="POST">
    <fieldset>
        <legend class="sr-only">Formulaire d'inscription</legend>
                    
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($_POST['nom'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div class="form-group">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($_POST['prenom'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div class="form-group">
            <label for="telephone">Téléphone</label>
            <input type="tel" id="telephone" name="telephone" value="<?= htmlspecialchars($_POST['telephone'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password-confirm">Confirmation de mot de passe</label>
            <input type="password" id="password-confirm" name="password_confirm" required>
        </div>

        <div class="form-group">
            <label>Je m'inscris en tant que</label>
            <div class="role-choice">
                <label class="role-option">
                    <input type="radio" name="role" value="etudiant"
                        <?= ($_POST['role'] ?? 'etudiant') === 'etudiant' ? 'checked' : '' ?>>
                    <span><i class="fas fa-graduation-cap"></i> Étudiant</span>
                </label>
                <label class="role-option">
                    <input type="radio" name="role" value="pilote"
                        <?= ($_POST['role'] ?? '') === 'pilote' ? 'checked' : '' ?>>
                    <span><i class="fas fa-chalkboard-teacher"></i> Pilote de promo</span>
                </label>
            </div>
        </div>

        <button type="submit" class="btn-submit">S'Inscrire</button>
    </fieldset>

    <div class="login-section">
        <p>Vous avez déjà un compte ?</p>
        <a href="<?= BASE_URL ?>connexion" class="auth-switch-link" data-direction="to-connexion">Connectez-vous</a>
    </div>
</form>
</div><!-- /.auth-card -->
