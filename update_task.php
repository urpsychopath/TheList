<?php
include 'connexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idtache = $_POST['idtache'];
    $completed = $_POST['completed'];

    $sql = "UPDATE tache SET completed = ? WHERE idtache = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $completed, $idtache);

    if ($stmt->execute()) {
        echo "Tâche mise à jour avec succès!";
    } else {
        echo "Erreur lors de la mise à jour de la tâche.";
    }

    $stmt->close();
}
$conn->close();
?>
