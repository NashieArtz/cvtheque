<nav class="navbar navbar-expand-lg" style="background:#5a3f78;">
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
                    <a class="nav-link text-white fs-5" href="?page=dashboard">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fs-5" href="?page=profiles-list">Voir CVs</a>
                </li>
                <li class="nav-item">
                    <a class="btn px-4 py-2 fs-5 btn-custom-primary"
                       href="?page=register">
                        S'inscrire
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn px-4 py-2 fs-5 btn-custom-secondary" href="?page=login">
                        Se connecter
                    </a>
                </li>
                <li class="nav-item">
                    <input type="checkbox" id="toggle" data-theme-toggler>
                    <label id="switch" for="toggle">
                        <div id="circle"></div>
                    </label>
            </ul>
        </div>
    </div>
</nav>
<?php
