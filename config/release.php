<?php
//function userInfo(PDO $pdo)
//{
//  $user_id = ($_SESSION['user']['id']);
//  $sql = "SELECT * FROM `user` WHERE `id` LIKE '$user_id'";
//  return $pdo->query($sql)->fetch();
//};
//function userdata(PDO $pdo, $table)
//{
//  $user_id = ($_SESSION['user']['id']);
//  $sql = "SELECT * FROM `$table` WHERE `user_id` LIKE '$user_id'";
//  return $pdo->query($sql)->fetch();
//};
//function edit_btn()
//{
//  if (isset($_SESSION)) {
//    if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
//      echo '<a href="?page=profile-edit" class="btn btn-sm text-color">Modifier le Profil</a>
//<a href="#" class="btn btn-sm ms-2">Générer CV</a>
//</div>';
//    }
//  }
//};
//
//$user = userInfo($pdo);
//$address = userdata($pdo, "address");
//$country = userdata($pdo, "country");
//$experience = userdata($pdo, "experience");
//$skils = userdata($pdo, "skills");
//$education = userdata($pdo, "education");
