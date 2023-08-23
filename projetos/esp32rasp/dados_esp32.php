<?php

require_once "conexao.php";

if (isset($_GET['I2']) && isset($_GET['I4'])) {
    $valorI2 = $_GET['I2'];
    $valorI4 = $_GET['I4'];
  $umidade = $_GET['umidade'];
    $temperatura = $_GET['temperatura']; 

    print_r($_GET);

    // Query para inserir os valores na tabela

    $sql =  "INSERT INTO dados_esp32 (i2, i4, temperatura, humidade) VALUES ($valorI2, $valorI4, $temperatura, $umidade)";
    

    if ($conn->query($sql) === TRUE) {
        echo "Valores recebidos e inseridos com sucesso na tabela.";
    } else {
        echo "Erro na inserção dos valores na tabela: " . $conn->error;
        
    print_r($_GET);
    var_dump($_GET);
    }

    // Fechamento da conexão
    $conn->close();
} else {
    echo "Erro: Parâmetros não recebidos corretamente.";
    
    print_r($_GET);
    var_dump($_GET);
}
?>
