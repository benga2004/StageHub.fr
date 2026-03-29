
// ============================================
// Auth — flip transition connexion ↔ inscription
// ============================================
(function () {
    var card = document.querySelector('.auth-card');
    if (!card) return;

    // Appliquer l'animation d'entrée si on arrive depuis la page sœur
    var incoming = sessionStorage.getItem('authFlipIncoming');
    if (incoming) {
        sessionStorage.removeItem('authFlipIncoming');
        card.classList.add(incoming);
    }

    // Intercepter les clics sur les liens de bascule
    document.querySelectorAll('.auth-switch-link').forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            var href      = this.href;
            var direction = this.dataset.direction; // 'to-inscription' ou 'to-connexion'

            // Classe de sortie + classe d'entrée à stocker
            var outClass, inClass;
            if (direction === 'to-inscription') {
                outClass = 'auth-flip-out-left';
                inClass  = 'auth-flip-in-right';
            } else {
                outClass = 'auth-flip-out-right';
                inClass  = 'auth-flip-in-left';
            }

            sessionStorage.setItem('authFlipIncoming', inClass);
            card.classList.add(outClass);

            // Naviguer après la fin de l'animation de sortie (~350ms)
            setTimeout(function () {
                window.location.href = href;
            }, 340);
        });
    });
}());

// ============================================
// Sélectionneur entreprise
// ============================================
const select = document.getElementById("enterpriseStatut");
if (select) {
    const existing = document.getElementById("existingEnterprise");
    const create   = document.getElementById("createEnterprise");

    select.addEventListener("change", function () {
        existing.style.display = "none";
        create.style.display   = "none";

        if (this.value === "hasAccount")   existing.style.display = "block";
        if (this.value === "hasNoAccount") create.style.display   = "block";
    });
}

// ============================================
// Ajout personnalisé des avantages
// ============================================
function addCustomAvantage() {
    const input = document.getElementById('customAvantage');
    const value = input.value.trim();
    if (!value) return;

    const container = document.getElementById('avantages-container');
    const id        = 'custom_' + Date.now();
    const label     = document.createElement('label');

    label.classList.add('avantage-tag', 'selected');
    label.innerHTML = `
        <input type="checkbox" name="avantages[]" value="${id}" checked>
        <span>${value}</span>
        <button type="button" class="remove-tag" onclick="this.closest('label').remove()">✕</button>
    `;
    container.appendChild(label);
    input.value = '';
}

const customAvantageInput = document.getElementById('customAvantage');
if (customAvantageInput) {
    customAvantageInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') { e.preventDefault(); addCustomAvantage(); }
    });
}

// Synchronise le contenu de l'éditeur vers le champ caché avant envoi
// (uniquement sur la page qui possède l'éditeur riche)
const jobDescEditor = document.getElementById('jobDescription');
if (jobDescEditor) {
    const editorForm = jobDescEditor.closest('form');
    if (editorForm) {
        editorForm.addEventListener('submit', function () {
            document.getElementById('jobDescriptionHidden').value = jobDescEditor.innerHTML;
        });
    }
}
// ============================================
// Wishlist — toggle bookmark par fetch
// ============================================
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.wishlist-btn');
    if (!btn) return;
    e.preventDefault();
    e.stopPropagation();

    const offreId = btn.dataset.offre;
    const fd      = new FormData();
    fd.append('offre_id', offreId);

    const url = (window.BASE_URL || '/') + 'wishlist/toggle';

    fetch(url, { method: 'POST', body: fd })
        .then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        })
        .then(function (data) {
            if (data.error === 'not_logged_in') {
                window.location.href = (window.BASE_URL || '/') + 'connexion';
                return;
            }
            const inWL = data.in_wishlist;
            btn.classList.toggle('active', inWL);
            btn.title = inWL ? 'Retirer de la wishlist' : 'Ajouter \u00e0 la wishlist';
            btn.querySelector('i').className = inWL ? 'fas fa-bookmark' : 'far fa-bookmark';
        })
        .catch(function (err) { console.error('Wishlist error:', err); });
});
