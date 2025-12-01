<div class="container py-4">

    <div class="row mb-4 align-items-center">

        <h2 class="mb-4">Liste de profils</h2>

        <div class="row g-4">

            <?php for ($i = 0; $i < 6; $i++): ?>
                <div class="col-12 col-md-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="row align-items-start">

                                <div class="col-3 col-sm-2 text-center">
                                    <div class="rounded-circle bg-light d-flex justify-content-center align-items-center"
                                         style="width: 70px; height: 70px; margin-top: 5px;">
                                        <i class="fas fa-user-circle fa-2x text-muted"></i>
                                    </div>
                                </div>

                                <div class="col-9 col-sm-10">
                                    <h5 class="card-title fw-bold mb-0">NOM PRÉNOM</h5>
                                    <p class="mb-0 text-muted">Poste Visée</p>
                                    <p class="text-secondary small">Ville</p>
                                </div>
                            </div>

                            <h6 class="mt-3 fw-bold">Compétences</h6>
                            <ul class="list-unstyled small ps-3">
                                <li>• List Item (PHP)</li>
                                <li>• List Item (SQL)</li>
                                <li>• List Item (JavaScript)</li>
                            </ul>

                            <div class="mt-3 d-flex justify-content-end gap-2">
                                <a href="?page=resume&id=<?=$i?>" class="btn btn-sm btn-outline-secondary">Voir
                                    CV</a>
                                <button class="btn btn-sm" style="background-color: #ac748f; color: white;">
                                    <a href="?page=profile&id=<?= $i ?>">Voir
                                        Profil</a>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>