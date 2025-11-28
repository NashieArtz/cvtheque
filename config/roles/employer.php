<?php

$role_id = ($_SESSION['user']['role_id']);

if (!($role_id == 2)) {
  header('Location: index.php');
}
