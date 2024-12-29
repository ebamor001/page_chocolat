<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


// Inclure le fichier de connexion
include 'db_connect.php';

// Recuperer les données du formulaire avec des requêtes préparées
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$message = $_POST['message'];


// Insérer la commande dans la base de données
$sql = "INSERT INTO commandes (nom_client, email_client, numero_telephone, adresse, message) 
        VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($db, $sql);

// Associer les valeurs aux paramètres
mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phone, $address, $message);

// Exécuter la requête
if (mysqli_stmt_execute($stmt)) {
    $confirmation_message = "Merci, $name ! Votre message a été enregistrée avec succès.";
} else {
    $confirmation_message = "Erreur lors de l'enregistrement de votre message : " . mysqli_error($db);
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
    <h1>message confirmé</h1>
    <p><?php echo $confirmation_message; ?></p>

    <div class="order-details">
        <h3>Détails du message :</h3>
        <ul>
            <li><strong>Nom :</strong> <?php echo htmlspecialchars($name); ?></li>
            <li><strong>Email :</strong> <?php echo htmlspecialchars($email); ?></li>
            <li><strong>Téléphone :</strong> <?php echo htmlspecialchars($phone); ?></li>
            <li><strong>Adresse :</strong> <?php echo htmlspecialchars($address); ?></li>
            <li><strong>Message :</strong> <?php echo nl2br(htmlspecialchars($message)); ?></li>
        </ul>
    </div>

    <a href="index.html" class="back-home-btn">Retour à l'accueil</a>
</div>

</body>
</html>
