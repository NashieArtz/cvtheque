<?php
include './db.php';
//parcourir toutes les tables pour en extraire les données
//pour chaque colonne d'un tableau, récupérer les données

function selectAll(PDO $pdo, $table)
{
  $sql = "SELECT * FROM `$table`
          LEFT JOIN `user_has_skills` ON user_has_skills.user_id = user.id
          LEFT JOIN `skills` ON user_has_skills.skills_id = skills.id
          LEFT JOIN `user_has_education` ON user_has_education.user_id = user.id
          LEFT JOIN `education` ON user_has_education.education_id = education.id
          LEFT JOIN `experience` ON experience.user_id = user.id 
          LEFT JOIN `address` ON address.user_id = user.id";
  return $pdo->query($sql)->fetchAll();
};

$user = selectAll($pdo, 'user');
foreach ($user as $u) {
  echo ($u['username'] . " " . $u['hard_skills'] . " " . $u['city']);
}








/*
if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {

  $user_id = ($_SESSION['user']['id']);

  function userInfo(PDO $pdo, $user_id)
  {
    $sql = "SELECT * FROM `user` WHERE `id` LIKE '$user_id'";
    return $pdo->query($sql)->fetch();
  };
  function userdata(PDO $pdo, $table, $user_id)
  {
    $sql = "SELECT * FROM `$table` WHERE `user_id` LIKE '$user_id'";
    return $pdo->query($sql)->fetch();
  };
  function edit_btn()
  {
    if (isset($_SESSION)) {
      if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
        echo '<a href="?page=profile-edit" class="btn btn-sm text-white" style="background-color: #ac748f;">Modifier le Profil</a> ?>
<a href="#" class="btn btn-sm btn-outline-dark ms-2">Générer CV</a>
</div>';
};
}
};
$user = userInfo($pdo, $user_id);
$address = userdata($pdo, "address", $user_id);
$experience = userdata($pdo, "experience", $user_id);
$skils = userdata($pdo, "skills", $user_id);
$education = userdata($pdo, "education", $user_id);
}
*/
