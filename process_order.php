<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


// Vérifier si 'cart_data' existe dans $_POST
if (isset($_POST['cart_data'])) {
    // Convertir la chaîne JSON en tableau PHP
    $cart_data = json_decode($_POST['cart_data'], true);

    if ($cart_data === null) {
        echo "Erreur : Les données du panier ne sont pas valides.";
    } 
} else {
    echo "Aucune donnée de panier reçue.";
}

// Inclure le fichier de connexion
include 'db_connect.php';

// Recuperer les données du formulaire avec des requêtes préparées
$name = isset($_POST['name']) ? $_POST['name'] : ' ';
$email = isset($_POST['email']) ? $_POST['email'] : ' ';
$phone = isset($_POST['phone']) ? $_POST['phone'] : ' ';
$address = isset($_POST['address']) ? $_POST['address'] : ' ';
$message = isset($_POST['message']) ? $_POST['message'] : ' ';
$etat_paiement = isset($_POST['etat_paiement']) ? $_POST['etat_paiement'] : ' '; 


// Récupérer les produits et quantités du panier
$cart_data = isset($_POST['cart_data']) ? json_decode($_POST['cart_data'], true) : [];
$produits = [];
$quantites = [];

foreach ($cart_data as $item) {
    $produits[] = $item['product'];
    $quantites[] = isset($item['quantity']) ? $item['quantity'] : 1; // Stocker la quantité, pas le prix
}

// Préparer les données pour l'insertion
$produits_json = json_encode($produits);
$quantites_json = json_encode($quantites);

// Insérer la commande dans la base de données
$sql = "INSERT INTO commandes (nom_client, email_client, numero_telephone, adresse, message, produits, quantites,etat_paiement) 
        VALUES (?, ?, ?, ?, ?, ?, ?,?)";
$stmt = mysqli_prepare($db, $sql);

// Associer les valeurs aux paramètres
mysqli_stmt_bind_param($stmt, "ssssssss", $name, $email, $phone, $address, $message, $produits_json, $quantites_json,$etat_paiement);

// Exécuter la requête
if (mysqli_stmt_execute($stmt)) {
    $confirmation_message = "Merci, $name ! Votre commande a été enregistrée avec succès.";
} else {
    $confirmation_message = "Erreur lors de l'enregistrement de votre commande : " . mysqli_error($db);
}

// Fermer la requête et la connexion
mysqli_stmt_close($stmt);
mysqli_close($db);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirmation de commande</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        body {
            font-family: "Inconsolata", sans-serif;
            background-color: #f4f4f9;
        }
        .confirmation-container {
            background-color: #fff;
            padding: 50px;
            margin: 100px auto;
            max-width: 600px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .confirmation-container h1 {
            color: #28a745;
        }
        .confirmation-container p {
            font-size: 18px;
        }
        .confirmation-container .order-details {
            margin-top: 20px;
        }
        .confirmation-container .order-details ul {
            list-style-type: none;
            padding: 0;
        }
        .confirmation-container .order-details li {
            font-size: 16px;
        }
        .back-home-btn {
            display: block;
            text-align: center;
            margin-top: 30px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-home-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<div class="confirmation-container">
    <h1>Commande confirmée</h1>
    <p><?php echo $confirmation_message; ?></p>

    <div class="order-details">
        <h3>Détails de la commande :</h3>
        <ul>
            <li><strong>Nom :</strong> <?php echo htmlspecialchars($name); ?></li>
            <li><strong>Email :</strong> <?php echo htmlspecialchars($email); ?></li>
            <li><strong>Téléphone :</strong> <?php echo htmlspecialchars($phone); ?></li>
            <li><strong>Adresse :</strong> <?php echo htmlspecialchars($address); ?></li>
            <li><strong>Message :</strong> <?php echo nl2br(htmlspecialchars($message)); ?></li>
            <li><strong>Produits :</strong> <?php echo implode(", ", json_decode($produits_json)); ?></li>
            <li><strong>Quantités :</strong> <?php echo implode(", ", json_decode($quantites_json)); ?></li>
            <li><strong>type de paiement :</strong> <?php echo htmlspecialchars($etat_paiement) ; ?></li>
        </ul>
    </div>

    <a href="index.html" class="back-home-btn">Retour à l'accueil</a>
</div>

</body>
</html>

<!--case "sur place":
                    messageDiv.innerHTML = "Vous avez choisi de payer sur place. Merci de préparer le paiement lors de la livraison.";
                    break;
                case "paylib":
                    messageDiv.innerHTML = "Vous avez choisi Paylib. Nous allons vous envoyer le numéro de téléphone pour le paiement à votre adresse e-mail.";
                    break;
                case "virement":
                    messageDiv.innerHTML = "Vous avez choisi le virement bancaire. Nous vous enverrons nos coordonnées bancaires par e-mail.";
                    break;-->