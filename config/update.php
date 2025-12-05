<?php

if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
  function update(PDO $pdo, $table, $column, $new_value, $user_id)
  {
    if (isset($_POST) && !empty($_POST)) {
      $colu = '';
      foreach ($column as $col) {
        $colu .= "$col = ?, ";
      }
      $colu = rtrim($colu, ', ');
      var_dump($table);
      $sqlUpdate = "UPDATE $table SET $colu WHERE id=$user_id";
      $stmt = $pdo->prepare($sqlUpdate);
      $stmt->execute($new_value);
    }
  };
  function updateSkills(PDO $pdo, $skillType)
  {
    if (isset($_POST) && !empty($_POST)) {

      $user_id = ($_SESSION['user']['id']);
      $skillInput = htmlspecialchars(trim($_POST[$skillType]));
      $sqlSelect = "SELECT id FROM `skills` WHERE $skillType LIKE ?";
      $stmtSelect = $pdo->prepare($sqlSelect);
      $stmtSelect->execute(["%$skillInput%"]);
      $skill_id = $stmtSelect->fetch(PDO::FETCH_COLUMN);

      if ($skill_id) {
        $sqlUserSkill = "INSERT INTO `user_has_skills` (`user_id`, `skills_id`) VALUES (?, ?)";
        $stmt = $pdo->prepare($sqlUserSkill);
        $stmt->execute([$user_id, $skill_id]);
      } else {
        $sqlUpdateSkill = "INSERT INTO `skills` (`$skillType`) VALUES (?)";
        $stmtSkill = $pdo->prepare($sqlUpdateSkill);
        $stmtSkill->execute([$skillInput]);

        $skill_id = $pdo->lastInsertId();

        $sqlUserSkill = "INSERT INTO `user_has_skills` (`user_id`, `skills_id`) VALUES (?, ?)";
        $stmt = $pdo->prepare($sqlUserSkill);
        $stmt->execute([$user_id, $skill_id]);
      }
    }
  }
};
