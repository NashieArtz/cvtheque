<?php

$role_id = ($_SESSION['user']['role_id']);

if (!($role_id == 3)) {
  header('Location: index.php');
}
