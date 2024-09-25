<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Pacifico&display=swap" rel="stylesheet">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <title> TheList.</title>
    </head>

    <body>
        <nav>
            <div class="navbar1">
                <ul>  
                    <li class="top"><a href="#">TheList.</a></li>
                    <li > <span class="circle"></span> <a class="open-manage-modal-nav" href="#">Mes Listes</a></li>
                </ul>
            </div>  
        </nav>
        
        <?php
// Inclure le fichier de connexion
include 'connexion.php';

// Traitement du formulaire d'ajout de tâche
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
    // Récupérer la description de la tâche soumise
    if (!empty($_POST['tache'])) {
        $description = $_POST['tache'];
        $idliste = 1;  // Supposons que la liste a l'ID 1 (à modifier dynamiquement plus tard)

        // Préparer la requête pour insérer la tâche dans la table 'tache'
        $sql = "INSERT INTO tache (description, idliste) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            echo "Erreur lors de la préparation de la requête : " . $conn->error;
            exit();
        }

        $stmt->bind_param("si", $description, $idliste);  // Liaison des paramètres

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "Tâche ajoutée avec succès!";
        } else {
            echo "Erreur lors de l'ajout de la tâche : " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Le champ 'tache' est requis.";
    }
}

// Traitement du formulaire d'ajout de liste
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_list'])) {
    if (!empty($_POST['listName']) && !empty($_POST['color'])) {
        $liste = $_POST['listName'];
        $id_colo = $_POST['color'];

        // Préparer la requête pour insérer la liste dans la table 'liste'
        $sql = "INSERT INTO liste (nom, idcolo) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            echo "Erreur lors de la préparation de la requête : " . $conn->error;
            exit();
        }

        $stmt->bind_param("si", $liste, $id_colo);  // Liaison des paramètres

        // Exécuter la requête
        if ($stmt->execute()) {
            echo "Liste ajoutée avec succès!";
        } else {
            echo "Erreur lors de l'ajout de la liste : " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Tous les champs sont requis pour créer une liste.";
    }
}

// Fermer la connexion
$conn->close();
?>
 <!-- Modal pour ajouter une nouvelle liste -->
 <div id="addListModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Ajouter une liste</h2>
            <form id="addListForm" method="POST" action="index.php">
                <div class="inputs">
                    <input type="text" name="listName" placeholder="Nom de la liste" class="premiers" required>
                    <input type="hidden" name="add_list" value="1">
                    <button class="deux">Add</button>
                </div>

                <!-- Sélection des couleurs -->
                <div class="colors">
                    <label>
                        <input type="radio" name="color" value="1" checked>
                        <span class="color-circle" style="background-color: #6f1d1b;"></span>
                    </label>
                    <label>
                        <input type="radio" name="color" value="2">
                        <span class="color-circle" style="background-color: #bb9457;"></span>
                    </label>
                    <label>
                        <input type="radio" name="color" value="3">
                        <span class="color-circle" style="background-color: #432818;"></span>
                    </label>
                    <label>
                        <input type="radio" name="color" value="4">
                        <span class="color-circle" style="background-color: #99582a;"></span>
                    </label>
                    <label>
                        <input type="radio" name="color" value="5">
                        <span class="color-circle" style="background-color: #ffe6a7;"></span>
                    </label>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal pour gérer les listes -->
    <div id="manageListModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Gérer les listes</h2>
            <!-- Contenu pour gérer les listes -->
            <form id="manageListForm" method="POST" action="manage.php">
                <div class="inputs">
                    <input type="text" name="newListName" placeholder="Renommer la liste" class="premiers" required>
                    <input type="hidden" name="manage_list" value="1">
                    <button class="deux">Update</button>
                </div>
            </form>
        </div>
    </div>

    <section class="formulaire">
        <div class="icons">
            <a class="open-list-modal" href="#"><i class='bx bxs-circle'></i></a>
            <a class="open-manage-modal" href="#"><i class="fas fa-cog"></i></a>
        </div>
        <form action="index.php" method="POST">
            <div class="input">
                <input type="text" id="tache" name="tache" class="premier" placeholder="Entrez une tâche pour la journée" required>
                <input type="hidden" name="add_task" value="1">
                <button class="deux">Add</button>
            </div>
        </form>
    </section>

    <!-- JavaScript pour gérer les modals -->
    <script>
        function setupModal(modalId, openButtonClass, closeButtonClass) {
            var modal = document.getElementById(modalId);
            var openModalBtn = document.querySelector(openButtonClass);
            var closeModalBtn = modal.querySelector(closeButtonClass);

            // Ouvrir le modal
            openModalBtn.addEventListener("click", function(event) {
                event.preventDefault();
                modal.style.display = "block";
            });

            // Fermer le modal
            closeModalBtn.addEventListener("click", function() {
                modal.style.display = "none";
            });

            // Fermer le modal en cliquant en dehors du contenu
            window.addEventListener("click", function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            });
        }

        // Configuration des deux modals
        setupModal("addListModal", ".open-list-modal", ".close");
        setupModal("manageListModal", ".open-manage-modal", ".close");
        setupModal("manageListModal", ".open-manage-modal-nav", ".close");
    </script>

</body>
</html>