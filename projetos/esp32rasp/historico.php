<?php

// Conexão com o banco de dados
require_once "conexao.php";

// Consulta SQL para buscar os dados
$sql = "SELECT data_hora, i2 FROM dados_esp32 ORDER BY data_hora";
$result = $conn->query($sql);

// Array para armazenar os dados
$dados_do_banco = array();

// Ler os dados do resultado da consulta e adicionar ao array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Extrair a data e hora
        $data_hora = $row["data_hora"];
        $valor = (int)$row["i2"];

        // Adicionar os dados ao array
        $dados_do_banco[] = array("data_hora" => $data_hora, "valor" => $valor);
    }
}

// Fechar a conexão com o banco de dados
$conn->close();

// Convertendo os dados para JSON e imprimindo na página
echo '<script>var dados = ' . json_encode($dados_do_banco) . ';</script>';
?>
