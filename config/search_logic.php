<?php
function searchProfiles(PDO $pdo, array $filters, bool $isAdmin = false): array
{
    $params = [];

    // Stocker les valeurs propres
    $params = [];

    $sql = "SELECT u.*, a.city, r.name as role_name 
            FROM user u 
            LEFT JOIN address a ON u.id = a.user_id 
            LEFT JOIN role r ON u.role_id = r.id 
            WHERE 1=1";
    // LEFT JOIN: GET l'user même sans adresse/rôle
    // WHERE 1=1: Empêche de vérifier si on ajoute constamment la 1ère condition/suivante
    
    // 2. Gestion de la visibilité
    if (!$isAdmin) {
        // Guests ne voit que les profils actifs
        $sql .= " AND u.is_active = 1";
    } elseif (!empty($filters['filter_status']) && $filters['filter_status'] !== 'all') {
        // Filtrage par status
        if ($filters['filter_status'] === 'active') {
            $sql .= " AND u.is_active = 1";
        } elseif ($filters['filter_status'] === 'inactive') {
            $sql .= " AND (u.is_active = 0 OR u.is_active IS NULL)";
        }
    }

    if (!empty($filters['filter_skill'])) {
        $sql .= " JOIN user_has_skills uhs ON u.id = uhs.user_id 
                  JOIN skill s ON uhs.skill_id = s.id ";
    }

    if (!empty($filters['filter_license']) && $filters['filter_license'] === 'yes') {
        $sql .= " AND u.driver_licence = 1";
    }

    if (!empty($filters['filter_city'])) {
        $sql .= " AND a.city LIKE :city";
        $params[':city'] = "%" . $filters['filter_city'] . "%";
    }

    if (!empty($filters['filter_skill'])) {
        $sql .= " AND s.name LIKE :skill";
        $params[':skill'] = "%" . $filters['filter_skill'] . "%";
    }

    if (!empty($filters['search'])) {
        $term = "%" . $filters['search'] . "%";
        $sql .= " AND (u.firstname LIKE :s1 
                    OR u.lastname LIKE :s2 
                    OR u.username LIKE :s3
                    OR u.job_title LIKE :s4 
                    OR a.city LIKE :s5)";
        $params[':s1'] = $term;
        $params[':s2'] = $term;
        $params[':s3'] = $term;
        $params[':s4'] = $term;
        $params[':s5'] = $term;
    }

    // Group by & order
    if (!empty($filters['filter_skill'])) {
        $sql .= " GROUP BY u.id, a.city, r.name";
    }
    $sql .= " ORDER BY u.id DESC";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}
?>