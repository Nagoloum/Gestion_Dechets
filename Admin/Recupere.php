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

    <title>Admin - Déchets Récupérés</title>

    <?php include "tete.php"; ?>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Déchets Récupérés</h1>
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
                                <th>Nom Agent Collecte</th>
                                <th>Type de Déchet</th>
                                <th>Description</th>
                                <th>Localisation</th>
                                <th>Photo</th>
                                <th>Statut Récupération</th>
                                <th>Photo Récupération</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include "./conn.php";

                            $sql = "SELECT d.id_dechets, d.type_de_dechets, d.quantite, d.descriptions, 
                               d.location, d.photo_path, a.nom AS agent_nom, r.photo AS photo_recup, r.statut AS statut_recup
                        FROM dechets d 
                        INNER JOIN recuperer r ON d.id_dechets = r.id_dechets 
                        INNER JOIN agent_collecte a ON r.id_agent = a.id_agent 
                        WHERE r.statut = 'recupéré'";

                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $row['id_dechets'] . "</td>";
                                    echo "<td>" . $row['agent_nom'] . "</td>";
                                    echo "<td>" . $row['type_de_dechets'] . "</td>";
                                    echo "<td>" . $row['descriptions'] . "</td>";
                                    echo "<td>" . $row['location'] . "</td>";
                                    echo "<td><img src='../" . $row['photo_path'] . "' alt='Photo du déchet' width='100'></td>";
                                    echo "<td>" . $row['statut_recup'] . "</td>";
                                    echo "<td><img src='../Agent_collecte/" . $row['photo_recup'] . "' alt='Photo récupération' width='100'></td>";
                                    echo "</tr>";
                                }
                            }

                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

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

    </body>

</html>