<?php
?>
<link href="./assets/css/register-login.css" rel="stylesheet">
<form method="post">
    <label for="name">Nom</label>
    <input type="text" name="name" id="name">
    <label for="forname">Prenom</label>
    <input type="text" name="forname" id="forname">
    <label for="username">Nom d'utilisateur</label>
    <input type="text" name="username" id="username">
    <label for="password">Mots de passe</label>
    <input type="password" name="password" id="password">
    <label for="Rpassword">Retaper votre Mots de passe</label>
    <input type="password" name="Rpassword" id="Rpassword">
    <label for="email">Email</label>
    <input type="email" name="email" id="email">
    <label for="permis">Permis</label>
    <input type="checkbox" name="permis" id="permis">
    <label for="hide-name">Masquer nom et prénom</label>
    <input type="checkbox" name="hide-name" id="hide-name">
    <label for="hide-photo">Inclure photo de profil</label>
    <input type="checkbox" name="hide-photo" id="hide-photo">
        <h2>Modifier les données de localisation</h2>
    <label for="country">Pays</label>
    <input type="text" name="country" id="country">
    <label for="city">Ville</label>
    <input type="text" name="city" id="city">
    <label for="cp">Code Postale</label>
    <input type="number" name="cp" id="cp">
        <h2>Compétences</h2>
        <label for="competence-actuelle">Compétences actuelles</label>
        <input type="text" name="competence-actuelle" id="competence-actuelle">
        <input type="submit" name="submit" id="modifie-competence" value="Modifier les compétences">
        <label></label>
        <input type="submit" name="submit" id="add-competence" value="Ajout de compétences">
    <label for="competence">OPTION: Compétence</label>
    <input type="text" name="competence" id="competence">
    <label for="competence-level">OPTION: Niveau compétence</label>
    <input type="text" name="competence-level" id="competence-level">
    <h2>Expériences</h2>
    <label for="experience-name">Nom/Titre expérience</label>
    <input type="text" name="experience-name" id="experience-name">
    <label for="experience-year">Année expérience</label>
    <input type="text" name="experience-year" id="experience-year">
    <input type="submit" name="submit" id="modifie-experience" value="Modifier & Supprimer">
    <label></label>
    <input type="submit" name="submit" id="add-experience" value="Ajouter expérience">
    <input type="submit" name="button" id="button"


</form>
