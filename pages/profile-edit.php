<?php
$user_id = ($_SESSION['user']['id']);
$profile_id = 
?>
<link href="./assets/css/register-login.css" rel="stylesheet">
<form method="post">
  <label for="profile-edit-image">Photo de profil</label>
  <input type="file" name="image" id="profile-edit-image">

  <label for="profile-edit-name">Nom</label>
  <input type="text" name="name" id="profile-edit-name">

  <label for="profile-edit-forname">Prenom</label>
  <input type="text" name="forname" id="profile-edit-forname">

  <label for="profile-edit-username">Nom d'utilisateur</label>
  <input type="text" name="username" id="profile-edit-username">

  <label for="profile-edit-password">Mots de passe</label>
  <input type="password" name="password" id="profile-edit-password">

  <label for="profile-edit-Rpassword">Retaper votre Mots de passe</label>
  <input type="password" name="Rpassword" id="profile-edit-Rpassword">

  <label for="profile-edit-email">Email</label>
  <input type="email" name="email" id="profile-edit-email">

  <label for="profile-edit-permis">Permis</label>
  <input type="checkbox" name="permis" id="profile-edit-permis">

  <label for="profile-edit-hide-name">Masquer nom et prénom</label>
  <input type="checkbox" name="hide-name" id="profile-edit-hide-name">

  <label for="profile-edit-hide-photo">Inclure photo de profil</label>
  <input type="checkbox" name="hide-photo" id="profile-edit-hide-photo">

  <h2>Modifier les données de localisation</h2>
  <label for="profile-edit-country">Pays</label>
  <input type="text" name="country" id="profile-edit-country">

  <label for="profile-edit-city">Ville</label>
  <input type="text" name="city" id="profile-edit-city">

  <label for="profile-edit-cp">Code Postale</label>
  <input type="number" name="cp" id="profile-edit-cp">

  <h2>Compétences</h2>
  <label for="profile-edit-competence-actuelle">Compétences actuelles</label>
  <input type="text" name="competence-actuelle" id="profile-edit-competence-actuelle">

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
