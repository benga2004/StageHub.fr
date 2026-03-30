<?php $title = "Ajouter une offre - StageHub"; ?>
<?php require __DIR__ . '/../layout/header.php'; ?>

<h1>Détails du stage</h1>

<form action="<?= BASE_URL ?>offres/ajouter/etape2" method="POST" onsubmit="document.getElementById('jobDescriptionHidden').value = document.getElementById('jobDescription').innerHTML;">

    <fieldset>
        <legend>Informations du poste</legend>

        <!-- DESCRIPTION - première position -->
        <div class="form-group">
            <label for="jobDescription">Description du poste *</label>
            <small>Vous pouvez modifier ou remplacer cette description.</small>
            <div class="editor-toolbar">
                <button type="button" onclick="document.execCommand('bold')" title="Gras"><strong>B</strong></button>
                <button type="button" onclick="document.execCommand('italic')" title="Italique"><em>I</em></button>
                <button type="button" onclick="document.execCommand('insertUnorderedList')" title="Liste">&#8801;</button>
                <button type="button" onclick="document.getElementById('jobDescription').innerHTML=''" title="Effacer">🗑</button>
            </div>
            <div
                id="jobDescription"
                name="jobDescription"
                contenteditable="true"
                class="editor-content"
                role="textbox"
                aria-multiline="true"
                aria-label="Description du poste"
            ><p><strong>À propos du poste</strong></p>
            <p>Décrivez ici le contexte général du stage.</p>

            <p><strong>Missions</strong></p>
            <ul>
                <li>Mission principale 1</li>
                <li>Mission principale 2</li>
                <li>Mission principale 3</li>
            </ul>

            <p><strong>Profil recherché</strong></p>
            <ul>
                <li>Formation en cours (ex: Bac+3 en informatique)</li>
                <li>Compétences requises</li>
                <li>Qualités attendues</li>
            </ul></div>
            <!-- Champ caché pour envoyer le contenu via POST -->
            <input type="hidden" name="jobDescription" id="jobDescriptionHidden">
        </div>

        <div class="form-group">
            <label for="durationNumber">Durée du contrat *</label>
            <div class="field-row">
                <input type="number" id="durationNumber" name="durationNumber" value="2" min="1" required>
                <select id="durationPeriod" name="durationPeriod">
                    <option value="Mois">Mois</option>
                    <option value="Semaine(s)">Semaine(s)</option>
                    <option value="Jour(s)">Jour(s)</option>
                </select>
            </div>
        </div>

    </fieldset>

    <fieldset>
        <legend>Salaire et avantages</legend>

        <div class="form-group">
            <label for="minSalary">Salaire minimum (€)</label>
            <input type="number" id="minSalary" name="minSalary" value="0" min="0">
        </div>

        <div class="form-group">
            <label for="frequence">Fréquence</label>
            <select id="frequence" name="frequence">
                <option value="Mois">Par mois</option>
                <option value="Semaine">Par semaine</option>
                <option value="Jour">Par jour</option>
                <option value="Heure">Par heure</option>
            </select>
        </div>

    </fieldset>

    <!-- AVANTAGES - en bas -->
    <fieldset>
        <legend>Avantages</legend>

        <div class="form-group avantages-group" id="avantages-container">
            <?php
            $avantages = [
                "transport"     => "Prise en charge du transport quotidien",
                "interessement" => "Intéressement et participation",
                "cantine"      => "Accès à une cantine ou tickets restaurant",
                "rtt"           => "RTT",
                "flextime"      => "Flextime",
                "vehicule"      => "Véhicule de fonction",
            ];
            foreach ($avantages as $value => $label): ?>
                <label class="avantage-tag">
                    <input type="checkbox" name="avantages[]" value="<?= $value ?>">
                    <span><?= $label ?></span>
                </label>
            <?php endforeach; ?>
        </div>

        <!-- Ajout personnalisé -->
        <div class="form-group avantage-custom-add">
            <input type="text" id="customAvantage" placeholder="Ajouter un avantage personnalisé...">
            <button type="button" onclick="addCustomAvantage()">+ Ajouter</button>
        </div>

    </fieldset>

    <button type="submit" class="btn-submit">Enregistrer l'offre</button>

</form>

<?php require __DIR__ . '/../layout/footer.php'; ?>