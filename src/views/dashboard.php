<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">Doc2Wheels</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item"><a class="nav-link" href="/admin/">Admin</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="/dashboard">Utilisateur</a></li>
                        <li class="nav-item"><a class="nav-link" href="/logout">Déconnexion</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="/login">Connexion</a></li>
                        <li class="nav-item"><a class="nav-link" href="/register">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <h2 class="text-center">Tableau de bord</h2>
        <p class="text-center">Bienvenue, <?= htmlspecialchars($userName); ?>.</p>

        <div class="container my-4">
            <h3 class="text-center">📌 Demandes de réparation</h3>
            <?php if (empty($repairs)): ?>
                <p class="text-center text-muted">Aucune demande en attente.</p>
            <?php else: ?>
                <div class="row">
                    <?php 
                    $repairInstance = new \App\Entities\Repair();
                    foreach ($repairs as $repair): 
                        error_log("Repair Data: " . print_r($repair, true));
                    ?>
                        <div class="col-md-6">
                            <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <h5 class="card-title">🔧 <?= htmlspecialchars($repair['type_service']); ?></h5>
                                    <p class="card-text"><strong>📍 Lieu :</strong> <?= htmlspecialchars($repair['address']) . ', ' . htmlspecialchars($repair['city']) . ' ' . htmlspecialchars($repair['postal_code']); ?></p>
                                    <p class="card-text"><strong>🛠 Statut :</strong> 
                                        <span class="badge bg-info"><?= ucfirst($repair['status']); ?></span>
                                    </p>
                                    <p class="card-text"><strong><?= $role === 'technician' ? '👤 Client' : '👤 Technicien'; ?> :</strong> <?= htmlspecialchars($repair['client_name'] ?? $repair['technician_name']); ?></p>
                                    <p class="card-text"><strong>🏍 Catégorie de moto :</strong> <?= htmlspecialchars($repair['vehicle_category']); ?></p>
                                    <p class="card-text"><strong>💰 Prix :</strong> <?= htmlspecialchars($repair['price']); ?> €</p>
                                    <p class="card-text"><strong>💬 Message :</strong> <?= htmlspecialchars($repair['message']); ?></p>

                                    <?php if ($role === 'technician' && $repair['status'] === 'en attente'): ?>
                                        <form method="POST" action="/update_repair" class="d-inline">
                                            <input type="hidden" name="repair_id" value="<?= $repair['id']; ?>">
                                            <input type="hidden" name="status" value="en cours">
                                            <button type="submit" class="btn btn-success btn-sm">✅ Accepter</button>
                                        </form>
                                        <form method="POST" action="/update_repair" class="d-inline">
                                            <input type="hidden" name="repair_id" value="<?= $repair['id']; ?>">
                                            <input type="hidden" name="status" value="refusé">
                                            <button type="submit" class="btn btn-danger btn-sm">❌ Refuser</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($role === 'client' && ($repair['status'] === 'en attente' || $repair['status'] === 'en cours')): ?>
                                        <form method="POST" action="/update_repair" class="d-inline">
                                            <input type="hidden" name="repair_id" value="<?= $repair['id']; ?>">
                                            <input type="hidden" name="status" value="annulé">
                                            <button type="submit" class="btn btn-warning btn-sm">❌ Annuler</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($role === 'client' && $repair['status'] === 'en cours'): ?>
                                        <form method="POST" action="/update_repair" class="d-inline">
                                            <input type="hidden" name="repair_id" value="<?= $repair['id']; ?>">
                                            <input type="hidden" name="status" value="terminé">
                                            <button type="submit" class="btn btn-primary btn-sm">✔️ Terminer</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($role === 'client' && $repair['status'] === 'terminé' && !$repair['reviewed']): ?>
                                        <form method="POST" action="/add_review" class="mt-3">
                                            <input type="hidden" name="repair_id" value="<?= $repair['id']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Note :</label>
                                                <select name="rating" class="form-select" required>
                                                    <option value="">-- Sélectionnez une note --</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Commentaire :</label>
                                                <textarea name="comment" class="form-control" rows="3" required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Envoyer</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($repair['reviewed']): ?>
                                        <h5 class="mt-3">Avis :</h5>
                                        <?php 
                                        $reviews = $repairInstance->getRepairReviews($repair['id']); 
                                        error_log("Reviews Data for Repair ID {$repair['id']}: " . print_r($reviews, true));
                                        ?>
                                        <?php foreach ($reviews as $review): ?>
                                            <p class="card-text"><strong>Note :</strong> <?= htmlspecialchars($review['rating']); ?>/5</p>
                                            <p class="card-text"><strong>Commentaire :</strong> <?= htmlspecialchars($review['comment']); ?></p>
                                            <p class="card-text"><strong>Client :</strong> <?= htmlspecialchars($review['client_name']); ?></p>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($role === 'technician'): ?>
            <div class="card p-4 mb-4">
                <h4 class="mb-3">🛠️ Mes services proposés</h4>
                <form method="POST" action="/update_services">
                    <input type="hidden" name="technician_id" value="<?= $user_id ?>">

                    <?php foreach ($allServicesByCategory as $category => $services): ?>
                        <h5 class="mt-3 text-primary"><?= htmlspecialchars($category); ?></h5>
                        <div class="row">
                            <?php foreach ($services as $s) : ?>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="services[]" value="<?= $s['id'] ?>"
                                            <?= in_array($s['id'], array_column($technicianServices, 'service_id')) ? 'checked' : '' ?>>
                                        <label class="form-check-label"><?= htmlspecialchars($s['name']); ?></label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <hr>
                    <?php endforeach; ?>

                    <button type="submit" class="btn btn-primary mt-3">💾 Sauvegarder</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="card p-4 mb-4">
            <h4 class="mb-3">📍 Mes adresses</h4>
            <form method="POST" action="/add_address">
                <div class="mb-3">
                    <label class="form-label">Adresse :</label>
                    <input type="text" name="address" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ville :</label>
                    <input type="text" name="city" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Code postal :</label>
                    <input type="text" name="postal_code" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Ajouter une adresse</button>
            </form>

            <h5 class="mt-4">Adresses enregistrées :</h5>
            <ul class="list-group">
                <?php foreach ($userAddresses as $address): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($address['address']) . ', ' . htmlspecialchars($address['city']) . ' ' . htmlspecialchars($address['postal_code']); ?>
                        <form method="POST" action="/delete_address" class="d-inline">
                            <input type="hidden" name="address_id" value="<?= $address['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="card p-4 mb-4">
            <h4 class="mb-3">🔧 Modifier mes informations</h4>
            <form method="POST" action="/update_user_info">
                <div class="mb-3">
                    <label class="form-label">Nom :</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($userInfo['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email :</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($userInfo['email']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe :</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>

    </div>

</body>
</html>