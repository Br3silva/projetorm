<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_esp32";

// Conectar ao banco de dados
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}

// Obter os parâmetros da solicitação AJAX
$tableName = $_GET["table"];
$columnName = $_GET["column"];

// Consulta para obter o último valor da coluna da tabela
$query = "SELECT `$columnName` FROM `$tableName` ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $lastValue = $row[$columnName];
} else {
    $lastValue = "N/A";
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);

// Retorna o último valor como resposta JSON
echo json_encode(["lastValue" => $lastValue]);
?>
