<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>CVthèque</title>
  <link href="./assets/css/index.css" rel="stylesheet">
  <link href="./assets/css/register-login.css" rel="stylesheet">
  <link href="./assets/css/header-footer.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

  <?php
  if (isset($_SESSION['user']['role_id'])) {
    $role_id = $_SESSION['user']['role_id'];
    if ($role_id == 1) {
      include("includes/header-student.php");
    }
    if ($role_id == 2) {
      include("includes/header-employer.php");
    }
    if ($role_id == 3) {
      include("includes/header-admin.php");
    }
  } else include("includes/header-guest.php");
  ?>
