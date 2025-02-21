<?php
if (!isset($users)) {
    die("Erreur : les utilisateurs ne sont pas chargés correctement.");
}
$title = "Gestion des utilisateurs";
ob_start();
?>

<h2>Liste de tous les utilisateurs</h2>

<table>
    <thead>
        <tr>
            <th style="border: 1px solid #000; padding: 8px;">Nom</th>
            <th style="border: 1px solid #000; padding: 8px;">Email</th>
            <th style="border: 1px solid #000; padding: 8px;">Rôle</th>
       </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td style="border: 1px solid #000; padding: 8px;"><?= htmlspecialchars($user['name']) ?></td>
                <td style="border: 1px solid #000; padding: 8px;"><?= htmlspecialchars($user['email']) ?></td>
                <td style="border: 1px solid #000; padding: 8px;"><?= htmlspecialchars($user['role']) ?></td>
                </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<form action="/admin/user/edit/<?= $user['id'] ?>" method="POST">
    <label for="name">Nom:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <label for="role">Rôle:</label>
    <select name="role" required>
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
        <option value="technician" <?= $user['role'] === 'technician' ? 'selected' : '' ?>>Technicien</option>
        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
    </select>

    <button type="submit">Mettre à jour</button>
</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/../views/layoutAdmin.php';
?>
