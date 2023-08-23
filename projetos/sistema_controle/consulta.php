<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_esp32";

$valor = $_GET['valor'];
$dataInicio = $_GET['data_inicio'];
$dataFim = $_GET['data_fim'];

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta SQL
$query = "SELECT data_hora, I2 FROM dados_esp32 WHERE data_hora = '$valor' AND data_hora BETWEEN '$dataInicio' AND '$dataFim'";
$result = $conn->query($query);

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = array(
        'data_hora' => $row['data_hora'],
        'I2' => $row['I2']
    );
}

// Fechar a conexão com o banco de dados
$conn->close();

// Transformar o resultado em JSON
echo json_encode($data);
?>
