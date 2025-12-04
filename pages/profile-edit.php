<?php

include './config/release.php';
include './config/update.php';


if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
  if (!empty($_POST) && isset($_POST)) {
    $email = htmlspecialchars(trim($_POST['email']));
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $job_title = htmlspecialchars(trim($_POST['job_title']));

    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
      $picture = file_get_contents($_FILES['picture']['tmp_name']);
    } else {
      // Garder l'ancienne image si aucun nouveau fichier n'est uploadé
      $picture = null; // ou récupérer l'image existante de la base de données
    };
    $phone = htmlspecialchars(trim($_POST['phone']));

    if (isset($_POST['driver_licence'])) $driver_licence = 1;
    else $driver_licence = 0;

    $username = htmlspecialchars(trim($_POST['username']));
    $country_id = htmlspecialchars(trim($_POST['country']));
    $city = htmlspecialchars(trim($_POST['city']));
    $area_code = htmlspecialchars(trim($_POST['area_code']));

    $user_columns = ["email", "firstname", "lastname", "job_title", "phone", "driver_licence", "username", "country_id"];
    $user_values = [$email, $firstname, $lastname, $job_title,  $phone, $driver_licence, $username];
    $address_columns = ["area_code", "city"];
    $address_values = [$city, $area_code];

    if ($picture !== null) {
      $user_column[] = "picture";
      $user_values[] = $picture;
    }

    update($pdo, "user", $user_columns, $user_values);
    update($pdo, "address", $address_columns, $address_values);
    updateSkills($pdo, 'hard_skills');
    updateSkills($pdo, 'soft_skills');
    updateSkills($pdo, 'hobbies');
  };
}
$user_id = ($_SESSION['user']['id']);
$user = userData($pdo, $user_id);
foreach ($user as $u) {
?>
  <link href="./assets/css/register-login.css" rel="stylesheet">

  <form method="post">
    <section id="user_data">

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
    </section>

    <section id="address">
      <h2>Modifier les données de localisation</h2>

      <label for="profile-edit-country">Pays
        <select name="country">
          <?php
          // fonction pour récupérer la liste des pays
          function selectCountry(PDO $pdo)
          {
            $sqlCountry = "SELECT * FROM `country`";
            return $pdo->query($sqlCountry)->fetchAll();
          };
          $country = selectCountry($pdo);
          foreach ($country as $c) {
          ?>
            <option value="<?= $c['id'] ?>">
              <?= $c['name'] ?>
            </option>
          <?php
          }
          ?>
        </select>
      </label>

      <label for="profile-edit-city">Ville</label>
      <input type="text" name="city" id="profile-edit-city" value="<?= $u['city'] ?>">

      <label for="profile-edit-cp">Code Postale</label>
      <input type="number" name="area_code" id="profile-edit-cp" value="<?= $u['area_code'] ?>">

    </section>

    <section id="skills">

      <h2>Compétences</h2>

      <label for=" profile-edit-competence-actuelle">Hard Skills
        <input type="text" name="hard_skills" class="profile-edit-competence-actuelle" id="hard_skills"
          placeholder="Ajouter une compétence">
        <button type="submit" id="hardSkillSubmit">Ajouter</button>>
      </label>

      <label for=" profile-edit-competence-actuelle">Soft Skills
        <input type="text" name="soft_skills" class="profile-edit-competence-actuelle"
          placeholder="Ajouter une compétence">
        <button type="submit" id="hardSkillSubmit">Ajouter</button>
      </label>

      <label for=" profile-edit-competence-actuelle">Soft Skills <input type="text" name="soft_skills"
          class="profile-edit-competence-actuelle" id="soft_skills" placeholder="Ajouter une passion">
        <button type="submit" id="hardSkillSubmit">Ajouter</button>
      </label>
      <label for=" profile-edit-competence-actuelle">Passions <input type="text" name="hobbies"
          class="profile-edit-competence-actuelle" id="soft_skills" placeholder="Ajouter une compétence">
        <button type="submit" id="hardSkillSubmit">Ajouter</button>
      </label>

    </section>

    <section id="experience">

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
    </section>
  </form>

<?php
}
?>
