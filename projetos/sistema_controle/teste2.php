<!DOCTYPE html>
<html>
<head>
    <title>Gráfico Dinâmico com AJAX e Chart.js</title>
    <!-- Adicione o link para o CSS do Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Adicione o link para o jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Adicione o link para o JS do Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_esp32";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}

$start_date = isset($_POST["start_date"]) ? $_POST["start_date"] : date("Y-m-d", strtotime("-1 week"));
$end_date = isset($_POST["end_date"]) ? $_POST["end_date"] : date("Y-m-d");
$selected_column = isset($_POST["selected_column"]) ? $_POST["selected_column"] : "I2";

$columns_query = "SHOW COLUMNS FROM `dados_esp32`";
$columns_result = mysqli_query($conn, $columns_query);

$columns = [];
while ($row = mysqli_fetch_assoc($columns_result)) {
    if ($row["Field"] !== "data_hora" && $row["Field"] !== "id") {
        $columns[] = $row["Field"];
    }
}

$query = "SELECT `$selected_column`, `data_hora` FROM `dados_esp32` WHERE `data_hora` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59' ORDER BY `data_hora`";
$result = mysqli_query($conn, $query);

$labels = [];
$values = [];

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row["data_hora"];
    $values[] = $row[$selected_column];
}

mysqli_close($conn);
?>

<div class="container mt-4">
    <h1>Gráfico Dinâmico com AJAX e Chart.js</h1>

    <form method="post" class="form-inline mb-4">
        <div class="form-group mr-2">
            <label for="start_date">Data de Início:</label>
            <input type="date" id="start_date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
        </div>
        <div class="form-group mr-2">
            <label for="end_date">Data de Término:</label>
            <input type="date" id="end_date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
        </div>
        <div class="form-group mr-2">
            <label for="selected_column">Coluna:</label>
            <select id="selected_column" name="selected_column" class="form-control">
                <?php foreach ($columns as $column): ?>
                    <option value="<?php echo $column; ?>" <?php if ($selected_column === $column) echo "selected"; ?>><?php echo $column; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar Gráfico</button>
    </form>

    <div class="mt-4">
        <canvas id="myChart"></canvas>
    </div>


</div>

<!-- Modal do Gráfico -->
<div class="modal fade" id="chartModal" tabindex="-1" role="dialog" aria-labelledby="chartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chartModalLabel">Gráfico Dinâmico</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <canvas id="modalChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
var ctx = document.getElementById('myChart').getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Valores de ' + '<?php echo $selected_column; ?>',
            data: <?php echo json_encode($values); ?>,
            borderColor: 'blue',
            fill: false
        }]
    }
});

/* function updateChart() {
    $.ajax({
        
        method: 'GET',
        dataType: 'json',
        data: {
            start_date: $('#start_date').val(),
            end_date: $('#end_date').val(),
            selected_column: $('#selected_column').val()
        },
        success: function(data) {
            myChart.data.labels = data.labels;
            myChart.data.datasets[0].data = data.values;
            myChart.options.scales.y.title.text = 'Valores de ' + $('#selected_column').val();
            myChart.update();
            updateCurrentValue(data.currentValue);
        }
    });
}

function updateCurrentValue(value) {
    $('#currentValue').text('Valor Atual: ' + value);
}
 */
setInterval(updateChart, 5000);

// Configuração do gráfico modal
var modalCtx = document.getElementById('modalChart').getContext('2d');
var modalChart = new Chart(modalCtx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: 'Valores de ' + '<?php echo $selected_column; ?>',
            data: [],
            borderColor: 'blue',
            fill: false
        }]
    }
});

$('#chartModal').on('show.bs.modal', function () {
    modalChart.data.labels = myChart.data.labels;
    modalChart.data.datasets[0].data = myChart.data.datasets[0].data;
    modalChart.options.scales.y.title.text = myChart.options.scales.y.title.text;
    modalChart.update();
});
</script>

</body>
</html>
