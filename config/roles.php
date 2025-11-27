<?php
$user_id = ($_SESSION['user']['user_id']);

if (isset($_SESSION)) {
  if ($user_id = 1) {
    include('./roles/student.php');
  }
  if ($user_id = 2) {
    include('./roles/employer.php');
  }
  if ($user_id = 1) {
    include('./roles/student.php');
  }
};
