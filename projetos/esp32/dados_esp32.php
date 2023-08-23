<?php

require_once "conexao.php";

if (isset($_GET['I2']) && isset($_GET['I4'])) {
    $valorI2 = $_GET['I2'];
    $valorI4 = $_GET['I4'];

    // Query para inserir os valores na tabela
    $sql = "INSERT INTO dados_esp32 (I2, I4) VALUES ($valorI2, $valorI4)";

    if ($conn->query($sql) === TRUE) {
        echo "Valores recebidos e inseridos com sucesso na tabela.";
    } else {
        echo "Erro na inserção dos valores na tabela: " . $conn->error;
    }

    // Fechamento da conexão
    $conn->close();
} else {
    echo "Erro: Parâmetros não recebidos corretamente.";
}
?>
