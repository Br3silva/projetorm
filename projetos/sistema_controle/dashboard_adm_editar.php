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
        <div class="user-list">
            <h3>Lista de Usuários</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>ID</th>
                        <th>Status</th>
                        <th>Nível de Acesso</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Recuperar dados de todos os usuários
                    $query = "SELECT id, nome, status, nivel_acesso FROM Usuarios";
                    $result = $conn->query($query);
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td style='color: ";
                        if ($row['status'] == 'pendente') {
                            echo "yellow"; // Amarelo para pendente
                        } elseif ($row['status'] == 'bloqueado') {
                            echo "red";    // Vermelho para bloqueado
                        }
                        echo ";'>{$row['nome']}</td>";
                        echo "<td>{$row['id']}</td>";
                        echo "<td>{$row['status']}</td>";
                        echo "<td>";
                        echo "<form method='post' action='dashboard_adm_editar.php'>";
                        echo "<input type='hidden' name='user_id' value='{$row['id']}'>";
                        echo "<select name='new_status'>";
                        echo "<option value='aprovado' " . ($row['status'] == 'aprovado' ? 'selected' : '') . ">Aprovado</option>";
                        echo "<option value='pendente' " . ($row['status'] == 'pendente' ? 'selected' : '') . ">Pendente</option>";
                        echo "<option value='bloqueado' " . ($row['status'] == 'bloqueado' ? 'selected' : '') . ">Bloqueado</option>";
                        echo "</select>";
                        echo "<button type='submit'>Atualizar</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "<td>";
                        echo "<form method='post' action='dashboard_adm_editar.php'>";
                        echo "<input type='hidden' name='user_id' value='{$row['id']}'>";
                        echo "<select name='new_nivel_acesso'>";
                        echo "<option value='adm' " . ($row['nivel_acesso'] == 'adm' ? 'selected' : '') . ">Admin</option>";
                        echo "<option value='usuario' " . ($row['nivel_acesso'] == 'usuario' ? 'selected' : '') . ">Usuário</option>";
                        echo "</select>";
                        echo "<button type='submit'>Atualizar</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "<td>";
                        echo "<a href='editar_usuario.php?id={$row['id']}' class='btn btn-primary'>Editar</a>";
                        echo "<a href='excluir_usuario.php?id={$row['id']}' class='btn btn-danger'>Excluir</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

    <div class="user-sensor">
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

    
    </div>

    <!-- Inclua os arquivos do Bootstrap (JavaScript) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- ... restante do seu código ... -->
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

</body>
</html>
