<nav class="navbar navbar-expand-lg navbar-guest">
    <div class="container-fluid">
        <a class="navbar-brand text-white fs-3 logo" href="?page=dashboard">
            Cvthèque
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-guest"
                aria-controls="navbar-guest" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar-guest">
            <ul class="navbar-nav ms-auto align-items-center gap-4">
                <li class="nav-item">
                    <a class="nav-link fs-5 nav-link-guest" href="?page=dashboard">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fs-5 nav-link-guest" href="?page=profiles-list">Voir CVs</a>
                </li>
                <li class="nav-item">
                    <a class="btn fs-5 btn-guest-primary" href="?page=register">
                        S'inscrire
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn fs-5 btn-guest-secondary" href="?page=login">
                        Se connecter
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