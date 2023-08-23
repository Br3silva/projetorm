<!DOCTYPE html>
<html>
<head>
    <title>Historiograma</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div style="width: 80%; margin: 0 auto;">
        <canvas id="histogramChart"></canvas>
    </div>

    <script>
        <?php
        
            // Fazendo a conexão com o banco de dados MySQL
            require_once "conexao.php";

            // Verificando a conexão
            if ($conn->connect_error) {
                die("Falha na conexão: " . $conn->connect_error);
            }

            // Realizando a consulta no banco de dados
            $sql = "SELECT * FROM dados_esp32";
            $result = $conn->query($sql);

            $dados_do_banco = array();
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $dados_do_banco[] = (int)$row["id"];
                }
            }

            // Fechando a conexão com o banco de dados
            $conn->close();

            // Convertendo os dados em formato JSON para passar ao Javascript
            echo 'var dados = ' . json_encode($dados_do_banco) . ';';
        ?>

        // Criar o historiograma usando Chart.js
        var ctx = document.getElementById('histogramChart').getContext('2d');
        var histogramChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dados.map(function(value, index) { return index + 1; }), // Rótulos para cada valor
                datasets: [{
                    label: 'Frequência',
                    data: dados,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)', // Cor das barras
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
