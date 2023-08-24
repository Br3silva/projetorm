<?php
include 'functions.php';
session_start();
require('conexao.php');
// Verificar se o usuário está logado, caso contrário, redirecionar para a página de login
if (!isset($_SESSION['email']) || !isset($_SESSION['nivel_acesso'])) {
    header("Location: login.php");
    exit();
}

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
            display: Block;
            /* Inicialmente oculta */
        }

        .user-sensor {
            display: none;
            /* Inicia a div oculta por padrão */
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

        .widget-container {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }

        .widget-title {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .btn-primary {
            background-color: #8e44ad;
            border-color: #8e44ad;
        }

        .btn-primary:hover {
            background-color: #6c3483;
            border-color: #6c3483;
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
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "db_esp32";
            $table = $_POST['table'];
            $email = $_POST['email'];
            $minValue = $_POST['minValue'];
            $maxValue = $_POST['maxValue'];
            $origemdoEvento = $_GET['valor'];


            inserirDadosNoBanco($servername, $username, $password, $dbname, $table, $email, $minValue, $maxValue);
            adicionarLog("alteracao dos setpoint", $origemdoEvento, $email);
        }


        // Captura o valor do parâmetro 'config_id' da URL
        if (isset($_GET['valor'])) {
            $vlrUrl = $_GET['valor'];
        } else {
            echo "ID de configuração não fornecido.";
            exit;
        }

        function getLatestValue($table, $column)
        {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "db_esp32";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            $query = "SELECT $column
              FROM $table
              ORDER BY data_hora DESC
              LIMIT 1";

            $result = $conn->query($query);
            $conn->close();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                return $row[$column];
            } else {
                return "Nenhum valor encontrado.";
            }
        }

        $consulta = consultarConfigTemp($_SESSION['email'], 'configtemp');

        $temp_min = $consulta['temp_min'];
        $temp_max = $consulta['temp_max'];

       /*  echo "Valor Mínimo: $temp_min<br>";
        echo "Valor Máximo: $temp_max<br>";
 */
        $limite = $temp_max; // Limite a ser verificado
        $consulta = contarUltrapassagensTemperatura($limite);

        $total_ultrapassagens = $consulta['total_ultrapassagens'];
        $max_temperatura = $consulta['max_temperatura'];

     /*    echo "Total de Ultrapassagens: $total_ultrapassagens<br>";
        echo "Máxima Temperatura Ultrapassada: $max_temperatura<br>"; */




        $limite = $temp_min; // Limite a ser verificado
        $consulta = contarTemperaturasAbaixoDoLimite($limite);

        $total_abaixo_limite = $consulta['total_abaixo_limite'];
        $min_temperatura = $consulta['min_temperatura'];
/* 
        echo "Total de vezes abaixo do limite: $total_abaixo_limite<br>";
        echo "Mínima Temperatura abaixo do limite: $min_temperatura<br>"; */

        ?>

        <title>Dashboard do Administrador</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


        <div class="container mt-4" id="info-container">
            <!-- Exemplo de uso da função para imprimir o último valor da coluna I2 -->
            <div class="row">
                <div class="col-md-4">
                    <div class="info-window">
                        <h3>Valvula </h3>
                        <p>Status Valvula: <?php echo getLatestValue('dados_esp32', 'I2'); ?></p>
                        <button class="btn btn-light" id="enviarBotao1" data-valor="I2" data-url="dashboard_adm_sensores_select.php">Historico</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-window">
                        <h3>Porta </h3>
                        <p>Status Porta: <?php echo getLatestValue('dados_esp32', 'I4'); ?></p>
                        <button class="btn btn-light" id="enviarBotao2" data-valor="I4" data-url="dashboard_adm_sensores_select.php">Historico</button>
                    </div>
                </div>

                <div class="col-md-4">
                    
                        <div class="widget-container">
                            <form method="post" action="">
                                <div class="form-group">
                                    <label for="maxValue">Valor Máximo:</label>
                                    <input type="number" step="0.01" class="form-control" id="maxValue" name="maxValue" required>
                                </div>
                                <div class="form-group">
                                    <label for="minValue">Valor Mínimo:</label>
                                    <input type="number" step="0.01" class="form-control" id="minValue" name="minValue" required>
                                </div>

                                <input type="hidden" name="email" value="<?php echo $_SESSION['email']; ?>">
                                <input type="hidden" name="table" value="<?php echo $vlrUrl; ?>">
                                <button type="submit" class="btn btn-primary btn-block">Enviar</button>
                        </div>
                        </form>

                    



                </div>
            </div>
            <div class="col-md-4">
                <div class="info-window">
                    <h3>Medição </h3>
                    <p>Umidade: <?php echo getLatestValue('dados_esp32', 'humidade'); ?></p>

                    <button class="btn btn-light" id="enviarBotao4" data-valor="humidade" data-url="dashboard_adm_sensores_select.php">Historico</button>

                </div>
            </div>
            <div class="container mt-5">

            </div>
            <button type="button" class="btn btn-primary btn-config" onclick="window.location.href='dashboard_adm_sensores_alarme.php'">
                <img width="30" height="30" src="https://img.icons8.com/3d-fluency/94/gear--v2.png" alt="gear--v2" /></button>
            <!-- Adicione mais divs conforme necessário -->
        </div>
    </div>



    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</body>

</html>
