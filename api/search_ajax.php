<?php
//include './config/config.php';
//include './config/search_logic.php';
//
//$filters = [
//    'search' => $_GET['search'] ?? '',
//    'filter_city' => $_GET['filter_city'] ?? '',
//    'filter_skill' => $_GET['filter_skill'] ?? '',
//    'filter_license' => $_GET['filter_license'] ?? 'all',
//    'filter_status' => $_GET['filter_status'] ?? 'all'
//];
//
//// Détection du mode (Admin ou Public)
//$viewMode = $_GET['view_mode'] ?? 'public';
//$isAdmin = ($viewMode === 'admin' && isset($_SESSION['user']) && $_SESSION['user']['role_id'] == 3);
//
//// Appel de la fonction centralisée
//$users = searchProfiles($pdo, $filters, $isAdmin);
//
//// Génération du HTML (Boucle)
//if (empty($users)) {
//    echo '<div class="col-12 text-center py-5"><h3 class="text-muted">Aucun profil trouvé.</h3></div>';
//} else {
//    foreach ($users as $user) {
//        $isActive = isset($user['is_active']) ? $user['is_active'] : 1;
//        $imgSrc = !empty($user['picture']) ? htmlspecialchars($user['picture']) : 'https://via.placeholder.com/300?text=Pas+d+image';
//
//        // Classes CSS spécifiques
//        $cardClass = ($isAdmin && !$isActive) ? 'inactive border-danger' : '';
//
//        echo '<div class="col-md-6 col-lg-3">';
//        echo '  <div class="card h-100 shadow-sm user-card ' . $cardClass . '">';
//
//        // Badge ID pour Admin
//        if ($isAdmin) {
//            echo '  <span class="badge-id">#' . $user['id'] . '</span>';
//        }
//
//        echo '      <img src="' . $imgSrc . '" class="card-img-top" alt="Avatar" style="height: 180px; object-fit: cover;">';
//
//        echo '      <div class="card-body d-flex flex-column">';
//        echo '          <h5 class="card-title text-center">' . htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) . '</h5>';
//        echo '          <p class="card-text text-center text-muted small mb-2">' . (!empty($user['job_title']) ? htmlspecialchars($user['job_title']) : 'Aucun poste') . '</p>';
//
//        // Boutons (Différents selon Admin ou Public)
//        echo '          <div class="mt-auto">';
//
//        if ($isAdmin) {
//            // BOUTONS ADMIN (Voir, Toggle, Delete)
//            // Note: Pour l'AJAX, les formulaires POST directs sont plus complexes.
//            // Ici on garde les liens simples ou on utilise un petit JS pour le submit si besoin.
//            // Pour simplifier l'exemple AJAX, on met juste le lien Voir.
//            echo '          <a href="index.php?page=profile-guest&id=' . $user['id'] . '" class="btn btn-primary w-100 mb-2">Gérer</a>';
//        } else {
//            // BOUTONS PUBLIC
//            echo '          <div class="d-grid gap-2">';
//            echo '              <a href="index.php?page=profile-guest&id=' . $user['id'] . '" class="btn btn-outline-primary">Voir le Profil</a>';
//            echo '              <a href="index.php?page=resume&id=' . $user['id'] . '" class="btn btn-dark">CV</a>';
//            echo '          </div>';
//        }
//
//        echo '          </div>';
//        echo '      </div>';
//        echo '  </div>';
//        echo '</div>';
//    }
//}