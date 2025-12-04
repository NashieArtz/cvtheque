// document.addEventListener("DOMContentLoaded", function() {
//     // On sélectionne tous les champs de filtre
//     const searchInput = document.querySelector('input[name="search"]');
//     const cityInput = document.querySelector('input[name="filter_city"]');
//     const skillInput = document.querySelector('input[name="filter_skill"]');
//     const licenseSelect = document.querySelector('select[name="filter_license"]');
//     const statusSelect = document.querySelector('select[name="filter_status"]');
//
//     // Où on injecte résultats
//     const resultsContainer = document.getElementById('results-container');
//
//     // Check de la vue: Admin ou publique
//     const viewMode = resultsContainer.getAttribute('data-view-mode') || 'public';
//
//     function fetchResults() {
//         // Construction de l'URL avec les paramètres
//         const params = new URLSearchParams();
//         params.append('view_mode', viewMode);
//
//         if(searchInput) params.append('search', searchInput.value);
//         if(cityInput) params.append('filter_city', cityInput.value);
//         if(skillInput) params.append('filter_skill', skillInput.value);
//         if(licenseSelect) params.append('filter_license', licenseSelect.value);
//         if(statusSelect) params.append('filter_status', statusSelect.value);
//
//         // Appel AJAX
//         fetch(`api/search_ajax.php?${params.toString()}`)
//             .then(response => response.text())
//             .then(html => {
//                 resultsContainer.innerHTML = html;
//             })
//             .catch(error => console.error('Erreur AJAX:', error));
//     }
//
//     // On attache l'événement à tous les inputs
//     const inputs = [searchInput, cityInput, skillInput, licenseSelect, statusSelect];
//     inputs.forEach(input => {
//         if (input) {
//             input.addEventListener('input', fetchResults); // Pour les champs texte
//             input.addEventListener('change', fetchResults); // Pour les select
//         }
//     });
// });