<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Inclure le fichier de connexion
include 'db_connect.php';

// Vérifier si l'ID de la commande et le nouveau statut sont envoyés
if (isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $new_status = mysqli_real_escape_string($db, $_POST['status']);
    
    // Mettre à jour le statut dans la base de données
    $sql = "UPDATE commandes SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($db, $sql);
    
    // Lier les paramètres et exécuter la requête
    mysqli_stmt_bind_param($stmt, "si", $new_status, $order_id);
    if (mysqli_stmt_execute($stmt)) {
        echo "Statut mis à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour du statut.";
    }

    // Fermer la connexion
    mysqli_stmt_close($stmt);
    mysqli_close($db);

    // Rediriger vers la page des commandes
    header("Location: admin.php");
    exit;
} else {
    echo "Informations manquantes.";
}
?>
