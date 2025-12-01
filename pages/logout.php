<?php
session_destroy();
session_start();
$_SESSION['logout_message'] = "Vous avez été déconnecté avec succès !";
header('Location: index.php');
exit;


