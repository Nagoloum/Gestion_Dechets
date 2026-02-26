<?php
include "./conn.php"; // Connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_dechet = intval($_POST["id_dechet"]);
    $id_agent = intval($_POST["id_agent"]);
    $statut = "Attribué";

    // Vérifier si le déchet est déjà attribué
    $check_query = "SELECT * FROM recuperer WHERE id_dechets = ? AND statut = ?";
    $stmt_check = $conn->prepare($check_query);
    $stmt_check->bind_param("is", $id_dechet, $statut);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        echo "<script>alert('⚠️ Ce déchet a déjà été attribué à un agent !'); window.location.href = 'Signales.php';</script>";
        exit();
    }
    $stmt_check->close();

    // Insérer dans la table `recuperer`
    $insert_query = "INSERT INTO recuperer (id_dechets, id_agent, statut) VALUES (?, ?, ?)";
    $stmt_insert = $conn->prepare($insert_query);
    $stmt_insert->bind_param("iis", $id_dechet, $id_agent, $statut);

    if ($stmt_insert->execute()) {
        // Vérifier le nombre de tâches en cours pour cet agent
        $task_query = "SELECT COUNT(*) FROM recuperer WHERE id_agent = ? AND statut = 'Attribué'";
        $stmt_task = $conn->prepare($task_query);
        $stmt_task->bind_param("i", $id_agent);
        $stmt_task->execute();
        $stmt_task->bind_result($task_count);
        $stmt_task->fetch();
        $stmt_task->close();

        // Déterminer le statut de l'agent
        $new_statut = ($task_count >= 5) ? 'occupe' : 'libre';

        // Mettre à jour le statut de l'agent
        $update_query = "UPDATE agent_collecte SET statut = ? WHERE id_agent = ?";
        $stmt_update = $conn->prepare($update_query);
        $stmt_update->bind_param("si", $new_statut, $id_agent);
        $stmt_update->execute();
        $stmt_update->close();

        echo "<script>alert('✅ Tâche attribuée avec succès !'); window.location.href = 'Signales.php';</script>";
    } else {
        echo "<script>alert('❌ Erreur lors de l\'attribution !'); window.location.href = 'Signales.php';</script>";
    }

    $stmt_insert->close();
}

$conn->close();
?>
