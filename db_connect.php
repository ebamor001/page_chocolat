<?php
$host = 'localhost';
$dbname = 'choco_shop';
$username = 'root';
$password = '';

// Connexion à la base
$db = mysqli_connect($host, $username, $password, $dbname);

if (mysqli_connect_errno()) {
    echo "Erreur de connexion à la base de données : " . mysqli_connect_error();
    exit;
}
?>
