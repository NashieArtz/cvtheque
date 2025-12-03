<nav class="navbar navbar-expand-lg navbar-admin">
    <div class="container-fluid">
        <a class="navbar-brand text-white fs-3 logo" href="?page=dashboard">
            Cvthèque
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVisiteur"
                aria-controls="navbarVisiteur" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarVisiteur">
            <ul class="navbar-nav ms-auto align-items-center gap-4">
                <li class="nav-item">
                    <a class="nav-link nav-link-admin fs-5" href="?page=dashboard">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-admin fs-5" href="?page=profiles-list">Voir CVs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-link-admin fs-5" href="?page=admin-dashboard">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="btn px-4 py-2 fs-5 btn-admin-secondary" href="?page=logout">
                        Se déconnecter
                    </a>
                </li>
                <li class="nav-item theme-position">
                    <img class="moon-sun-icon" src="./assets/img/moon.png" alt="Logo dark ou light mode" height="20px" width="20px">
                    <input type="checkbox" id="toggle" data-theme-toggle class="theme-toggle-input">
                    <label for="toggle" class="theme-switch">
                        <div class="theme-circle"></div>
                    </label>
                </li>
            </ul>
        </div>
    </div>
</nav>