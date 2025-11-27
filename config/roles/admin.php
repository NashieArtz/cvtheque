<?php
$role_id = ($_SESSION['user']['role_id']);

$student_page = ($_GET['student']);
$employer_page = ($_GET['employer']);
$admin_page = ($_GET['admin']);

if (isset($_SESSION)) {
  if ($student_page && !$role_id = 1) {
    header('Location = index');
  }
  if ($employer_page && !$role_id = 2) {
    header('Location = index');
  }
  if ($admin_page && !$role_id = 3) {
    header('Location = index');
  }
};
