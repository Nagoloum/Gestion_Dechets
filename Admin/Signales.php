<?php

$inactive_duration = 3600;

ini_set('session.gc_maxlifetime', $inactive_duration);

session_set_cookie_params($inactive_duration);

session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $inactive_duration)) {
    session_unset();
    session_destroy();
    header("location:index.php");
}

$_SESSION['LAST_ACTIVITY'] = time();

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="apple-touch-icon" sizes="180x180" href="../vendor/img/ico/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="../vendor/img/ico/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="../vendor/img/ico/favicon-16x16.png" />

    <title>Admin - Déchets Signalés</title>

    <?php include "tete.php"; ?>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Déchets Signalés</h1>
    </div>

    <?php include "card.php"; ?>

    <div class="container-fluid">

        <div class="card shadow mb-4">

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type de Déchet</th>
                                <th>Quantité</th>
                                <th>Description</th>
                                <th>Localisation</th>
                                <th>Photo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "./conn.php";

                            $sql = "SELECT DISTINCT d.id_dechets, d.type_de_dechets, d.quantite, d.descriptions, 
                                   d.location, d.photo_path, r.statut 
                                   FROM dechets d 
                                   INNER JOIN signaler s ON d.id_dechets = s.id_dechets
                                   LEFT JOIN recuperer r ON d.id_dechets = r.id_dechets";

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id_dechets'] . "</td>";
                                    echo "<td>" . $row['type_de_dechets'] . "</td>";
                                    echo "<td>" . $row['quantite'] . "</td>";
                                    echo "<td>" . $row['descriptions'] . "</td>";
                                    echo "<td>" . $row['location'] . "</td>";
                                    echo "<td><img src='../" . $row['photo_path'] . "' alt='Photo du déchet' width='100'></td>";
                                    echo "<td>
                                           <button class='btn btn-info btn-sm' onclick='showMap(\"" . $row['location'] . "\")'>
                                               👁 Voir sur la carte
                                           </button>";

                                    // Vérifier si l'état est NULL (en attente), afficher le bouton "Attribuer la tâche"
                                    if (empty($row['statut'])) {
                                        echo " <button class='btn btn-success btn-sm' onclick='showAssignModal(" . $row['id_dechets'] . ")'>
                                                   📌 Attribuer la tâche
                                               </button>";
                                    }

                                    echo "</td></tr>";
                                }
                            }

                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MODAL POUR AFFICHER LA MAP -->
            <div id="mapModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('mapModal')">&times;</span>
                    <h2>Localisation du déchet</h2>
                    <div id="map" style="width:100%; height:400px;"></div>
                </div>
            </div>

            <!-- MODAL POUR ATTRIBUER LA TÂCHE -->
            <div id="assignModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal('assignModal')">&times;</span>
                    <h2>Attribuer un agent</h2>
                    <form id="assignForm" method="POST" action="assign_task.php">
                        <input type="hidden" id="id_dechet" name="id_dechet">
                        <label for="agent">Sélectionner un agent :</label>
                        <select name="id_agent" id="agent" class="form-control">
                            <?php
                            include "./conn.php";
                            $sql_agents = "SELECT id_agent, nom, email FROM agent_collecte WHERE statut = 'libre'";
                            $result_agents = $conn->query($sql_agents);
                            while ($agent = $result_agents->fetch_assoc()) {
                                echo "<option value='" . $agent['id_agent'] . "'>" . $agent['nom'] . " - " . $agent['email'] . "</option>";
                            }
                            ?>
                        </select><br>
                        <button type="submit" class="btn btn-primary">Attribuer</button>
                    </form>
                </div>
            </div>

            <!-- CSS POUR LES MODALS -->
            <style>
                .modal {
                    display: none;
                    position: fixed;
                    z-index: 1;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    overflow: auto;
                    background-color: rgba(0, 0, 0, 0.5);
                }

                .modal-content {
                    background-color: white;
                    margin: 10% auto;
                    padding: 20px;
                    border: 1px solid #888;
                    width: 50%;
                }

                .close {
                    float: right;
                    font-size: 28px;
                    font-weight: bold;
                    cursor: pointer;
                }
            </style>

            <!-- SCRIPT JAVASCRIPT -->
            <script>
                function showMap(location) {
                    document.getElementById('mapModal').style.display = 'block';
                    // Afficher la carte avec Google Maps ou OpenStreetMap
                    document.getElementById('map').innerHTML = "<iframe width='100%' height='400' src='https://www.google.com/maps?q=" + location + "&output=embed'></iframe>";
                }

                function showAssignModal(id_dechets) {
                    document.getElementById('assignModal').style.display = 'block';
                    document.getElementById('id_dechet').value = id_dechets;
                }

                function closeModal(modalId) {
                    document.getElementById(modalId).style.display = 'none';
                }
            </script>

        </div>

    </div>
    </div>

    </div>

    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="js/sb-admin-2.min.js"></script>

    <script src="vendor/dataTables/jquery.dataTables.min.js"></script>
    <script src="vendor/dataTables/dataTables.bootstrap4.min.js"></script>

    <script src="js/demo/dataTables-demo.js"></script>
    <script src="../sweetalert/sweetalert2.all.min.js"></script>

    </body>

</html>