<?php
// Conexão com o banco de dados (substitua com suas configurações)

require('conexao copy.php');


if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Consulta SQL para obter os valores da coluna "time" e "banores"
// $sql = "SELECT data_hora, I2 FROM dados_esp32 LIMIT 1"; // Adicione condições ou filtros conforme necessário
// Consulta SQL para obter os valores da coluna "data_hora" e "I2"
$sql = "SELECT data_hora, I2 FROM dados_esp32"; // Adicione condições ou filtros conforme necessário
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $values = array();
    while ($row = $result->fetch_assoc()) {
        $values[] = array(
            "data_hora" => $row["data_hora"],
            "I2" => $row["I2"]
        );
    }
    echo json_encode($values);
} else {
    echo json_encode(array("error" => "Nenhum resultado encontrado."));
}

$conn->close();
?>
