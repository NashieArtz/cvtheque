<?php
include './config/release.php';
include './config/update.php';

if (!empty($_POST) && isset($_POST)) {
  $email = htmlspecialchars(trim($_POST['email']));
  $firtname = htmlspecialchars(trim($_POST['firstname']));
  $lastname = htmlspecialchars(trim($_POST['lastname']));
  $job_title = htmlspecialchars(trim($_POST['job_title']));
  $picture = file_get_contents($_FILES['picture']);
  $phone = htmlspecialchars(trim($_POST['phone']));
  if (isset($_POST['driver_licence'])) $driver_licence = 1;
  else $driver_licence = 0;
  $username = htmlspecialchars(trim($_POST['username']));
  $country = htmlspecialchars(trim($_POST['country']));
  $city = htmlspecialchars(trim($_POST['city']));
  $area_code = htmlspecialchars(trim($_POST['area_code']));


  $user_column = ["email", "firstname", "lastname", "job_title", "picture", "phone", "driver_licence", "username"];
  $user_values = [$email, $firtname, $lastname, $job_title, $picture, $phone, $driver_licence, $username];
  $address_columns = ["area_code", "city"];
  $adress_values = [$city, $area_code];
  $country_columns = "country";

  update($pdo, "user", $user_column, $user_values);
  update($pdo, "address", $adress_column, $address_values);
  update($pdo, "country", $country_columns, $country);
}
?>
<link href="./assets/css/register-login.css" rel="stylesheet">
<form method="post">
  <label for="profile-edit-image">Photo de profil</label>
  <input type="file" name="picture" id="profile-edit-image">

  <label for="profile-edit-name">Nom</label>
  <input type="text" name="lastname" id="profile-edit-name" value="<?= $user['lastname'] ?>">

  <label for="profile-edit-forname">Prénom</label>
  <input type="text" name="firstname" id="profile-edit-forname" value="<?= $user['firstname'] ?>">

  <label for="profile-edit-username">Nom d'utilisateur</label>
  <input type="text" name="username" id="profile-edit-username">

  <label for="profile-edit-password">Mots de passe</label>
  <input type="password" name="password" id="profile-edit-password">

  <label for="profile-edit-Rpassword">Retaper votre Mots de passe</label>
  <input type="password" name="Rpassword" id="profile-edit-Rpassword">

  <label for="profile-edit-email">Email</label>
  <input type="email" name="email" id="profile-edit-email">

  <label for="profile-edit-job_title">Quel métier souhaitez-vous exercer ?</label>
  <input type="text" name="job_title" id="profile-edit-job_title">

  <label for="profile-edit-permis">Permis</label>
  <input type="checkbox" name="driver_licence" id="profile-edit-permis>

  <label for=" profile-edit-hide-name">Masquer nom et prénom</label>
  <input type="checkbox" name="hide-name" id="profile-edit-hide-name">

  <label for="profile-edit-hide-photo">Inclure photo de profil</label>
  <input type="checkbox" name="hide-photo" id="profile-edit-hide-photo">

  <h2>Modifier les données de localisation</h2>
  <label for="profile-edit-country">Pays</label>
  <input type="text" name="country" id="profile-edit-country">

  <label for="profile-edit-city">Ville</label>
  <input type="text" name="city" id="profile-edit-city">

  <label for="profile-edit-cp">Code Postale</label>
  <input type="number" name="area_code" id="profile-edit-cp">

  <h2>Compétences</h2>
  <label for="profile-edit-competence-actuelle">Compétences actuelles</label>
  <input type="text" name="skills" id="profile-edit-competence-actuelle">

  <input type="submit" name="submit-competence-modifie" id="profile-edit-modifie-competence"
    value="Modifier les compétences">
  <label></label>
  <input type="submit" name="submit-competence-add" id="profile-edit-add-competence" value="Ajout de compétences">

  <label for="profile-edit-competence">OPTION: Compétence</label>
  <input type="text" name="competence" id="profile-edit-competence">

  <label for="profile-edit-competence-level">OPTION: Niveau compétence</label>
  <input type="text" name="competence-level" id="profile-edit-competence-level">

  <h2>Expériences</h2>
  <label for="profile-edit-experience-name">Nom/Titre expérience</label>
  <input type="text" name="experience-name" id="profile-edit-experience-name">

  <label for="profile-edit-experience-year">Année expérience</label>
  <input type="text" name="experience-year" id="profile-edit-experience-year">

  <input type="submit" name="submit-experience-modifie" id="profile-edit-modifie-experience"
    value="Modifier & Supprimer">
  <label></label>
  <input type="submit" name="submit-experience-add" id="profile-edit-add-experience" value="Ajouter expérience">

  <input type="submit" name="submit-save-all" id="profile-edit-save-button" value="Sauvegarder le Profil">

</form>
