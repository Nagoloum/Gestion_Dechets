<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "alineEcoPro";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

?>