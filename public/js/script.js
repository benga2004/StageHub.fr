
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
