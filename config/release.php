<?php
function userInfo(PDO $pdo)
{
  $user_id = ($_SESSION['user']['id']);
  $sql = "SELECT * FROM `user` WHERE `id` LIKE '$user_id'";
  return $pdo->query($sql)->fetch();
};
function userAddress(PDO $pdo)
{
  $user_id = ($_SESSION['user']['id']);
  $sql = "SELECT * FROM `address` RIGHT JOIN `country` ON address.country_id = country.id WHERE `user_id` LIKE '$user_id'";
  return $pdo->query($sql)->fetch();
};
function userExperience(PDO $pdo)
{
  $user_id = ($_SESSION['user']['id']);
  $sql = "SELECT * FROM `experience` WHERE `user_id` LIKE '$user_id'";
  return $pdo->query($sql)->fetch();
};
function edit_btn()
{
  if (isset($_SESSION)) {
    if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
      echo '<a href="?page=profile-edit" class="btn btn-sm text-color">Modifier le Profil</a> 
<a href="#" class="btn btn-sm ms-2">Générer CV</a>
</div>';
    }
  }
};

$user = userInfo($pdo);
$address = userAddress($pdo);
$experience = userExperience($pdo);
