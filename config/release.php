<?php
function userAll(PDO $pdo)
{
    $sql = "SELECT * FROM `user`
          LEFT JOIN `user_has_skills` ON user_has_skills.user_id = user.id
          LEFT JOIN `skills` ON user_has_skills.skills_id = skills.id
          LEFT JOIN `user_has_education` ON user_has_education.user_id = user.id
          LEFT JOIN `education` ON user_has_education.education_id = education.id
          LEFT JOIN `experience` ON experience.user_id = user.id 
          LEFT JOIN `country` ON user.country_id = country.id 
          LEFT JOIN `address` ON address.user_id = user.id";
    return $pdo->query($sql)->fetchAll();
};

function userData(PDO $pdo, $user_id)
{
    $sql = "SELECT * FROM `user`
          LEFT JOIN `user_has_skills` ON user_has_skills.user_id = user.id
          LEFT JOIN `skills` ON user_has_skills.skills_id = skills.id
          LEFT JOIN `user_has_education` ON user_has_education.user_id = user.id
          LEFT JOIN `education` ON user_has_education.education_id = education.id
          LEFT JOIN `experience` ON experience.user_id = user.id 
          LEFT JOIN `country` ON user.country_id = country.id 
          LEFT JOIN `address` ON address.user_id = user.id
          WHERE user.id ='$user_id'";
  return $pdo->query($sql)->fetchAll();
}
