<?php
include './config/release.php';
include './config/update.php';


if (!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) {
  header('Location: index.php?page=login');
  exit();
}

$user_id = $_SESSION['user']['id'];
$message = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $email = htmlspecialchars(trim($_POST['email']));
  $firstname = htmlspecialchars(trim($_POST['firstname']));
  $lastname = htmlspecialchars(trim($_POST['lastname']));
  $job_title = htmlspecialchars(trim($_POST['job_title']));
  $phone = htmlspecialchars(trim($_POST['phone']));
  $username = htmlspecialchars(trim($_POST['username']));


  $country_id = !empty($_POST['country']) ? intval($_POST['country']) : null;
  $city = htmlspecialchars(trim($_POST['city']));
  $area_code = htmlspecialchars(trim($_POST['area_code']));


  $driver_licence = isset($_POST['driver_licence']) ? 1 : 0;


  $picture = null;
  if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
    $picture = 'data:' . $_FILES['picture']['type'] . ';base64,' . base64_encode(file_get_contents($_FILES['picture']['tmp_name']));
  }

  $user_columns = ['email', 'firstname', 'lastname', 'job_title', 'phone', 'driver_licence', 'country_id', 'username'];
  $user_values = [$email, $firstname, $lastname, $job_title, $phone, $driver_licence, $country_id, $username];

  if ($picture) {
    $user_columns[] = 'picture';
    $user_values[] = $picture;
  }
  $table_update = "`user`";
  update($pdo, $table_update, $user_columns, $user_values, $user_id);

  $stmtAddr = $pdo->prepare("UPDATE address SET city = ?, area_code = ? WHERE user_id = ?");
  $stmtAddr->execute([$city, $area_code, $user_id]);

  if (function_exists('updateSkills')) {
    updateSkills($pdo, 'hard_skills');
    updateSkills($pdo, 'soft_skills');
    updateSkills($pdo, 'hobbies');
  }

  $message = "<div class='alert alert-success'>Profil mis à jour avec succès !</div>";
}

$userResults = userData($pdo, $user_id);
foreach ($userResults as $u) {
?>

  <div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold m-0">Mon Profil</h2>
      <a href="index.php?page=profile-user" class="btn btn-outline-secondary btn-sm btn-return-dashboard">
        Retour Dashboard
      </a>
    </div>

    <?= $message ?>

    <form method="post" enctype="multipart/form-data" id="profileForm">
      <div class="row g-4">

        <div class="col-lg-4">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
              <div class="edit-section-title text-center">Identité</div>

              <div class="profile-edit-avatar-container">
                <?php $imgSrc = !empty($u['picture']) ? $u['picture'] : 'https://via.placeholder.com/150'; ?>
                <img src="<?= htmlspecialchars($imgSrc) ?>" id="avatar-preview" class="profile-edit-avatar">
                <label for="picture-input" class="btn-upload-icon" title="Changer la photo">
                </label>
                <input type="file" name="picture" id="picture-input" class="file-input-hidden"
                  onchange="previewImage(event)">
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Nom d'utilisateur</label>
                <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($u['username']) ?>">
              </div>

              <div class="row">
                <div class="col-6 mb-3">
                  <label class="form-label small text-muted">Prénom</label>
                  <input type="text" name="firstname" class="form-control"
                    value="<?= htmlspecialchars($u['firstname']) ?>">
                </div>
                <div class="col-6 mb-3">
                  <label class="form-label small text-muted">Nom</label>
                  <input type="text" name="lastname" class="form-control" value="<?= htmlspecialchars($u['lastname']) ?>">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($u['email']) ?>">
              </div>

              <div class="mb-3">
                <label class="form-label small text-muted">Téléphone</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($u['phone']) ?>">
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
              <div class="edit-section-title">Infos Professionnelles</div>

              <div class="mb-3">
                <label class="form-label small text-muted">Poste Visé</label>
                <input type="text" name="job_title" class="form-control" value="<?= htmlspecialchars($u['job_title']) ?>">
              </div>

              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="driver_licence" id="driver_licence"
                  <?= $u['driver_licence'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="driver_licence">Permis de conduire</label>
              </div>
            </div>
          </div>

          <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
              <div class="edit-section-title">Localisation</div>

              <div class="mb-3">
                <label class="form-label small text-muted">Pays</label>
                <select name="country" class="form-select">
                  <option value="">Sélectionner...</option>
                  <?php
                  $countries = $pdo->query("SELECT * FROM country ORDER BY name ASC")->fetchAll();
                  foreach ($countries as $c) {
                    $selected = (isset($u['country_id']) && $u['country_id'] == $c['id']) ? 'selected' : '';
                    echo "<option value='{$c['id']}' $selected>{$c['name']}</option>";
                  }
                  ?>
                </select>
              </div>

              <div class="row">
                <div class="col-7 mb-3">selected
                  <label class="form-label small text-muted">Ville</label>
                  <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($u['city']) ?>">
                </div>
                <div class="col-5 mb-3">
                  <label class="form-label small text-muted">CP</label>
                  <input type="text" name="area_code" class="form-control"
                    value="<?= htmlspecialchars($u['area_code']) ?>">
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card shadow-sm border-0 h-100">
            <div class="card-body">
              <div class="edit-section-title">Compétences</div>

              <div class="mb-4">
                <label class="form-label small fw-bold">Hard Skills</label>
                <div class="skills-input-wrapper">
                  <input type="text" id="input-hard" class="form-control form-control-sm" placeholder="Ex: PHP, SQL">
                  <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSkillTag('hard')">
                    +
                  </button>
                </div>
                <div id="list-hard" class="skills-list-container">
                  <?php
                  if (isset($u['skills']) && is_array($u['skills'])) {
                    foreach ($u['skills'] as $s) {
                      if (!empty($s['hard_skills'])) {
                        echo '<div class="skill-tag-item"><span>' . htmlspecialchars($s['hard_skills']) . '</span><span class="skill-tag-remove" onclick="this.parentElement.remove()">✕</span></div>';
                      }
                    }
                  }
                  ?>
                </div>
                <input type="hidden" name="hard_skills" id="hidden-hard">
              </div>

              <div class="mb-4">
                <label class="form-label small fw-bold">Soft Skills</label>
                <div class="skills-input-wrapper">
                  <input type="text" id="input-soft" class="form-control form-control-sm" placeholder="Ex: Rigueur">
                  <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSkillTag('soft')">
                    +
                  </button>
                </div>
                <div id="list-soft" class="skills-list-container">
                  <?php
                  if (isset($u['skills']) && is_array($u['skills'])) {
                    foreach ($u['skills'] as $s) {
                      if (!empty($s['soft_skills'])) {
                        echo '<div class="skill-tag-item"><span>' . htmlspecialchars($s['soft_skills']) . '</span><span class="skill-tag-remove" onclick="this.parentElement.remove()">✕</span></div>';
                      }
                    }
                  }
                  ?>
                </div>
                <input type="hidden" name="soft_skills" id="hidden-soft">
              </div>

              <div class="mb-4">
                <label class="form-label small fw-bold">Hobbies</label>
                <div class="skills-input-wrapper">
                  <input type="text" id="input-hobbies" class="form-control form-control-sm" placeholder="Ex: Football">
                  <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSkillTag('hobbies')">
                    +
                  </button>
                </div>
                <div id="list-hobbies" class="skills-list-container">
                  <?php
                  ?>
                </div>
                <input type="hidden" name="hobbies" id="hidden-hobbies">
              </div>

            </div>
          </div>
        </div>

      </div>

      <div class="row mt-4 mb-5">
        <div class="col-12 text-center">
          <button type="submit" name="submit-save-all" class="btn btn-primary btn-lg px-5 shadow"
            onclick="prepareTagsForSubmit()">
            Sauvegarder le Profil
          </button>
        </div>
      </div>

    </form>
  </div>

<?php } ?>
