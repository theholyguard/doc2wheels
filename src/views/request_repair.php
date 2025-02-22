<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demander une réparation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container my-5">
        <h2 class="text-center">📩 Demande de Réparation</h2>

        <!-- ✅ Message d'erreur -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="card p-4 shadow">
            <div class="mb-3">
                <label class="form-label">Catégorie de service :</label>
                <select name="category" class="form-select" required>
                    <option value="">-- Sélectionnez une catégorie --</option>
                    <?php foreach ($allServicesByCategory as $category => $services): ?>
                        <option value="<?= htmlspecialchars($category); ?>"><?= htmlspecialchars($category); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse :</label>
                <select name="address_id" class="form-select" required>
                    <option value="">-- Sélectionnez une adresse --</option>
                    <?php foreach ($userAddresses as $address): ?>
                        <option value="<?= htmlspecialchars($address['id']); ?>"><?= htmlspecialchars($address['address']) . ', ' . htmlspecialchars($address['city']) . ' ' . htmlspecialchars($address['postal_code']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Catégorie de véhicule :</label>
                <select name="vehicle_category_id" class="form-select" required>
                    <option value="">-- Sélectionnez une catégorie de véhicule --</option>
                    <?php foreach ($vehicleCategories as $category): ?>
                        <option value="<?= htmlspecialchars($category['id']); ?>"><?= htmlspecialchars($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Message :</label>
                <textarea name="message" class="form-control" rows="3" placeholder="Message"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Rechercher des techniciens</button>
        </form>

        <?php if (isset($selectedValues)): ?>
            <div class="mt-5">
                <h3 class="text-center">Techniciens disponibles</h3>
                <?php if (empty($selectedValues['technicians'])): ?>
                    <p class="text-center text-muted">Aucun technicien disponible pour cette catégorie de service.</p>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($selectedValues['technicians'] as $technician): 
                            error_log(print_r($technician, true));
                            ?>
                            <div class="col-md-6">
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">🔧 <?= htmlspecialchars($technician['technician_name']); ?></h5>
                                        <p class="card-text"><strong>📍 Adresse :</strong> <?= htmlspecialchars($technician['address']) . ', ' . htmlspecialchars($technician['city']) . ' ' . htmlspecialchars($technician['postal_code']); ?></p>
                                        <p class="card-text"><strong>💰 Prix :</strong> <?= htmlspecialchars($technician['price']); ?> €</p>
                                        <p class="card-text"><strong>💸 Remise :</strong> <?= htmlspecialchars($technician['discount'] * 100); ?> %</p>
                                        <p class="card-text"><strong>⭐ Note moyenne :</strong> <?= number_format($technician['average_rating'], 1); ?>/5</p>
                                        <a href="/request_repair?technician_id=<?= htmlspecialchars($technician['technician_id']); ?>&category=<?= htmlspecialchars($selectedValues['selectedCategory']); ?>&address_id=<?= htmlspecialchars($selectedValues['selectedAddressId']); ?>&vehicle_category_id=<?= htmlspecialchars($selectedValues['selectedVehicleCategoryId']); ?>&message=<?= htmlspecialchars($selectedValues['message']); ?>&price=<?= htmlspecialchars($technician['price']); ?>" class="btn btn-success">Choisir ce technicien</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>