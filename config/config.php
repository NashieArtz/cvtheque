<?php
include './config/db.php';
include './config/roles.php';

if (isset($_SESSION)) {
  session_start();
}
