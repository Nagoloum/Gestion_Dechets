<?php
include "conn.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_SESSION['agent_id'])) {
        die("❌ Accès refusé. Veuillez vous connecter.");
    }
    
    $id_agent = $_SESSION['agent_id'];
    
    // Gestion de l'upload de l'image
    if (!empty($_FILES["file"]["name"])) {
        $target_dir = "recuperes/";
        $file_name = time() . "_" . basename($_FILES["file"]["name"]);
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Vérification de l'image
        $check = getimagesize($_FILES["file"]["tmp_name"]);
        if ($check === false) {
            die("❌ Le fichier n'est pas une image.");
        }

        // Vérification du format et de la taille
        if ($_FILES["file"]["size"] > 5000000 || !in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            die("❌ Image invalide (format JPG, JPEG, PNG, GIF uniquement, max 5MB).");
        }

        // Déplacer l'image
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
            $photo_path = $target_file;
        } else {
            die("❌ Erreur lors de l'upload de l'image.");
        }
    } else {
        die("❌ Aucune image sélectionnée.");
    }

    $statut = "recupéré";
    $id_dechets = intval($_POST['id_dechets']);

    // Mettre à jour le statut de la collecte
    $stmt = $conn->prepare("UPDATE recuperer SET photo = ?, statut = ? WHERE id_agent = ? AND id_dechets = ?");
    $stmt->bind_param("ssii", $photo_path, $statut, $id_agent, $id_dechets);

    if ($stmt->execute()) {
        // Vérifier le nombre de tâches restantes de l'agent
        $task_stmt = $conn->prepare("SELECT COUNT(*) FROM recuperer WHERE id_agent = ? AND statut = 'Attribué'");
        $task_stmt->bind_param("i", $id_agent);
        $task_stmt->execute();
        $task_stmt->bind_result($task_count);
        $task_stmt->fetch();
        $task_stmt->close();

        // Mettre à jour le statut de l'agent
        $new_status = ($task_count < 5) ? 'libre' : 'occupe';
        $update_status_stmt = $conn->prepare("UPDATE agent_collecte SET statut = ? WHERE id_agent = ?");
        $update_status_stmt->bind_param("si", $new_status, $id_agent);
        $update_status_stmt->execute();
        $update_status_stmt->close();

        echo "<script>alert('✅ Tâche confirmée avec succès !'); window.location.href = 'Taches.php';</script>";
    } else {
        echo "<script>alert('❌ Erreur lors de la confirmation !'); window.location.href = 'Taches.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
