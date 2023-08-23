<!DOCTYPE html>
<html>
<head>
    <title>Cadastro de Usuário</title>
    <!-- Inclua os arquivos do Bootstrap (CSS) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-4">Cadastro de Usuário</h1>

        <?php
        require('conexao.php');

        

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            // Verificação se o email já existe no banco de dados
            $emailExistsQuery = "SELECT COUNT(*) as count FROM Usuarios WHERE email = ?";
            $stmt = $conn->prepare($emailExistsQuery);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $emailCount = $result->fetch_assoc()['count'];

            if ($emailCount > 0) {
                echo '<div id="alertMessage" class="alert alert-danger">O email já está cadastrado.</div>';
            } else {
                $insertQuery = "INSERT INTO Usuarios (nome, senha, email, status, nivel_acesso) VALUES (?, ?, ?, 'pendente', 'usuario')";
                $insertStmt = $conn->prepare($insertQuery);
                $insertStmt->bind_param("sss", $nome, $senha, $email);

                if ($insertStmt->execute()) {
                    echo '<div id="alertMessage" class="alert alert-success">Cadastro realizado com sucesso!</div>';
                    
                    // Redirecionar para a página de aguardo de aprovação após 2 segundos
                    echo '<script>
                            setTimeout(function() {
                                window.location.href = "aguardando_aprovacao.php";
                            }, 2000); // 2000 milissegundos = 2 segundos
                          </script>';
                } else {
                    echo '<div id="alertMessage" class="alert alert-danger">Erro ao cadastrar: ' . $insertStmt->error . '</div>';
                }

                $insertStmt->close();
            }

            $stmt->close();
        }

        $conn->close();

        
        ?>

<form action="cadastro.php" method="post">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha:</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cadastrar</button>
                </form>
        
        <a href="login.php" class="mt-2">Tela Inicial</a>
    </div>
    <script>
// JavaScript para esconder a mensagem após um certo período de tempo
setTimeout(function() {
    var alertMessage = document.getElementById('alertMessage');
    if (alertMessage) {
        alertMessage.style.display = 'none';
    }
}, 3000); // 5000 milissegundos = 5 segundos
</script>
    <!-- Inclua os arquivos do Bootstrap (JavaScript) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
