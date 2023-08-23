<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_esp32";

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>
