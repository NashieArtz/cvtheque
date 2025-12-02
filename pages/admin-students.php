<?php

include './config/roles/admin.php';


if (!isset($_SESSION) || $_SESSION['user']['role_id'] != 3) {
    header('location: http://localhost/1bcigit/cvtheque/index.php');
}
