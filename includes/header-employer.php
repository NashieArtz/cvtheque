<nav class="navbar navbar-expand-lg" style="background:#5a3f78;">
    <div class="container-fluid">
        <a class="navbar-brand text-white fs-3" href="?page=dashboard">
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
                    <a class="nav-link text-white fs-5" href="?page=employer-list">Entreprise</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white fs-5" href="?page=profiles-list">Voir CV</a>
                </li>
                <li class="nav-item">
                    <a class="btn px-4 py-2 fs-5"
                       style="background:#FFFFFF; color:#b6859a;  border:2px solid #b6859a; border-radius:20px;"
                       href="?page=employer-list">
                        Mon Profile Entreprise
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn px-4 py-2 text-white fs-5" style="background:#b6859a; border-radius:20px;" href="?page=logout">
                        Se deconnecter
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php
<<<<<<< HEAD
=======

include './config/db.php';


$route = [
  'index' => __DIR__ . '/index.php',

  // PAGES
  'profile' => __DIR__ . '/pages/profile.php',
  'profile-edit' => __DIR__ . '/pages/profile-edit.php',
  'profiles-list' => __DIR__ . '/pages/profiles-list.php',
  'dashboard' => __DIR__ . '/pages/dashboard.php',
  'employer-list' => __DIR__ . '/pages/employer-list.php',

  // Connection
  'login' => __DIR__ . '/pages/login.php',
  'register' => __DIR__ . '/pages/register.php',
  'logout' => __DIR__ . '/pages/logout.php',

  'admin-dashboard' => __DIR__ . '/pages/admin-dashboard.php',
  'admin-employer' => __DIR__ . '/pages/admin-employer.php',
  'admin-students' => __DIR__ . '/pages/admin-students.php',


  // ERROR
  '404' => __DIR__ . '/pages/404.php',
  '405' => __DIR__ . '/pages/405.php',
];

$page = $_GET["page"] ?? 'dashboard';
$viewFile = $route[$page] ?? __DIR__ . '/pages/404.php';

$title = "CVthèque - Accueil";

switch ($page) {
  case 'register':
    $title = "Inscription";
    break;
  case 'login':
    $title = "Connexion";
    break;
  default:
    break;
}

include("includes/header.php");
?>
<main>
  <?php
  include_once($viewFile); ?>
</main>

<?php
include("./includes/footer.php");
?>
>>>>>>> a12f186229974c975bbbffd409893e22de0b9667
