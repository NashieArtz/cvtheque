function loadProfiles() {
    // Récupération des valeurs
    var searchStr = document.getElementById("search-input").value;
    var sortStr = document.getElementById("sort-select").value;
    var container = document.getElementById("profiles-grid");

    // Création de l'objet XMLHttpRequest (comme dans ton code original)
    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Mise à jour du conteneur avec la réponse PHP
            container.innerHTML = this.responseText;
            // On peut ajouter une bordure ou un style si nécessaire,
            // mais c'est mieux géré par le CSS des cartes maintenant.
        }
    }

    // Envoi de la requête avec les DEUX paramètres (q et sort)
    // Note: On pointe vers le dossier api/
    xmlhttp.open("GET", "api/search_profiles.php?q=" + encodeURIComponent(searchStr) + "&sort=" + encodeURIComponent(sortStr), true);
    xmlhttp.send();
}

// Écouteurs d'événements pour lancer la recherche automatiquement
document.addEventListener("DOMContentLoaded", function() {
    // Recherche quand on tape (avec un petit délai pour ne pas spammer)
    var timeout = null;
    document.getElementById("search-input").addEventListener("keyup", function() {
        clearTimeout(timeout);
        timeout = setTimeout(loadProfiles, 300);
    });

    // Recherche quand on change le tri
    document.getElementById("sort-select").addEventListener("change", function() {
        loadProfiles();
    });

    // Chargement initial
    loadProfiles();
});