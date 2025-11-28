<?php
function userInfo(PDO $pdo)
{
  $user_id = ($_GET['id']);
  $sql = "SELECT * FROM `user` WHERE `id` LIKE '$user_id'";
  return $pdo->query($sql)->fetch();
};
function userAddress(PDO $pdo)
{
  $user_id = ($_GET['id']);
  $sql = "SELECT * FROM `address` RIGHT JOIN `country` ON address.country_id = country.id WHERE `user_id` LIKE '$user_id'";
  return $pdo->query($sql)->fetch();
};
function userExperience(PDO $pdo)
{
  $user_id = ($_GET['id']);
  $sql = "SELECT * FROM `experience` WHERE `user_id` LIKE '$user_id'";
  return $pdo->query($sql)->fetch();
};
function edit_btn()
{
  if (isset($_SESSION)) {
    if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
      echo '<a href="?page=profile-edit" class="btn btn-sm text-white" style="background-color: #ac748f;">Modifier le Profil</a> ?>
<a href="#" class="btn btn-sm btn-outline-dark ms-2">Générer CV</a>
</div>';
    }
  }
};

$user = userInfo($pdo);
$address = userAddress($pdo);
$experience = userExperience($pdo);
echo ($address['streetname'])
?>

<div class="container myo-5">

  <div class="row justify-content-center">
    <div class="col-lg-10 col-md-12">
      <div class="card shadow-sm mb-4 border-0">
        <div class="card-body p-4 p-md-5" style="background-color: #f0f0f0;">
          <div class="row align-items-center">

            <div class="col-12 col-md-3 text-center mb-4 mb-md-0">
              <img src="https://picsum.photos/300/300" alt="Icone utilisateur"
                class="img-fluid rounded-circle shadow-lg" style="width: 150px; height: 150px; object-fit: cover;">
            </div>

            <div class="col-12 col-md-6 text-center text-md-start">
              <h1 class="display-5 fw-bold mb-0">
                <?= $user['firstname'] ?>
                <?= $user['lastname'] ?>
              </h1>
              <p class="lead" style="color: #613F75;">Étudiant / Postulant</p>
              <p class="text-muted mb-0">@
                <?= $user['username'] ?>
              </p>
            </div>

            <div class="col-12 col-md-3 text-center text-md-end">
              <?= edit_btn() ?>
            </div>
          </div>
        </div>

        <div class="card shadow-sm mb-4">
          <div class="card-header fw-bold">Détails Personnels & Localisation</div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-6">
                <h5 class="fw-bold">Coordonnées</h5>
                <p class="mb-1 text-muted"><i class="fas fa-envelope me-2"></i> Email :
                  <?= $user['email'] ?>
                </p>
                <p class="mb-1 text-muted"><i class="fas fa-phone me-2"></i> Numéro : 06 XX XX XX XX</p>
                <p class="mb-0 text-muted"><i class="fas fa-key me-2"></i> Mots de passe : ••••••••</p>
              </div>
              <div class="col-md-6">
                <h5 class="fw-bold">Localisation</h5>
                <p class="mb-1 text-muted"><i class="fas fa-globe-europe me-2"></i> Pays : France</p>
                <p class="mb-1 text-muted"><i class="fas fa-city me-2"></i> Ville : Rouen</p>
                <p class="mb-0 text-muted"><i class="fas fa-map-pin me-2"></i> Code Postal : 76570</p>
              </div>
            </div>
          </div>
        </div>

        <div class="card shadow-sm mb-4">
          <div class="card-header fw-bold">Compétences Actuelles</div>
          <div class="card-body">
            <ul class="list-inline">
              <li class="list-inline-item"><span class="badge bg-primary fs-6">HTML5/CSS3</span></li>
              <li class="list-inline-item"><span class="badge bg-secondary fs-6">JavaScript</span></li>
              <li class="list-inline-item"><span class="badge bg-info fs-6">Bootstrap</span></li>
            </ul>
          </div>
        </div>

        <div class="card shadow-sm mb-4">
          <div class="card-header fw-bold">Expériences Professionnelles</div>
          <div class="card-body">
            <dl class="row">
              <dt class="col-sm-4 text-dark fw-normal">Web Développeur (Google)</dt>
              <dd class="col-sm-8 text-muted">10 novembre 2025 à 24 décembre 2025</dd>
              <dt class="col-sm-4"></dt>
              <dd class="col-sm-8 small fst-italic">Description courte de la mission.</dd>
            </dl>
          </div>
        </div>

        <div class="card shadow-sm mb-4">
          <div class="card-header fw-bold">Options de Visibilité</div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="profile-edit-permis" disabled checked>
                  <label class="form-check-label text-muted" for="profile-edit-permis">Permis de Conduire</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="profile-edit-hide-name" disabled>
                  <label class="form-check-label text-muted" for="profile-edit-hide-name">Masquer nom et prénom</label>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="profile-edit-hide-photo" disabled checked>
                  <label class="form-check-label text-muted" for="profile-edit-hide-photo">Inclure photo de
                    profil</label>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
