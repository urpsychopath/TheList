<?php
$servername = "localhost";  // Le serveur MySQL
$username = "root";         // Ton nom d'utilisateur MySQL
$password = "";             // Ton mot de passe MySQL
$dbname = "todo";       // Nom de ta base de données

// Connexion à MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}
?>
