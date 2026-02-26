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

    <title>Admin - Usagers</title>

    <?php include "tete.php"; ?>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"> Usagers</h1>
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
                                <th>Numero de Telephone_Agence</th>
                            </tr>
                        </thead>
                        <?php
                        include "./conn.php";

                        $sql2 = "SELECT DISTINCT * FROM usager";
                        $result2 = $conn->query($sql2);

                        if ($result2->num_rows > 0) {
                            while ($row = $result2->fetch_assoc()) {
                                echo "<tbody>";
                                echo "<tr>";
                                echo "<td>" . $row['id_usager'] . "</td>";
                                echo "<td>" . $row['numero_telephone'] . "</td>";
                                echo "</tr>";
                                echo "</tbody>";
                            }
                        }

                        $conn->close();
                        ?>
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

    </div>


    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <script src="js/sb-admin-2.min.js"></script>

    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="js/demo/datatables-demo.js"></script>
    <script src="../sweetalert/sweetalert2.all.min.js"></script>

    </body>

</html>