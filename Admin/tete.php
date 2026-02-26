<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
<link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

<link href="css/sb-admin-2.min.css" rel="stylesheet">
<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<link rel="stylesheet" href="../sweetalert/sweetalert2.min.css">
<link rel="apple-touch-icon" sizes="180x180" href="../vendor/img/ico/apple-touch-icon.png" />
<link rel="icon" type="image/png" sizes="32x32" href="../vendor/img/ico/favicon-32x32.png" />
<link rel="icon" type="image/png" sizes="16x16" href="../vendor/img/ico/favicon-16x16.png" />



</head>

<body id="page-top" onload="disablePastDates()">

    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-success sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="admin.php">
                <div class="sidebar-brand-icon">
                    <img
                        style="width: 50%; height: auto"
                        src="./vendor/img/logo.png"
                        alt="" />
                </div>
                <div class="sidebar-brand-text mx-3">Admin</div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item active">
                <a class="nav-link" href="admin.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Tableau de bord</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="Users.php">
                    <i class="fas fa-solid fa-user"></i>
                    <span>Usagers</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Agents.php">
                    <i class="fas fa-solid fa-users"></i>
                    <span>Agents de collectes</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Signales.php">
                    <i class="fas fa-dumpster"></i>
                    <span>Dechets signalés</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="Recupere.php">
                    <i class="fas fa-dumpster-fire"></i>
                    <span>Dechets récupérés</span>
                </a>
            </li>

            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php
                                    if (isset($_SESSION['admin_name'])) {
                                        echo $_SESSION['admin_name'];
                                    } else {
                                        session_unset();

                                        session_destroy();

                                        header('location: index.php');
                                        exit();
                                    }
                                    include "./conn.php";

                                    $login = $_SESSION['admin_name'];

                                    $conn->close();
                                    ?>
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="vendor/img/logo.png">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#profil">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profil
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Parametres
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="deconnexion.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Se deconnecter
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>