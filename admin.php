<?php 
// Inclure le fichier de connexion
include 'db_connect.php';

// Récupérer les commandes
$sql = "SELECT * FROM commandes ORDER BY date_commande DESC";
$result = mysqli_query($db, $sql);

// Liste des nouveaux statuts
$statuts = ['En attente de traitement', 'En cours de préparation', 'Prêt à expédier', 'Expédié', 'Livré'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 20px;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        td {
            font-size: 14px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            overflow: auto;
        }

        .status-select {
            padding: 5px;
            font-size: 14px;
        }

        .update-btn {
            padding: 5px 10px;
            font-size: 14px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .update-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Commandes</h1>
    <table>
        <tr>
            <th>Nom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Adresse</th>
            <th>Produits</th>
            <th>Message</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Modifier</th>
            <th>etat_paiement</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= htmlspecialchars($row['nom_client']) ?></td>
                <td><?= htmlspecialchars($row['email_client']) ?></td>
                <td><?= htmlspecialchars($row['numero_telephone']) ?></td>
                <td><?= htmlspecialchars($row['adresse']) ?></td>
                <td>
                    <?php 
                    $produits = json_decode($row['produits'], true); // true pour convertir en tableau associatif
                    $quantites = json_decode($row['quantites'], true);
                    
                    // Vérifier que $produits et $quantites sont des tableaux valides
                    if (is_array($produits) && is_array($quantites)) {
                        $order_details = [];
                        foreach ($produits as $index => $produit) {
                            $quantite = isset($quantites[$index]) ? $quantites[$index] : "Non spécifiée"; 
                            $order_details[] = htmlspecialchars($produit) . " (Quantité: " . htmlspecialchars($quantite) . ")";
                        }
                        echo implode("<br>", $order_details);
                    } else {
                        // Ne rien afficher si les données ne sont pas valides
                        echo "";
                    }
                    ?>
                </td>
                <td><?= htmlspecialchars($row['message']) ?></td>
                <td><?= $row['date_commande'] ?></td>
                <td><?= htmlspecialchars($row['status']) ?></td>
                
                
                <td>
                    <form method="POST" action="update.php">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($row['id']) ?>">
                        <select name="status" class="status-select">
                            <?php foreach ($statuts as $statut): ?>
                                <option value="<?= htmlspecialchars($statut) ?>" <?= $row['status'] == $statut ? 'selected' : '' ?>><?= htmlspecialchars($statut) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="update-btn">Mettre à jour</button>
                    </form>
                </td>

                <td>
                <?= $row['etat_paiement'] ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
