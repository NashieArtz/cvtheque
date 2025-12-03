<div class="container py-5">

    <div class="row mb-5 align-items-end">
        <div class="col-md-6">
            <h2 class="fw-bold display-6 mb-3" style="color: var(--guest-main);">Nos Talents</h2>
            <p style="color: var(--text-color);">Explorez les profils de nos étudiants.</p>
        </div>

        <div class="col-md-6">
            <div class="row g-2">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text border-end-0" style="background-color: var(--bg2-color); border-color: var(--guest-secondary); color: var(--text-color);">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" id="search-input" class="form-control form-control-theme border-start-0" placeholder="Rechercher (nom, compétence, ville)...">
                    </div>
                </div>
                <div class="col-md-4">
                    <select id="sort-select" class="form-select form-select-theme">
                        <option value="newest">Plus récents</option>
                        <option value="az">Nom (A-Z)</option>
                        <option value="za">Nom (Z-A)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4" id="profiles-grid">
        <div class="col-12 text-center">
            Chargement des profils...
        </div>
    </div>



    <script src="./assets/js/search-profiles.js"></script>