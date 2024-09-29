<?php
// Fichier delete_task.php
include 'connexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_task'])) {
    $taskId = $_POST['idtache'];
    
    $sql = "DELETE FROM tache WHERE idtache = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $taskId);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Tâche supprimée avec succès']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression de la tâche']);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur de préparation de la requête']);
    }
    
    exit;
}
?>