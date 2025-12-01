<?php

$_id = ($_SESSION['user']['role_id']);

if (!($role_id == 1)) {
  header('Location: index.php');
}
