<?php
$title = "Créer un service";
ob_start();
?>
<h1>Créer un service</h1>
<form action="" method="post">
    <table>
        <thead>
            <tr>
                <th style="border: 1px solid #000; padding: 8px;">Nom de la prestation:</th>
                <th style="border: 1px solid #000; padding: 8px;">Coût de la prestation:</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border: 1px solid #000; padding: 8px;">
                    <input type="text" id="category" name="category" required>
                </td>
                <td style="border: 1px solid #000; padding: 8px;">
                    <input type="number" id="price" name="price" required>
                </td>
            </tr>
        </tbody>
    </table>


    <input type="submit" value="Créer">
</form>



</body>

</html>
<?php
$content = ob_get_clean();
require __DIR__ . '/../views/layoutAdminCreate.php';
?>