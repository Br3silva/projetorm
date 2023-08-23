<?php
require_once "conexao.php";

// Query para buscar o último resultado
$sql = "SELECT * FROM dados_esp32 ORDER BY id DESC LIMIT 1";
//$sql = "SELECT * FROM dados_esp32 ";
$result = $conn->query($sql);

// Verificar se encontrou resultado
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Codificar o resultado em JSON e enviar para o frontend
    echo json_encode($row);
} else {
    echo "Nenhum resultado encontrado.";
}

$conn->close();


?>



<!DOCTYPE html>
<html>
<head>
    <title>Gráfico de Valores I2</title>
    <script src="https://code.highcharts.com/highcharts.js"></script>
</head>
<body>
    <div id="grafico"></div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Highcharts.chart('grafico', {
                title: {
                    text: 'Gráfico de Valores I2'
                },
                xAxis: {
                    type: 'datetime'
                },
                yAxis: {
                    title: {
                        text: 'Valores I2'
                    }
                },
                series: [{
                    type: 'line',
                    name: 'Valores I2',
                    data: <?php echo json_encode($row); ?>
                }]
            });
        });
    </script>
</body>
</html>
