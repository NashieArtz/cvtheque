<?php

if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
  function update(PDO $pdo, $table, $column, $new_value)
  {
    if (isset($_POST) && !empty($_POST)) {
      $col = ' ';
      $value = ' ';
      $user_id = ($_SESSION['user']['id']);
      foreach ($column as $col) {
        $col += "`$col`, ";
        $value += '?, ';
      }
      $sql = "UPDATE `$table`(`$col`) SET VALUE ($value) WHERE `id` = $user_id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute($new_value);
    }
  };
  function updateSkills(PDO $pdo, $skillType)
  {
    if (isset($_POST) && !empty($_POST)) {

      $user_id = ($_SESSION['user']['id']);
      $skillInput = htmlspecialchars(trim($_POST[$skillType]));
      $sqlSelect = "SELECT * FROM `skills` WHERE '$skillType'.name LIKE '$skillInput'";
      $skill = $pdo->query($sqlSelect)->fetchAll();

      if ($skill) {
        $sqlUpdateUser = "UPDATE `user`(`skill_id`) WHERE id = '$user_id'";
        $stmt = $pdo->prepare($sqlUpdateUser);
        $stmt->execute($skill['id']);
      } else {
        $sqlUpdateSkill = "INSERT INTO `skills`(`$skillType`) VALUES (?)";
        $stmtSkill = $pdo->prepare($sqlUpdateSkill);
        $stmtSkill->execute([$skillInput]);
        $skill_id = $pdo->lastInsertId();
        $sqlUpdateUser = "UPDATE `user`(`skill_id`) SET VALUE ? WHERE id = '$user_id'";
        $stmtUser = $pdo->prepare($sqlUpdateUser);
        $stmtUser->execute([$skill_id]);
      }
    }
  };
}
