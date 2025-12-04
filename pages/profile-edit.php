<?php

include './config/release.php';
include './config/update.php';


if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
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
    $country_id = $pdo->query("SELECT id FROM `country` WHERE name='$country'");
    $city = htmlspecialchars(trim($_POST['city']));
    $area_code = htmlspecialchars(trim($_POST['area_code']));

    $user_column = ["email", "firstname", "lastname", "job_title", "picture", "phone", "driver_licence", "username", "country_id"];
    $user_values = [$email, $firtname, $lastname, $job_title, $picture, $phone, $driver_licence, $username];
    $address_columns = ["area_code", "city"];
    $adress_values = [$city, $area_code];

    update($pdo, "user", $user_column, $user_values);
    update($pdo, "address", $adress_column, $address_values);
  };
}
$user_id = ($_SESSION['user']['id']);
$user = userData($pdo, $user_id);
foreach ($user as $u) {
?>
  <link href="./assets/css/register-login.css" rel="stylesheet">

  <form method="post">

    <label for="profile-edit-image">Photo de profil
      <input type="file" name="picture" id="profile-edit-image">
    </label>
    <label for="profile-edit-name">Nom
      <input type="text" name="lastname" id="profile-edit-name" value="<?= $u['lastname'] ?>">
    </label>
    <label for="profile-edit-forname">Prénom
      <input type="text" name="firstname" id="profile-edit-forname" value="<?= $u['firstname'] ?>">
    </label>
    <label for="profile-edit-username">Nom d'utilisateur
      <input type="text" name="username" id="profile-edit-username" value="<?= $u['username'] ?>">
    </label>
    <label for="profile-edit-password">Mots de passe
      <input type="password" name="password" id="profile-edit-password" value="0000000000">
    </label>
    <label for="profile-edit-Rpassword">Retaper votre Mots de passe
      <input type="password" name="Rpassword" id="profile-edit-Rpassword">
    </label>
    <label for="profile-edit-email">Email
      <input type="email" name="email" id="profile-edit-email" value="<?= $u['email'] ?>">
    </label>
    <label for="profile-edit-job_title">Quel métier souhaitez-vous exercer ?
      <input type="text" name="job_title" id="profile-edit-job_title" value="<?= $u['job_title'] ?>">
    </label>
    <label for="profile-edit-permis">Permis
      <input type="checkbox" name="driver_licence" id="profile-edit-permis">
    </label>
    <label for=" profile-edit-hide-name">Masquer nom et prénom
      <input type="checkbox" name="hide-name" id="profile-edit-hide-name">
    </label>
    <label for="profile-edit-hide-photo">Inclure photo de profil
      <input type="checkbox" name="hide-photo" id="profile-edit-hide-photo">
    </label>

    <h2>Modifier les données de localisation</h2>
    <label for="profile-edit-country">Pays
      <?php
      // fonction pour récupérer la liste des pays
      function selectCountry(PDO $pdo)
      {
        $sql = "SELECT * FROM `country`";
        return $pdo->query($sql)->fetchAll();
      };
      $country = selectCountry($pdo);
      foreach ($country as $c) {
      ?>
        <option value="<?= $u['name'] ?> ">
        </option>
      <?php
      };
      ?>
    </label>
    <label for="profile-edit-city">Ville
      <input type="text" name="city" id="profile-edit-city" value="<?= $u['adress']['city'] ?> ">
    </label>
    <label for="profile-edit-cp">Code Postale
      <input type="number" name="area_code" id="profile-edit-cp" value="<?= $u['address']['area_code'] ?>">
    </label>

    <section id="skills">
      <h2>Compétences</h2>
      <?php
      function selectSkills(PDO $pdo)
      {
        $user_id = ($_SESSION['user']['id']);
        $sql = "SELECT * FROM `skills` WHERE user_id='$user_id'";
        return $pdo->query($sql)->fetchAll();
      };

      $skills = selectSkills($pdo);
      foreach ($skills as $s)
      ?>
      <label for=" profile-edit-competence-actuelle">Hard Skills
        <input type="text" name="skills" class="profile-edit-competence-actuelle" id="hardSkills"
          placeholder="Ajouter une compétence">
        <button type="submit" id="hardSkillSubmit">Ajouter</button>>
        <section class="skillList">
          <ul is="list">
          </ul>
        </section>
      </label>
      <script src="assets/js/skill-edit.js"></script>
    </section>
    <h2>Expériences</h2>

    <label for=" profile-edit-experience-name">Nom/Titre expérience</label>
    <input type="text" name="experience-name" id="profile-edit-experience-name">

    <label for="profile-edit-experience-year">Année expérience</label>
    <input type="text" name="experience-year" id="profile-edit-experience-year">

    <input type="submit" name="submit-experience-modifie" id="profile-edit-modifie-experience"
      value="Modifier & Supprimer">
    <label></label>
    <input type="submit" name="submit-experience-add" id="profile-edit-add-experience" value="Ajouter expérience">

    <input type="submit" name="submit-save-all" id="profile-edit-save-button" value="Sauvegarder le Profil">

  </form>

<?php } ?>
