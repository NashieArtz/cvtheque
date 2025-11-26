<?php
if (isset($_SESSION)) {
  $role_id = $_SESSION['user']['role_id'];
  if ($role_id = 1) {
    include("includes/header-student.php");
  }
  if ($role_id = 2) {
    include("includes/header-employer");
  }
  if ($role_id = 3) {
    include("includes/header-admin");
  }
} else include("includes/header-guest");
