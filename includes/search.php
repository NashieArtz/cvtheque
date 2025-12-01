<?php
$allusers = $pdo->prepare("SELECT * FROM `users` ORDER BY `id` DESC");
if(isset($_GET['s']) AND !empty($_GET['s'])) {
    $recherche = htmlspecialchars($_GET['s']);
    $allusers = $pdo->prepare('SELECT * FROM `users` WHERE * LIKE "%' . $recherche . '%" ORDER BY `id` DESC');
}