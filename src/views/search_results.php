<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats de recherche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <h2 class="text-center">🔍 Résultats pour "<?= htmlspecialchars($query) ?>" à "<?= htmlspecialchars($location) ?>"</h2>

    <?php if (empty($results)): ?>
        <p class="text-center text-muted">Aucun service trouvé.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($results as $service) : ?>
                <div class="col-md-4">
                    <div class="card shadow-sm mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($service['name']); ?></h5>
                            <p class="card-text"><strong>📍 Disponible à :</strong> <?= htmlspecialchars($service['location']); ?></p>
                            <a href="request_repair.php?service_id=<?= $service['id']; ?>" class="btn btn-warning btn-sm">Demander ce service</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>