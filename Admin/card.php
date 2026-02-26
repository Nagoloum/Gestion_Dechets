<?php 
    include "./conn.php"; 

    // Comptages des différents éléments demandés
    $usagers = "SELECT count(*) AS total_usagers FROM usager";
    $agents = "SELECT count(*) AS total_agents FROM agent_collecte";
    $dechets_signales = "SELECT count(*) AS total_signales FROM signaler";
    $dechets_recuperes = "SELECT count(*) AS total_recuperes FROM recuperer WHERE statut ='recupéré'";

    // Exécution des requêtes
    $user_result = $conn->query($usagers);
    $agent_result = $conn->query($agents);
    $signale_result = $conn->query($dechets_signales);
    $recupere_result = $conn->query($dechets_recuperes);

    // Récupération des résultats
    $row_usagers = $user_result->fetch_assoc();
    $row_agents = $agent_result->fetch_assoc();
    $row_signales = $signale_result->fetch_assoc();
    $row_recuperes = $recupere_result->fetch_assoc();

    // Assignation des valeurs aux variables
    $count_usagers = $row_usagers['total_usagers'];
    $count_agents = $row_agents['total_agents'];
    $count_signales = $row_signales['total_signales'];
    $count_recuperes = $row_recuperes['total_recuperes'];
?>

    
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Usagers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $count_usagers; ?> Usagers</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Agents de Collectes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $count_agents; ?> Agents</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Déchets Signalés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo "$count_signales"; ?> Déchets</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dumpster fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Déchets Récupérés</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo "$count_recuperes"; ?> Déchets</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dumpster-fire fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
