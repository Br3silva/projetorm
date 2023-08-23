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
                    data: <?php 
                    require_once "atualizar_dados.php";
                    echo json_encode($row); ?>
                }]
            });
        });
    </script>
</body>
</html>
