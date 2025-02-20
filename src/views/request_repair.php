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


        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" class="card p-4 shadow">
            <div class="mb-3">
                <label class="form-label">Catégorie de service :</label>
                <select name="category" class="form-select" required>
                    <option value="">-- Sélectionnez une catégorie --</option>
                    <?php foreach ($allServicesByCategory as $category => $services): ?>
                        <option value="<?= htmlspecialchars($category); ?>">
                            <?= htmlspecialchars($category); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse :</label>
                <select name="address_id" class="form-select" required>
                    <option value="">-- Sélectionnez une adresse --</option>
                    <?php foreach ($userAddresses as $address): ?>
                        <option value="<?= $address['id']; ?>">
                            <?= htmlspecialchars($address['address']) . ', ' . htmlspecialchars($address['city']) . ' ' . htmlspecialchars($address['postal_code']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success w-100">Rechercher Techniciens</button>
        </form>

        <?php if (isset($technicians)): ?>
            <h3 class="text-center mt-5">Techniciens disponibles</h3>
            <div class="list-group">
                <?php foreach ($technicians as $technician): ?>
                    <a href="/request_repair?technician_id=<?= $technician['technician_id']; ?>&category=<?= htmlspecialchars($selectedCategory); ?>&address_id=<?= htmlspecialchars($selectedAddressId); ?>" class="list-group-item list-group-item-action">
                        <?= htmlspecialchars($technician['technician_name']); ?> - <?= htmlspecialchars($technician['address']) . ', ' . htmlspecialchars($technician['city']) . ' ' . htmlspecialchars($technician['postal_code']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>