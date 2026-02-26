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

$_SESSION['LAST_ACTIVITY'] = time(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet" />

  <title>Effectuer mes Taches</title>

  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Additional CSS Files -->
  <link rel="stylesheet" href="assets/css/fontawesome.css" />
  <link rel="stylesheet" href="assets/css/templatemo-scholar.css" />
  <link rel="stylesheet" href="assets/css/owl.css" />
  <link rel="stylesheet" href="assets/css/animate.css" />
  <link rel="stylesheet" href="assets/css/swiper.min.css" />
  <link
    rel="apple-touch-icon"
    sizes="180x180"
    href="../vendor/img/ico/apple-touch-icon.png" />
  <link
    rel="icon"
    type="image/png"
    sizes="32x32"
    href="../vendor/img/ico/favicon-32x32.png" />
  <link
    rel="icon"
    type="image/png"
    sizes="16x16"
    href="../vendor/img/ico/favicon-16x16.png" />
</head>

<style>
  .dechet-card {
    margin: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
  }

  .modal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #57c08f;
    padding: 20px;
    border-radius: 8px;
    z-index: 1000;
    width: 50%;
    height: 50%;
  }

  .modal.active {
    display: block;
  }

  .overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
  }

  .overlay.active {
    display: block;
  }

  /* Cache l'input natif */
  input[type="file"] {
    display: none;
  }

  /* Conteneur en flex pour alignement horizontal */
  fieldset {
    display: flex;
    align-items: center;
    gap: 15px;
    /* Espace entre les éléments */
    border: none;
    padding: 0;
  }

  /* Style du bouton personnalisé */
  .custom-file-upload {
    background-color: #57c08f;
    /* Vert éco */
    color: white;
    padding: 10px 20px;
    border: 2px solid #ccc;
    /* Bordure grise */
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background 0.3s, border 0.3s;
    white-space: nowrap;
  }

  .custom-file-upload:hover {
    background-color: #4aae80;
    border-color: #bbb;
  }

  /* Style du nom du fichier */
  #file-name {
    font-size: 14px;
    color: #ffffff;
  }

  /* Conteneur pour le styliser proprement */
  .custom-select-container {
    position: relative;
    width: 440px;
    /* Ajuste la largeur selon tes besoins */
  }

  /* Stylisation du select */
  select {
    appearance: none;
    /* Supprime le style natif du navigateur */
    width: 100%;
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    color: white;
    background-color: #57c08f;
    /* Vert éco */
    border: 2px solid #ccc;
    /* Bordure grise */
    border-radius: 5px;
    cursor: pointer;
    outline: none;
    transition: background 0.3s, border 0.3s;
  }

  /* Effet au survol */
  select:hover {
    background-color: #4aae80;
    border-color: #bbb;
  }

  /* Effet au focus */
  select:focus {
    border-color: #aaa;
  }

  /* Ajout d'un petit icône flèche vers le bas */
  .custom-select-container::after {
    content: "▼";
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    color: white;
    pointer-events: none;
  }

  .location-container {
    display: flex;
    align-items: center;
    position: relative;
    width: 100%;
  }

  /* Style de l'input */
  #location {
    flex: 1;
    padding: 12px;
    font-size: 16px;
    border: 2px solid gray;
    border-radius: 25px;
    /* Bordures arrondies */
    outline: none;
  }

  .location-btn:hover {
    background-color: #218838;
  }

  .button-group {
    display: flex;
    gap: 10px;
    /* Espace entre les boutons */
  }

  .button-group .btn {
    flex: 1;
    /* Permet aux boutons de prendre la même largeur */
    white-space: nowrap;
    /* Évite le retour à la ligne du texte */
  }

  #dechets-list {
    max-height: 400px;
    /* Ajuste la hauteur selon tes besoins */
    overflow-y: auto;
    scrollbar-width: thin;
    /* Pour Firefox */
    scrollbar-color: #fff rgba(241, 241, 241, 0);
    /* Couleurs de la barre */
  }

  /* Personnalisation de la scrollbar pour Chrome, Edge et Safari */
  #dechets-list::-webkit-scrollbar {
    width: 8px;
  }

  #dechets-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }

  #dechets-list::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
  }

  #dechets-list::-webkit-scrollbar-thumb:hover {
    background: #555;
  }
</style>

<body>
  <!-- ***** Preloader Start ***** -->
  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
  </div>
  <!-- ***** Preloader End ***** -->

  <!-- ***** Header Area Start ***** -->
  <header class="header-area header-sticky">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <!-- ***** Logo Start ***** -->
            <a href="index.php" class="logo">
              <p
                style="
                    font-size: 40px;
                    font-weight: bold;
                    color: white;
                    border-right: 2px solid white;
                    padding-right: 150px;
                    margin-bottom: 10px;
                    width: 380px;
                    display: flex;
                    align-items: center;
                    gap: 15px;
                  ">
                <img
                  style="width: 40%; height: auto"
                  src="./vendor/img/logo.png"
                  alt="Logo" />
                Aline EcoPro
              </p>
            </a>
            <!-- ***** Logo End ***** -->

            <!-- ***** Logout Button ***** -->
            <a href="deconnexion.php" class="logout-icon" style="color: white; font-size: 24px; text-decoration: none;margin-left:45%;">
              <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <div class="headd">
    <div class="container">
      <div class="col-lg-12"></div>
    </div>
  </div>
  <!-- ***** Header Area End ***** -->

  <div class="contact-us section">
    <div class="container" style="margin-top: 7%">
      <div class="row">
        <div class="col-lg-4 align-self-center">
          <div class="section-heading">
            <h5 style="color: rgb(24, 161, 65)">
              Liste des Taches a Accomplir
            </h5>
            <h2>
              Remplissez votre devoir afin de garder votre environnement
              propre pour participer au développement durable !
            </h2>
          </div>
        </div>
        <?php
        include "./conn.php";
        $id_agent = $_SESSION['agent_id'];

        $sql = "SELECT d.id_dechets, d.type_de_dechets, d.photo_path, d.location, d.descriptions
        FROM dechets d 
        INNER JOIN recuperer r ON d.id_dechets = r.id_dechets 
        WHERE r.id_agent = ? AND r.statut = 'Attribué'";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_agent);
        $stmt->execute();
        $result = $stmt->get_result();
        ?>

        <div class="col-lg-8">
          <div class="contact-us-content">
            <h3 style="color: #ffffff; margin-bottom: 3%; text-align: center">
              Déchets signalés
            </h3>

            <div class="container">
              <?php if ($result->num_rows > 0): ?>
                <div class="list-group" id="dechets-list">
                  <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="dechet-card">
                      <img src="../<?= $row['photo_path'] ?>" alt="Déchet" width="100" height="300" style="border-radius: 8px;" />
                      <h5 style="color: #ffffff; margin-top: 3%">Type : <?= $row['type_de_dechets'] ?></h5>
                      <h6 style="color: #ffffff; margin-top: 1%; margin-bottom: 2%;">Description : <?= $row['descriptions'] ?></h6>
                      <div class="button-group">
                        <button class="btn btn-success" onclick="openConfirmModal(<?= $row['id_dechets'] ?>)">✅ Terminé</button>
                        <button class="btn btn-info" onclick="openMapModal('<?= $row['location'] ?>')">👀 Voir sur la carte</button>
                      </div>
                    </div>
                  <?php endwhile; ?>
                </div>
              <?php else: ?>
                <p style="color: white; text-align: center;">🚧 Aucune tâche ne vous a été attribuée. 🚧</p>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <!-- Modal de confirmation -->
        <div id="confirm-modal" class="modal">
          <h4 style="color: #ffffff">Confirmer la collecte</h4>
          <form method="post" action="Terminer_tache.php" enctype="multipart/form-data">
            <input type="hidden" name="id_dechets" id="confirm-id-dechets">
            <div class="col-lg-12">
              <fieldset style="margin-top: 20px">
                <label for="file" class="custom-file-upload">Choisir une photo</label>
                <input type="file" name="file" id="file" accept="image/*" required onchange="updateFileName()" />
                <span id="file-name">Aucun fichier sélectionné</span>
              </fieldset>
            </div>
            <button class="btn btn-primary" type="submit" style="margin-top: 3%">Confirmer</button>
            <button class="btn btn-danger" style="margin-top: 3%" onclick="closeModal('confirm-modal')">Fermer</button>
          </form>
        </div>

        <!-- Modal de localisation -->
        <div id="map-modal" class="modal">
          <h4 style="color: #ffffff;margin-bottom: 3%;">Localisation du déchet</h4>
          <div id="map-container" style="width: 620px; height: 300px; background: #ddd;">
            <iframe id="map-iframe" width="620" height="300" allowfullscreen="" loading="lazy"></iframe>
          </div>
          <button class="btn btn-danger" style="margin-top: 3%" onclick="closeModal('map-modal')">Fermer</button>
        </div>

        <div class="overlay" id="overlay" onclick="closeAllModals()"></div>

        <script>
          function openConfirmModal(id) {
            document.getElementById("confirm-id-dechets").value = id;
            document.getElementById("confirm-modal").classList.add("active");
            document.getElementById("overlay").classList.add("active");
          }

          function openMapModal(location) {
            const mapUrl = `https://www.google.com/maps?q=${location}&output=embed`;
            document.getElementById("map-iframe").src = mapUrl;
            document.getElementById("map-modal").classList.add("active");
            document.getElementById("overlay").classList.add("active");
          }

          function closeModal(modalId) {
            document.getElementById(modalId).classList.remove("active");
            document.getElementById("overlay").classList.remove("active");
          }

          function closeAllModals() {
            document.querySelectorAll(".modal").forEach(modal => modal.classList.remove("active"));
            document.getElementById("overlay").classList.remove("active");
          }

          function updateFileName() {
            const input = document.getElementById("file");
            const fileName = input.files.length > 0 ? input.files[0].name : "Aucun fichier sélectionné";
            document.getElementById("file-name").textContent = fileName;
          }
        </script>
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/isotope.min.js"></script>
        <script src="assets/js/owl-carousel.js"></script>
        <script src="assets/js/counter.js"></script>
        <script src="assets/js/custom.js"></script>
</body>

</html>