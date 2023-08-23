<!DOCTYPE html>
<html>
<head>
    <title>Exibição de Dados do Banco de Dados</title>
    <!-- Adicione o link para o CSS do Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Adicione o link para o JS do Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_esp32";

// Cria a conexão
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verifica a conexão
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}

$start_date = isset($_POST["start_date"]) ? $_POST["start_date"] : '2023-07-02';
$end_date = isset($_POST["end_date"]) ? $_POST["end_date"] : '2023-08-20';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
}

// Sua consulta
$query = "SELECT `I2`, `data_hora` FROM `dados_esp32` WHERE `data_hora` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'";
$result = mysqli_query($conn, $query);

$dataPoints = []; // Para armazenar os pontos de dados para o gráfico

if ($result) {
    echo "<h2>Dados do período de $start_date a $end_date:</h2>";
    echo "<table class='table table-bordered'>";
    echo "<thead><tr><th>I2</th><th>Data e Hora</th></tr></thead>";
    echo "<tbody>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["I2"] . "</td>";
        echo "<td>" . $row["data_hora"] . "</td>";
        echo "</tr>";

        // Adicione os pontos de dados para o gráfico
        $dataPoints[] = [
            'x' => $row["data_hora"],
            'y' => $row["I2"]
        ];
    }

    echo "</tbody></table>";
} else {
    echo "Erro na consulta: " . mysqli_error($conn);
}

// Fecha a conexão
mysqli_close($conn);
?>

<div class="container mt-4">
    <h1>Exibição de Dados do Banco de Dados</h1>

    <form method="post" class="mb-4">
        <div class="form-group">
            <label for="start_date">Data de Início:</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo $start_date; ?>" required>
        </div>
        <div class="form-group">
            <label for="end_date">Data de Término:</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo $end_date; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Exibir Dados</button>
    </form>

    <div class="mb-4">
        <canvas id="myChart"></canvas>
    </div>
</div>

<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        datasets: [{
            label: 'Valores de I2',
            data: <?php echo json_encode($dataPoints); ?>,
            borderColor: 'blue',
            fill: false
        }]
    },
    options: {
        scales: {
            x: {
                type: 'time',
                time: {
                    unit: 'day'
                },
                title: {
                    display: true,
                    text: 'Data e Hora'
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Valor de I2'
                }
            }
        }
    }
});
</script>

<!-- Adicione o link para o JS do Bootstrap no final do body -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
