<?php
include'./config/release.php';
if (isset($_SESSION['logout_message'])) {
    echo '<div id="logout-message" class="alert alert-info">' . $_SESSION['logout_message'] . '</div>';
    unset($_SESSION['logout_message']);
}

?>






<section id="hero">
    <div class="container-fluid py-5 banniere-cv">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="fw-bold display-4">Créez votre CV<br>professionnel en ligne</h1>

                    <?php
                    if (isset($_SESSION)) {
                        if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
                            $role_id = $_SESSION['user']['role_id'] ?? null;

                            if ($role_id == 1) {
                                ?>
                                <a href="?page=profile-edit" class="btn btn-lg mt-4 btn-student-primary">
                                    Commencer mon CV
                                </a>
                                <?php
                            }

                            if ($role_id == 3) {
                                ?>
                                <a href="?page=profile-edit" class="btn btn-lg mt-4 btn-admin-primary">
                                    Commencer mon CV
                                </a>
                                <?php
                            }

                        } else {
                            ?>
                            <a href="?page=register" class="btn btn-lg mt-4 btn-color-primary">
                                Commencer mon CV
                            </a>
                            <?php
                        }
                    }
                    ?>

                </div>
                <div class="col-md-6">
                    <img src="./assets/img/pc_with_cv.jpg" class="img-fluid rounded shadow" alt="Illustration d'un CV en ligne">
                </div>
            </div>
        </div>
    </div>
</section>
<section id="3-great-point">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-4 d-flex align-items-center gap-2">
                <img src="./assets/img/smile.png" alt="Icône simplicité">
                <p class="fw-bold">Facilité d’utilisation</p>
            </div>
            <div class="col-12 col-lg-4 d-flex align-items-center gap-2">
                <img src="./assets/img/share.png" alt="Icône partage">
                <p class="fw-bold">Générez et partagez votre profil</p>
            </div>
            <div class="col-12 col-lg-4 d-flex align-items-center gap-2">
                <img src="./assets/img/people.png" alt="Icône employeur">
                <p class="fw-bold">Visibilité auprès de nombreux employeurs</p>
            </div>
        </div>
    </div>
</section>
<section id="DESC">
    <div class="container py-5">
        <div class="col-md-12 align-items-center gap-2">
            <h2 class="fw-bold">Un profil unique, de multiples opportunités.</h2>
            <p>
                La CVthèque de notre école vous permet de centraliser toutes vos expériences, formations et compétences.
                Grâce à un modèle de CV standardisé, assurez-vous que votre profil est toujours clair, professionnel et
                facilement consultable par nos entreprises partenaires. Mettez votre profil à jour en quelques clics et
                gagnez en visibilité immédiate sur le marché du travail.
            </p>
            <img src="./assets/img/zen.jpg" alt="Image d'illustration des fonctionnalités du CV">
        </div>
        <a href="?page=profile-edit" class="btn btn-color-primary btn-lg mt-4">
            Accéder à mon Édition de CV
        </a>
    </div>
</section>
<section id="conseil">
    <div class="container py-5">
        <h2>Conseils pour optimiser votre profil</h2>
        <p>
            Pour maximiser vos chances de recrutement, assurez-vous que chaque section de votre profil est complète.
            N'hésitez pas à détailler vos projets techniques : c'est la meilleure façon de démontrer vos compétences concrètes.
            Les recruteurs filtrent souvent par niveau de compétence (Débutant, Intermédiaire, Avancé), donc soyez précis et honnête !
        </p>
        <h2>Comment les employeurs trouvent-ils mon CV ?</h2>
        <p>
            Les recruteurs accèdent à la <a href="?page=profiles-list">Liste des Profils</a> et utilisent des filtres par
            promotion et par compétence clé. Une fois votre profil sélectionné, ils peuvent consulter votre fiche détaillée et vous contacter directement
            grâce aux coordonnées que vous avez rendues publiques.
        </p>
    </div>
</section>
<section id="FAQ">
    <div class="container">
        <h2>Questions Fréquentes (FAQ)</h2>
        <div class="accordion">
            <div class="accordion-item">
                <button id="accordion-button-1" aria-expanded="false">
                    <span class="accordion-title">Mon CV est-il visible par tout le monde ?</span>
                    <span class="icon" aria-hidden="true"></span>
                </button>
                <div class="accordion-content">
                    <p>
                        Vous contrôlez la visibilité de votre profil. Dans la page d'édition (<a href="?page=profile-edit">Mon Profil</a>),
                        vous pouvez choisir de masquer votre profil de la liste publique des recruteurs à tout moment.
                    </p>
                </div>
            </div>
            <div class="accordion-item">
                <button id="accordion-button-2" aria-expanded="false">
                    <span class="accordion-title">Puis-je changer le modèle de CV ?</span>
                    <span class="icon" aria-hidden="true"></span>
                </button>
                <div class="accordion-content">
                    <p>
                        Non, la CVthèque utilise un modèle unique et standardisé pour garantir l'uniformité et la lisibilité
                        pour l'ensemble de nos recruteurs partenaires. La mise en forme est professionnelle et optimisée.
                    </p>
                </div>
            </div>
            <div class="accordion-item">
                <button id="accordion-button-3" aria-expanded="false">
                    <span class="accordion-title">Comment ajouter mes projets et expériences ?</span>
                    <span class="icon" aria-hidden="true"></span>
                </button>
                <div class="accordion-content">
                    <p>
                        Toutes les sections (Expériences, Projets, Formations) sont gérables depuis la page <a href="?page=profile-edit">Mon Profil</a>.
                        Vous pouvez ajouter, modifier ou supprimer chaque élément séparément.
                    </p>
                </div>
            </div>
            <div class="accordion-item">
                <button id="accordion-button-4" aria-expanded="false">
                    <span class="accordion-title">Que dois-je faire en cas d'oubli de mot de passe ?</span>
                    <span class="icon" aria-hidden="true"></span>
                </button>
                <div class="accordion-content">
                    <p>
                        Sur la page <a href="?page=login">Connexion</a>, un lien 'Mot de passe oublié' vous permet de lancer
                        la procédure de réinitialisation via votre adresse email.
                    </p>
                </div>
            </div>
            <div class="accordion-item">
                <button id="accordion-button-5" aria-expanded="false">
                    <span class="accordion-title">Où puis-je voir les entreprises qui recrutent ?</span>
                    <span class="icon" aria-hidden="true"></span>
                </button>
                <div class="accordion-content">
                    <p>
                        Vous pouvez consulter la liste de nos entreprises partenaires sur la page <a href="?page=companies-list">Partenaires</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>

</section>
