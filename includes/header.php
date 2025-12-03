<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
  <meta charset="UTF-8">
  <title>CVthèque</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="./assets/css/index.css" rel="stylesheet">
  <link href="./assets/css/header-footer.css" rel="stylesheet">
  <link href="./assets/css/register-login.css" rel="stylesheet">
  <link href="./assets/css/resume.css" rel="stylesheet">

</head>

<body>

  <?php
  $navClass = 'navbar-guest';

  if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
    $role_id = $_SESSION['user']['role_id'] ?? null;

    if ($role_id == 1) {
      $navClass = 'navbar-student';
      include("includes/header-student.php");
    }
    if ($role_id == 3) {
      $navClass = 'navbar-admin';
      include("includes/header-admin.php");
    }
  } else include("includes/header-guest.php");
  ?>
