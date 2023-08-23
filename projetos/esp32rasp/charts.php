<!DOCTYPE html>
<html>
<head>
    <title>Gráfico de Data e Valor</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<title>Leitura do Banco de Dados com Atualização Automática</title>
<body>
    <div style="width: 80%; margin: 0 auto;">
        <canvas id="dataValorChart"></canvas>
    </div>

    <script>
        <?php
         require_once "conexao.php";

            // Consulta SQL para buscar os dados
            $sql = "SELECT data_hora, i2 FROM dados_esp32 ORDER BY data_hora";
            $result = $conn->query($sql);
            print_r($result);

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
                    print_r($dados_do_banco);
                }
            }

            // Fechar a conexão com o banco de dados
            $conn->close();

            // Convertendo os dados para JSON
            $dados_json = json_encode($dados_do_banco);
        ?>

        // Criar o gráfico usando Chart.js
        var dados = <?php echo $dados_json; ?>;
        var ctx = document.getElementById('dataValorChart').getContext('2d');
        var dataValorChart = new Chart(ctx, {
            type: 'line', // Gráfico de linha para exibir a relação entre data e valor
            data: {
                labels: dados.map(function(item) { return item.data_hora; }), // Eixo x com data e hora
                datasets: [{
                    label: 'Valor',
                    data: dados.map(function(item) { return item.valor; }), // Eixo y com os valores
                    borderColor: 'rgba(75, 192, 192, 1)', // Cor da linha
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', // Cor do fundo da área sob a linha
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        type: 'time', // Configurar o eixo x como tipo "time"
                        time: {
                            unit: 'hour', // Unidade para exibir no eixo x (pode ser configurada para 'day', 'week', 'month', etc.)
                            displayFormats: {
                                hour: 'YYYY-MM-DD HH:mm' // Formato de exibição da data e hora
                            }
                        },
                        ticks: {
                            maxRotation: 90, // Rotação máxima dos rótulos do eixo x (ajustar conforme necessário)
                            minRotation: 90
                        }
                    },
                    y: {
                        beginAtZero: true // Iniciar o eixo y em 0
                    }
                }
            }
        });
    </script>
</body>
</html>
