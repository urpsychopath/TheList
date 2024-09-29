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
        <title>TheList.</title>
    </head>

    <body>
        <nav>
            <div class="navbar1">
                <ul>  
                    <li class="top"><a href="#">TheList.</a></li>
                    <li><span class="circle"></span> <a class="open-manage-modal-nav" href="#">Mes Listes</a></li>
                </ul>
            </div>  
        </nav>

        <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        
        // Inclure le fichier de connexion
        include 'connexion.php';

        // Traitement du formulaire d'ajout de tâche
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_task'])) {
            if (!empty($_POST['tache'])) {
                $description = $_POST['tache'];
                $idliste = 1;  // Exemple: l'ID de la liste (peut être modifié dynamiquement)

                // Préparer la requête pour insérer la tâche
                $sql = "INSERT INTO tache (description, idliste) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);

                if ($stmt === false) {
                    echo "Erreur lors de la préparation de la requête : " . $conn->error;
                    exit();
                }

                $stmt->bind_param("si", $description, $idliste);

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

                // Préparer la requête pour insérer la liste
                $sql = "INSERT INTO liste (nom, idcolo) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);

                if ($stmt === false) {
                    echo "Erreur lors de la préparation de la requête : " . $conn->error;
                    exit();
                }

                $stmt->bind_param("si", $liste, $id_colo);

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

        // Récupérer les idcolo déjà utilisés dans la table 'liste'
        $sql_used_colors = "SELECT idcolo FROM liste";
        $result_used_colors = $conn->query($sql_used_colors);

        $used_colors = array();
        if ($result_used_colors->num_rows > 0) {
            while ($row = $result_used_colors->fetch_assoc()) {
                $used_colors[] = $row['idcolo'];
            }
        }

        // Préparer la récupération des listes
        $sql_liste = "SELECT liste.nom, colo.nom AS color_name, colo.id AS color_id FROM liste JOIN colo ON liste.idcolo = colo.id";
        $result_liste = $conn->query($sql_liste);
        
        $lists = [];
        if ($result_liste->num_rows > 0) {
            while ($row = $result_liste->fetch_assoc()) {
                $lists[] = $row;
            }
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete') {
            if (isset($_POST['list_id'])) {
                $listId = $_POST['list_id'];  // Récupérer l'ID de la liste à supprimer
        
                // Préparer et exécuter la requête SQL pour supprimer la liste
                $sql = "DELETE FROM liste WHERE idcolo = ?";
                $stmt = $conn->prepare($sql);
        
                if ($stmt === false) {
                    echo "Erreur lors de la préparation de la requête : " . $conn->error;
                    exit();
                }
        
                $stmt->bind_param("i", $listId);
        
                if ($stmt->execute()) {
                    echo "Liste supprimée avec succès!";
                } else {
                    echo "Erreur lors de la suppression de la liste : " . $stmt->error;
                }
        
                $stmt->close();
            } else {
                echo "ID de la liste non fourni.";
            }
        }
        
        // Récupérer les idcolo déjà utilisés dans la table 'liste'
        $sql_used_colors = "SELECT idcolo FROM liste";
        $result_used_colors = $conn->query($sql_used_colors);
        
        $used_colors = array();
        if ($result_used_colors->num_rows > 0) {
            while ($row = $result_used_colors->fetch_assoc()) {
                $used_colors[] = $row['idcolo'];
            }
        }
        
        // Préparer la récupération des listes
        $sql_liste = "SELECT liste.nom, colo.nom AS color_name, colo.id AS color_id FROM liste JOIN colo ON liste.idcolo = colo.id";
        $result_liste = $conn->query($sql_liste);
        
        $lists = [];
        if ($result_liste->num_rows > 0) {
            while ($row = $result_liste->fetch_assoc()) {
                $lists[] = $row;
            }
        }
        
        // Fermer la connexion
        $conn->close();

        ?>

         <!-- Modal pour gérer les listes -->
         <div id="manageListModal" class="modal" style="display: none;">
            <div class="modal-contents">
                <span class="close">&times;</span>
                <h2>Vos listes</h2>
                <ul>
                <?php foreach ($lists as $list): ?>
    <li style="display: flex; align-items: center; margin-bottom: 20px;">
        <span class="color-circle" style="background-color: <?php echo $list['color_name']; ?>; width: 35px; height: 35px; border-radius: 50%; margin-left: 30px;"></span>
        <span style="margin-left: 10px; font-size: 24px; margin-bottom:10px"><?php echo $list['nom']; ?></span> <!-- Espace et taille de police ajoutés -->
        <form method="POST" action="index.php" style="margin-left: auto;">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="list_id" value="<?php echo $list['color_id']; ?>"> <!-- ID correct de la liste -->
            <button type="submit" style="border: none; background: none; cursor: pointer;">
                <i class="fas fa-trash" style="margin-right: 40px;"></i>
            </button>
        </form>
    </li>
<?php endforeach; ?>

                </ul>
                <div class="exa">
                <button class="new-list-btn">New List</button>
                </div>
                
            </div>
        </div>

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
                        <?php
                        $colors = [
                            1 => "#6f1d1b",
                            2 => "#bb9457",
                            3 => "#432818",
                            4 => "#99582a",
                            5 => "#ffe6a7"
                        ];

                        $availableColors = false;

                        foreach ($colors as $id => $colorCode) {
                            if (!in_array($id, $used_colors)) {
                                $availableColors = true;
                                echo '
                                    <label>
                                    <input type="radio" name="color" value="' . $id . '" ' . ($id == 1 ? 'checked' : '') . '>
                                    <span class="color-circle" style="background-color: ' . $colorCode . ';"></span>
                                    </label>
                                ';
                            }
                        }

                        // Si aucune couleur n'est disponible
                        if (!$availableColors) {
                            echo "Aucune couleur disponible.";
                        }
                        ?>
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
        <input type="hidden" name="idliste" id="idliste" value=""> <!-- Champ caché pour l'ID de la liste sélectionnée -->
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

                openModalBtn.addEventListener("click", function(event) {
                    event.preventDefault();
                    modal.style.display = "block";
                });

                closeModalBtn.addEventListener("click", function() {
                    modal.style.display = "none";
                });

                window.addEventListener("click", function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                });
            }

            setupModal('addListModal', '.new-list-btn', '.close');
            setupModal('addListModal', '.open-list-modal', '.close');
            setupModal('manageListModal', '.open-manage-modal', '.close');
        </script>
    </body>
</html>
