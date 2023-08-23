<!DOCTYPE html>
<html>
<head>
    <title>Visualizar Assinatura</title>
    <!-- Incluindo os arquivos CSS do Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Assinatura Salva</h1>
        <?php
        $assinaturaDataURL = isset($_POST['assinatura']) ? $_POST['assinatura'] : '';
        if ($assinaturaDataURL) {
            echo '<img class="signature-image" src="' . $assinaturaDataURL . '" alt="Assinatura">';
        } else {
            echo '<p>Nenhuma assinatura foi encontrada.</p>';
        }
        ?>
    </div>

    <!-- Incluindo os arquivos JavaScript do Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
