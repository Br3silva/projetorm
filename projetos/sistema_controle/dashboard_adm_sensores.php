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

      /*   echo "Valor Mínimo: $temp_min<br>";
        echo "Valor Máximo: $temp_max<br>";
 */
        $limite = $temp_max; // Limite a ser verificado
        $consulta = contarUltrapassagensTemperatura($limite);

        $total_ultrapassagens = $consulta['total_ultrapassagens'];
        $max_temperatura = $consulta['max_temperatura'];

    /*     echo "Total de Ultrapassagens: $total_ultrapassagens<br>";
        echo "Máxima Temperatura Ultrapassada: $max_temperatura<br>"; */




        $limite = $temp_min; // Limite a ser verificado
        $consulta = contarTemperaturasAbaixoDoLimite($limite);

        $total_abaixo_limite = $consulta['total_abaixo_limite'];
        $min_temperatura = $consulta['min_temperatura'];

     /*    echo "Total de vezes abaixo do limite: $total_abaixo_limite<br>";
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
                    <div class="info-window">
                        <h3>Medição</h3>
                        <p>temperatura: <?php echo getLatestValue('dados_esp32', 'temperatura'); ?>
                        </p>

                        <p class="text-white">
                            <i class="fa fa-angle-up"></i> <?php echo "$temp_max" ?>
                            <span style="font-size: 0.9em" class="ms-2 White link">Alta</span>
                        </p>

                        <p class="text-white">
                            <i class="fa fa-angle-up"></i><?php echo "$temp_min" ?>
                            <span style="font-size: 0.9em" class="ms-2 White link">Baixa</span>
                        </p>

                        <button class="btn btn-dark py-1 px-2"><i class="fa fa-arrow-up ps-0 "></i> <?php echo "$total_ultrapassagens" ?> </button>
                        <button class="btn btn-dark py-1 px-2"><i class="fa fa-arrow-down ps-0"></i><?php echo "$total_abaixo_limite" ?></button>


                        <!-- botao de configuração -->
                        <button id="configAlarme" data-valor="configtemp" data-url="dashboard_adm_sensores_alarme.php" type="button">
                            <img width="30" height="30" src="https://img.icons8.com/deco/48/000000/alarm.png" alt="alarm" /></button>
                        <!-- botao de configuração -->
                        <button class="btn btn-light" id="enviarBotao3" data-valor="temperatura" data-url="dashboard_adm_sensores_select.php">Historico</button>
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
        <script>
            // atualizaçao da pagina(inicio)

            function reloadPage() {
                location.reload();
            }

            // Atualize a página a cada 5 segundos (5000 ms)

            setInterval(reloadPage, 5000);

            //fim atualizaçao da pagina(fim)



            // Função para lidar com o clique no botão(inicio)

            // Função para redirecionar com valor via URL
            // Função para redirecionar com valor via URL
            function redirecionarComValor(valor, url) {
                window.location.href = url + "?valor=" + valor;
            }

            // Associar a função aos botões
            var botao1 = document.getElementById("enviarBotao1");
            var botao2 = document.getElementById("enviarBotao2");
            var botao3 = document.getElementById("enviarBotao3");
            var botao4 = document.getElementById("enviarBotao4");
            var botao5 = document.getElementById("configAlarme");

            botao1.addEventListener("click", function() {
                var valor = botao1.getAttribute("data-valor");
                var url = botao1.getAttribute("data-url");
                redirecionarComValor(valor, url);
            });

            botao2.addEventListener("click", function() {
                var valor = botao2.getAttribute("data-valor");
                var url = botao2.getAttribute("data-url");
                redirecionarComValor(valor, url);
            });

            botao3.addEventListener("click", function() {
                var valor = botao3.getAttribute("data-valor");
                var url = botao3.getAttribute("data-url");
                redirecionarComValor(valor, url);
            });

            botao4.addEventListener("click", function() {
                var valor = botao4.getAttribute("data-valor");
                var url = botao4.getAttribute("data-url");
                redirecionarComValor(valor, url);
            });

            botao5.addEventListener("click", function() {
                var valor = botao5.getAttribute("data-valor");
                var url = botao5.getAttribute("data-url");
                redirecionarComValor(valor, url);
            });
            // Função para lidar com o clique no botão(fim)
        </script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>