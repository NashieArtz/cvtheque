<?php

include './config/config.php';


$route = [
  'index' => __DIR__ . '/index.php',

    // PAGES
        'profile' => __DIR__ . '/pages/profile-student.php',
        'profile-edit' => __DIR__ . '/pages/profile-edit.php',
        'profiles-list' => __DIR__ . '/pages/profiles-list.php',
        'dashboard' => __DIR__ . '/pages/dashboard.php',
        'resume' => __DIR__ . '/pages/resume.php',
    'delete' => __DIR__ . '/pages/delete.php',

    // Connection
        'login' => __DIR__ . '/pages/login.php',
        'register' => __DIR__ . '/pages/register.php',
        'logout' => __DIR__ . '/pages/logout.php',

        'admin-dashboard' => __DIR__ . '/pages/admin-dashboard.php',
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
include("includes/footer.php");
?>
