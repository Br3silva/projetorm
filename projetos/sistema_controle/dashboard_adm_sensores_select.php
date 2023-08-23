<?php
session_start();
require('conexao.php');
// Verificar se o usuário está logado, caso contrário, redirecionar para a página de login
if (!isset($_SESSION['email']) || !isset($_SESSION['nivel_acesso'])) {
    header("Location: login.php");
    exit();}

// Atualização do status e nível de acesso
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user_id'])) {
        $userId = $_POST['user_id'];

        // Atualizar status, se disponível
        if (isset($_POST['new_status'])) {
            $newStatus = $_POST['new_status'];
            $updateStatusQuery = "UPDATE Usuarios SET status = ? WHERE id = ?";
            $stmt = $conn->prepare($updateStatusQuery);
            $stmt->bind_param("si", $newStatus, $userId);
            $stmt->execute();
            $stmt->close();
            // Redirecionar para a seção de edição após atualizar o status
            header("Location: #editButton");
        }

        // Atualizar nível de acesso, se disponível
        if (isset($_POST['new_nivel_acesso'])) {
            $newNivelAcesso = $_POST['new_nivel_acesso'];
            $updateNivelAcessoQuery = "UPDATE Usuarios SET nivel_acesso = ? WHERE id = ?";
            $stmt = $conn->prepare($updateNivelAcessoQuery);
            $stmt->bind_param("si", $newNivelAcesso, $userId);
            $stmt->execute();
            $stmt->close();
            // Redirecionar para a seção de edição após atualizar o nível de acesso
            header("Location: #editButton");
        }
    }
}

// Função para sair
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php"); // Redirecionar para a página de login após sair
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    
    <title>Dashboard do Administrador</title>
    <!-- Inclua os arquivos do Bootstrap (CSS) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Estilo para a barra de menu lateral */
        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #333;
            display: block;
        }
        .sidebar a:hover {
            background-color: #e9ecef;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
        }
        .user-list {
            display: Block; /* Inicialmente oculta */
        }
        .user-sensor {
            display: none; /* Inicia a div oculta por padrão */
        }

         /* Estilos para as janelas de informações */
         .info-window {
            background: linear-gradient(135deg, #2980b9, #3498db);
            border-radius: 10px;
            padding: 20px;
            color: white;
            margin-bottom: 10px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
        }

        
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Menu</h4>
        <p>Bem-vindo, <?php echo $_SESSION['email']; ?></p>
        <a href="dashboard_adm_editar.php" id="editButton">Editar</a>
        <a href="javascript:void(0);" id="accessControlButton">Controle de Acesso</a>
        <a href="dashboard_adm_sensores.php" id="sensorArea">Sensores Area</a>
        <a href="?logout=true">Sair</a>
    </div>
    <div class="content">
        <h1>Dashboard do Administrador</h1>
    <div class="container mt-4">
        <h1>Histórico</h1>
        <?php
        // Conexão com o banco de dados
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "db_esp32";

        $conn = mysqli_connect($servername, $username, $password, $dbname);
        if (!$conn) {
            die("Falha na conexão: " . mysqli_connect_error());
        }

        // Captura os valores do intervalo de datas e coluna da URL
        $start_date = isset($_POST["start_date"]) ? $_POST["start_date"] : date("Y-m-d", strtotime("-1 week"));
        $end_date = isset($_POST["end_date"]) ? $_POST["end_date"] : date("Y-m-d");
        $selected_column = isset($_GET["valor"]) ? $_GET["valor"] : "I2";

        // Recupera nomes das colunas da tabela
        $columns_query = "SHOW COLUMNS FROM `dados_esp32`";
        $columns_result = mysqli_query($conn, $columns_query);

        $columns = [];
        while ($row = mysqli_fetch_assoc($columns_result)) {
            if ($row["Field"] !== "data_hora" && $row["Field"] !== "id") {
                $columns[] = $row["Field"];
            }
        }

        // Consulta SQL para obter os dados
        $query = "SELECT `$selected_column`, `data_hora` FROM `dados_esp32` WHERE `data_hora` BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59' ORDER BY `data_hora`";
        $result = mysqli_query($conn, $query);

        // Preparando dados para o gráfico
        $labels = [];
        $values = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $labels[] = $row["data_hora"];
            $values[] = $row[$selected_column];
        }
        ?>
        <div class="container mt-4">
           <!-- Formulário para seleção de datas e coluna -->
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
                    <!-- Dropdown de seleção de coluna -->
                    <select id="selected_column" name="selected_column" class="form-control">
                        <?php foreach ($columns as $column) : ?>
                            <option value="<?php echo $column; ?>" <?php if ($selected_column === $column) echo "selected"; ?>><?php echo $column; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Atualizar Gráfico</button>
            </form>

            <!-- Local para exibir o gráfico -->
            <div class="mt-4">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>

    <?php
    // Fechando a conexão com o banco de dados
    mysqli_close($conn);
    ?>

    <!-- Modal do Gráfico -->
    <div class="modal fade" id="chartModal" tabindex="-1" role="dialog" aria-labelledby="chartModalLabel" aria-hidden="true">
        <!-- Conteúdo do modal -->
    </div>

    <script>
        // Criação do gráfico principal
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

        // Atualiza o gráfico a cada 5 segundos
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

        // Atualiza o gráfico modal quando mostrado
        $('#chartModal').on('show.bs.modal', function() {
            modalChart.data.labels = myChart.data.labels;
            modalChart.data.datasets[0].data = myChart.data.datasets[0].data;
            modalChart.options.scales.y.title.text = myChart.options.scales.y.title.text;
            modalChart.update();
        });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
</body>
</html>


























<!DOCTYPE html>
<html>

<head>
    <title>Gráfico Dinâmico com AJAX e Chart.js</title>
    <!-- Links para bibliotecas externas -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
   
</body>

</html>