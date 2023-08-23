<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_esp32";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}

$start_date = isset($_GET["start_date"]) ? $_GET["start_date"] : date("Y-m-d", strtotime("-1 week"));
$end_date = isset($_GET["end_date"]) ? $_GET["end_date"] : date("Y-m-d");
$selected_column = isset($_GET["selected_column"]) ? $_GET["selected_column"] : "I2";

$query = "SELECT `$selected_column`, `data_hora` FROM `dados_esp32` WHERE `data_hora` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59' ORDER BY `data_hora`";
$result = mysqli_query($conn, $query);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array(
        "data_hora" => $row["data_hora"],
        "value" => $row[$selected_column]
    );
}

mysqli_close($conn);

echo json_encode($data);
?>
