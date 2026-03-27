
const select = document.getElementById("enterpriseStatut");
const existing = document.getElementById("existingEnterprise");
const create = document.getElementById("createEnterprise");

select.addEventListener("change", function(){

    existing.style.display = "none";
    create.style.display = "none";

    if(this.value === "hasAccount"){
        
        existing.style.display = "block";
    }

    if(this.value === "hasNoAccount"){
        create.style.display = "block";

    }

});


// Pour l'ajout personnalisé des avantages
function addCustomAvantage() {
    const input = document.getElementById('customAvantage');
    const value = input.value.trim();
    if (!value) return;

    const container = document.getElementById('avantages-container');
    const id = 'custom_' + Date.now();

    const label = document.createElement('label');
    label.classList.add('avantage-tag', 'selected');
    label.innerHTML = `
        <input type="checkbox" name="avantages[]" value="${id}" checked>
        <span>${value}</span>
        <button type="button" class="remove-tag" onclick="this.closest('label').remove()">✕</button>
    `;

    container.appendChild(label);
    input.value = '';
}

// Permettre l'ajout via la touche Entrée
document.getElementById('customAvantage').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addCustomAvantage();
    }
});
