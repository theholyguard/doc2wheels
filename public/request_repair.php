<?php
session_start(); // Démarre la session
require 'auth.php';
redirectIfNotLoggedIn();
require_once '../src/Repair.php';
require_once '../src/Service.php';

$repair = new Repair();
$service = new Service();
$user_id = $_SESSION['user_id'];
$allServicesByCategory = $service->getAllServicesGroupedByCategory();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type_service = $_POST['type_service'];
    $location = $_POST['location'];

    if ($repair->createRepair($user_id, $type_service, $location)) {
        $_SESSION['success_message'] = "Votre demande a bien été envoyée !";
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Une erreur est survenue lors de l'envoi de la demande.";
    }
}
?>

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
                <label class="form-label">Type de service :</label>
                <select name="type_service" class="form-select" required>
                    <option value="">-- Sélectionnez un service --</option>
                    <?php foreach ($allServicesByCategory as $category => $services): ?>
                        <optgroup label="<?= htmlspecialchars($category); ?>">
                            <?php foreach ($services as $s) : ?>
                                <option value="<?= htmlspecialchars($s['name']); ?>">
                                    <?= htmlspecialchars($s['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Adresse :</label>
                <input type="text" name="location" class="form-control" placeholder="Entrez votre adresse" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Envoyer</button>
        </form>
    </div>

</body>
</html>
