<?php
include "conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $type_de_dechets = htmlspecialchars($_POST['category']);
    $descriptions = htmlspecialchars($_POST['message']);
    $location = $_POST['location'];
    $tel = htmlspecialchars($_POST['tel']);
    $quantite = 1; 
    
    // Vérification du numéro dans `usager`
    $stmt = $conn->prepare("SELECT id_usager FROM usager WHERE numero_telephone = ?");
    $stmt->bind_param("s", $tel);
    $stmt->execute();
    $stmt->store_result();
 
    if ($stmt->num_rows == 0) {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO usager (numero_telephone) VALUES (?)");
        $stmt->bind_param("s", $tel);
        $stmt->execute();
        $id_usager = $stmt->insert_id;
    } else {
        $stmt->bind_result($id_usager);
        $stmt->fetch();
    }
    $stmt->close();

    // Gestion de l'upload de l'image
    if (!empty($_FILES["file"]["name"])) {
        $target_dir = "uploads/";
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

    // Insertion des données dans la table `dechets`
    $stmt = $conn->prepare("INSERT INTO dechets (type_de_dechets, quantite, descriptions, location, photo_path) 
                            VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $type_de_dechets, $quantite, $descriptions, $location, $photo_path);
    $stmt->execute();
    $id_dechets = $stmt->insert_id;
    $stmt->close();

    // Insertion dans la table `signaler`
    $stmt = $conn->prepare("INSERT INTO signaler (id_usager, id_dechets) VALUES (?, ?)");
    $stmt->bind_param("ii", $id_usager, $id_dechets);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Déchet signalé avec succès !'); window.location.href = 'Signaler.php';</script>";
    } else {
        echo "<script>alert('❌ Erreur lors du signalement !'); window.location.href = 'Signaler.php';</script>";
    }

    $stmt->close();
}
$conn->close();
?>
