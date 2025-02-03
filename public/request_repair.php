<?php
require_once '../src/Repair.php';
require '../public/auth.php';  // Vérification de connexion
redirectIfNotLoggedIn();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $repair = new Repair();
    $user_id = $_SESSION['user_id'];
    $type_service = $_POST['type_service'];
    $location = $_POST['location'];

    if ($repair->createRepair($user_id, $type_service, $location)) {
        echo "✅ Votre demande a bien été envoyée !";
    } else {
        echo "❌ Erreur lors de l'envoi de la demande.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demander une réparation</title>
</head>
<body>
<h2>Faire une demande de réparation</h2>
<form method="POST">
    <label for="type_service">Type de service :</label>
    <select name="type_service" required>
        <option value="réparation">Réparation</option>
        <option value="entretien">Entretien</option>
        <option value="urgence">Dépannage d'urgence</option>
    </select>
    <br>
    <label for="location">Adresse :</label>
    <input type="text" name="location" required>
    <br>
    <button type="submit">Envoyer</button>
</form>
</body>
</html>
