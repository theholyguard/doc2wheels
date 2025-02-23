<?php
if (!isset($users)) {
    die("Erreur : les utilisateurs ne sont pas chargés correctement.");
}
$title = "Gestion des utilisateurs";
ob_start();



if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_GET['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    updateUser($id, $name, $email, $role);
}

?>

<h2>Liste de tous les utilisateurs</h2>

<table>
    <thead>
        <tr>
            <th style="border: 1px solid #000; padding: 8px;">Nom</th>
            <th style="border: 1px solid #000; padding: 8px;">Email</th>
            <th style="border: 1px solid #000; padding: 8px;">Rôle</th>
            <th style="border: 1px solid #000; padding: 8px;">Actions</th>
       </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <form action="" method="POST">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <td style="border: 1px solid #000; padding: 8px;">
                        <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </td>
                    <td style="border: 1px solid #000; padding: 8px;">
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </td>
                    <td style="border: 1px solid #000; padding: 8px;">
                        <select name="role" required>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
                            <option value="technician" <?= $user['role'] === 'technician' ? 'selected' : '' ?>>Technicien</option>
                            <option value="client" <?= $user['role'] === 'client' ? 'selected' : '' ?>>Client</option>
                        </select>
                    </td>
                    <td style="border: 1px solid #000; padding: 8px;">
                        <button type="submit">Mettre à jour</button>
                        </form>
                        <form action="/admin/user/delete" method="POST">
                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<?php
$content = ob_get_clean();
require __DIR__ . '/../views/layoutAdmin.php';
?>
