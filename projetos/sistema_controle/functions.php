<?php
function dbConnect() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "db_esp32";

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Falha na conexão: " . mysqli_connect_error());
    }
    return $conn;
}

function fetchColumns() {
    $conn = dbConnect();
    $query = "SHOW COLUMNS FROM `dados_esp32`";
    $result = mysqli_query($conn, $query);
    $columns = [];
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row["Field"] !== "data_hora" && $row["Field"] !== "id") {
            $columns[] = $row["Field"];
        }
    }
    mysqli_close($conn);
    return $columns;
}

function fetchChartData($selectedColumn, $startDate, $endDate) {
    $conn = dbConnect();
    $query = "SELECT `$selectedColumn`, `data_hora` FROM `dados_esp32` WHERE `data_hora` BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ORDER BY `data_hora`";
    $result = mysqli_query($conn, $query);
    $labels = [];
    $values = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $labels[] = $row["data_hora"];
        $values[] = $row[$selectedColumn];
    }
    mysqli_close($conn);
    return ['labels' => $labels, 'values' => $values];
}

function renderFilterForm() {
    $columns = fetchColumns();
    $selectedColumn = isset($_POST["selected_column"]) ? $_POST["selected_column"] : "I2";
    $startDate = isset($_POST["start_date"]) ? $_POST["start_date"] : date("Y-m-d", strtotime("-1 week"));
    $endDate = isset($_POST["end_date"]) ? $_POST["end_date"] : date("Y-m-d");
    
    echo '<form method="post" class="form-inline mb-4">';
    echo '<div class="form-group mr-2">';
    echo '<label for="start_date" class="form-label">Data de Início:</label>';
    echo '<input type="date" id="start_date" name="start_date" class="form-control" value="' . $startDate . '">';
    echo '</div>';
    echo '<div class="form-group mr-2">';
    echo '<label for="end_date" class="form-label">Data de Término:</label>';
    echo '<input type="date" id="end_date" name="end_date" class="form-control" value="' . $endDate . '">';
    echo '</div>';
    echo '<div class="form-group mr-2">';
    echo '<label for="selected_column" class="form-label">Coluna:</label>';
    echo '<select id="selected_column" name="selected_column" class="form-control">';
    foreach ($columns as $column) {
        $selected = $selectedColumn === $column ? 'selected' : '';
        echo '<option value="' . $column . '" ' . $selected . '>' . $column . '</option>';
    }
    echo '</select>';
    echo '</div>';
    echo '<button type="submit" class="btn btn-primary">Atualizar Gráfico</button>';
    echo '</form>';
}

function renderCurrentValuePreview() {
    echo '<div class="mt-4">';
    echo '<div class="card">';
    echo '<div class="card-header">';
    echo 'Pré-visualização do Valor Atual';
    echo '</div>';
    echo '<div class="card-body">';
    echo '<p class="card-text" id="currentValue"></p>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

function renderChartModal() {
    echo '<div class="modal fade" id="chartModal" tabindex="-1" role="dialog" aria-labelledby="chartModalLabel" aria-hidden="true">';
    echo '<div class="modal-dialog modal-lg" role="document">';
    echo '<div class="modal-content">';
    echo '<div class="modal-header">';
    echo '<h5 class="modal-title" id="chartModalLabel">Gráfico Dinâmico</h5>';
    echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>';
    echo '</div>';
    echo '<div class="modal-body">';
    echo '<canvas id="modalChart"></canvas>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

?>
