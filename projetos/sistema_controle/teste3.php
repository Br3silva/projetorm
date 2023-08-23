<?php
function getLatestValue($table, $column) {
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard do Administrador</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Estilos da barra lateral e do conteúdo ... */

        /* Estilos para as janelas de informações ... */
        .info-window {
            background: linear-gradient(135deg, #2980b9, #3498db);
            border-radius: 10px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <!-- ... Conteúdo da barra lateral ... -->
    </div>
   
    <div class="content">
        <h1>Dashboard do Administrador</h1>
        <div class="container mt-4" id="info-container">
            <!-- Exemplo de uso da função para imprimir o último valor da coluna I2 -->
            <div class="row">
                <div class="col-md-4">
                    <div class="info-window">
                        <h3>Tabela 1</h3>
                        <p>Último valor da coluna I2: <?php echo getLatestValue('dados_esp32', 'I2'); ?></p>
                        <button class="btn btn-light">Detalhes</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-window">
                        <h3>Tabela 2</h3>
                        <p>Último valor da coluna I4: <?php echo getLatestValue('dados_esp32', 'I4'); ?></p>
                        <button class="btn btn-light">Detalhes</button>
                    </div>
                </div>
                <!-- Adicione mais divs conforme necessário -->
            </div>
        </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>

    function reloadPage() {
        location.reload();
    }

    // Atualize a página a cada 5 segundos (5000 ms)
    setInterval(reloadPage, 5000);

    </script>
</body>
</html>