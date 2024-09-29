<?php
// delete_task.php
include 'connexion.php'; // Inclure le fichier de connexion

if (isset($_GET['id'])) {
    $idtache = $_GET['id'];

    // Préparer la requête pour supprimer la tâche
    $sql = "DELETE FROM tache WHERE idtache = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idtache);
    
    if ($stmt->execute()) {
        // Rediriger vers la page d'accueil ou la liste des tâches après la suppression
        header("Location: index.php");
        exit();
    } else {
        echo "Erreur lors de la suppression de la tâche : " . $stmt->error;
    }

    $stmt->close();
}

// Fermer la connexion
$conn->close();
?>
