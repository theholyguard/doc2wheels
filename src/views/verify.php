<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Validation de compte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <h2 class="text-center">Validation de compte</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger text-center"><?= $error ?></div>
        <?php elseif (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success text-center"><?= $_SESSION['success_message'] ?></div>
        <?php endif; ?>
    </div>
</body>
</html>