<?php
// Dados de conexão com o banco de dados (substitua pelos seus dados)
//rasp
/* $servername = "localhost";
$username = "root";
$password = "rasp";
$dbname = "esp32"; */

// notebook
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_esp32";

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Definir o conjunto de caracteres para UTF-8 (opcional)
$conn->set_charset("utf8");
?>
