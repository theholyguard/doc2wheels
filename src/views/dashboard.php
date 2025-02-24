<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body class="bg-white">
  <nav class="navbar">
    <div class="container flex-space-between align-items-center">
      <a class="navbar-brand" href="/">Doc2Wheels</a>
      <ul class="navbar-menu">
        <?php if (isset($_SESSION['user_id'])): ?>
          <?php if ($_SESSION['role'] === 'admin'): ?>
            <li><a href="/admin/">Admin</a></li>
          <?php endif; ?>
          <li><a href="/dashboard">Utilisateur</a></li>
          <li><a href="/logout">Déconnexion</a></li>
        <?php else: ?>
          <li><a href="/login">Connexion</a></li>
          <li><a href="/register">Inscription</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>

  <div class="container my-5">
    <h2 class="text-center">Tableau de bord</h2>
    <p class="text-center">Bienvenue, <?= htmlspecialchars($userName); ?>.</p>

    <div class="container my-5">
      <h3 class="text-center">📌 Demandes de réparation</h3>
      <?php if (empty($repairs)): ?>
        <p class="text-center text-grey">Aucune demande en attente.</p>
      <?php else: ?>
        <div class="grid grid-cols-2">
          <?php 
          $repairInstance = new \App\Entities\Repair();
          foreach ($repairs as $repair): 
          ?>
            <div class="card p-4 shadow-sm">
              <div class="card-body">
                <h5 class="card-title">🔧 <?= htmlspecialchars($repair['type_service']); ?></h5>
                <p class="card-text"><strong>📍 Lieu :</strong> <?= htmlspecialchars($repair['address']) . ', ' . htmlspecialchars($repair['city']) . ' ' . htmlspecialchars($repair['postal_code']); ?></p>
                <p class="card-text"><strong>🛠 Statut :</strong> 
                  <span class="badge bg-grey"><?= ucfirst($repair['status']); ?></span>
                </p>
                <p class="card-text"><strong><?= $role === 'technician' ? '👤 Client' : '👤 Technicien'; ?> :</strong> <?= htmlspecialchars($repair['client_name'] ?? $repair['technician_name']); ?></p>
                <p class="card-text"><strong>🏍 Catégorie de moto :</strong> <?= htmlspecialchars($repair['vehicle_category']); ?></p>
                <p class="card-text"><strong>💰 Prix :</strong> <?= htmlspecialchars($repair['price']); ?> €</p>
                <p class="card-text"><strong>💬 Message :</strong> <?= htmlspecialchars($repair['message']); ?></p>

                <?php if ($role === 'technician' && $repair['status'] === 'en attente'): ?>
                  <div class="flex-gap-2">
                    <form method="POST" action="/update_repair">
                      <input type="hidden" name="repair_id" value="<?= $repair['id']; ?>">
                      <input type="hidden" name="status" value="en cours">
                      <button type="submit" class="button button-primary button-sm">✅ Accepter</button>
                    </form>
                    <form method="POST" action="/update_repair">
                      <input type="hidden" name="repair_id" value="<?= $repair['id']; ?>">
                      <input type="hidden" name="status" value="refusé">
                      <button type="submit" class="button button-secondary button-sm">❌ Refuser</button>
                    </form>
                  </div>
                <?php endif; ?>

                <?php if ($role === 'client' && ($repair['status'] === 'en attente' || $repair['status'] === 'en cours')): ?>
                  <form method="POST" action="/update_repair">
                    <input type="hidden" name="repair_id" value="<?= $repair['id']; ?>">
                    <input type="hidden" name="status" value="annulé">
                    <button type="submit" class="button button-secondary button-sm">❌ Annuler</button>
                  </form>
                <?php endif; ?>

                <?php if ($role === 'client' && $repair['status'] === 'en cours'): ?>
                  <form method="POST" action="/update_repair">
                    <input type="hidden" name="repair_id" value="<?= $repair['id']; ?>">
                    <input type="hidden" name="status" value="terminé">
                    <button type="submit" class="button button-primary button-sm">✔️ Terminer</button>
                  </form>
                <?php endif; ?>

                <?php if ($role === 'client' && $repair['status'] === 'terminé' && !$repair['reviewed']): ?>
                  <form method="POST" action="/add_review" class="mt-3">
                    <input type="hidden" name="repair_id" value="<?= $repair['id']; ?>">
                    <div class="form">
                      <label class="input-label">Note :</label>
                      <select name="rating" class="input">
                        <option value="">-- Sélectionnez une note --</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                      </select>
                    </div>
                    <div class="form">
                      <label class="input-label">Commentaire :</label>
                      <textarea name="comment" class="input" rows="3"></textarea>
                    </div>
                    <button type="submit" class="button button-primary">Envoyer</button>
                  </form>
                <?php endif; ?>

                <?php if ($repair['reviewed']): ?>
                  <h5 class="mt-3">Avis :</h5>
                  <?php 
                  $reviews = $repairInstance->getRepairReviews($repair['id']); 
                  ?>
                  <?php foreach ($reviews as $review): ?>
                    <p class="card-text"><strong>Note :</strong> <?= htmlspecialchars($review['rating']); ?>/5</p>
                    <p class="card-text"><strong>Commentaire :</strong> <?= htmlspecialchars($review['comment']); ?></p>
                    <p class="card-text"><strong>Client :</strong> <?= htmlspecialchars($review['client_name']); ?></p>
                  <?php endforeach; ?>
                <?php endif; ?>
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
            <div class="grid grid-cols-3">
              <?php foreach ($services as $s) : ?>
                <div>
                  <label class="flex items-center">
                    <input type="checkbox" name="services[]" value="<?= $s['id'] ?>"
                      <?= in_array($s['id'], array_column($technicianServices, 'service_id')) ? 'checked' : '' ?>>
                    <span><?= htmlspecialchars($s['name']); ?></span>
                  </label>
                </div>
              <?php endforeach; ?>
            </div>
            <hr>
          <?php endforeach; ?>
          <button type="submit" class="button button-primary mt-3">💾 Sauvegarder</button>
        </form>
      </div>
    <?php endif; ?>

    <div class="card p-4 mb-4">
      <h4 class="mb-3">📍 Mes adresses</h4>
      <form method="POST" action="/add_address">
        <div class="form">
          <label class="input-label">Adresse :</label>
          <input type="text" name="address" class="input" required>
        </div>
        <div class="form">
          <label class="input-label">Ville :</label>
          <input type="text" name="city" class="input" required>
        </div>
        <div class="form">
          <label class="input-label">Code postal :</label>
          <input type="text" name="postal_code" class="input" required>
        </div>
        <button type="submit" class="button button-primary">Ajouter une adresse</button>
      </form>

      <h5 class="mt-4">Adresses enregistrées :</h5>
      <ul class="grid grid-cols-1">
        <?php foreach ($userAddresses as $address): ?>
          <li class="flex flex-space-between align-items-center">
            <?= htmlspecialchars($address['address']) . ', ' . htmlspecialchars($address['city']) . ' ' . htmlspecialchars($address['postal_code']); ?>
            <form method="POST" action="/delete_address">
              <input type="hidden" name="address_id" value="<?= $address['id']; ?>">
              <button type="submit" class="button button-secondary button-sm">Supprimer</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="card p-4 mb-4">
      <h4 class="mb-3">🔧 Modifier mes informations</h4>
      <form method="POST" action="/update_user_info">
        <div class="form">
          <label class="input-label">Nom :</label>
          <input type="text" name="name" class="input" value="<?= htmlspecialchars($userInfo['name']); ?>" required>
        </div>
        <div class="form">
          <label class="input-label">Email :</label>
          <input type="email" name="email" class="input" value="<?= htmlspecialchars($userInfo['email']); ?>" required>
        </div>
        <div class="form">
          <label class="input-label">Mot de passe :</label>
          <input type="password" name="password" class="input" required>
        </div>
        <button type="submit" class="button button-primary">Mettre à jour</button>
      </form>
    </div>
  </div>
</body>
</html>