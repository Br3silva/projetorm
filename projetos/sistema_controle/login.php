<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <!-- Inclua os arquivos do Bootstrap (CSS) -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Login
            </div>
            <div class="card-body">
                <?php
                
                session_start();
                require('conexao.php');

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $email = $_POST['email'];
                    $senha = $_POST['senha'];
                    $nivel_acesso = $_POST['nivel_acesso'];

                    // Preparar as consultas com declarações preparadas para evitar SQL injection
                    $checkUserQuery = "SELECT status, nivel_acesso FROM Usuarios WHERE email = ? AND senha = ?";
                    $stmt = $conn->prepare($checkUserQuery);
                    $stmt->bind_param("ss", $email, $senha);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows === 0) {
                        echo '<div class="alert alert-danger" role="alert">Usuário não cadastrado. <a href="cadastro.php">Cadastrar</a></div>';
                    } else {
                        $userData = $result->fetch_assoc();
                        if ($userData['status'] !== 'aprovado') {
                            echo '<div class="alert alert-warning" role="alert">Seu cadastro está pendente de aprovação pelo administrador.</div>';
                        } else {
                            $_SESSION['email'] = $email; // Iniciar a sessão com o email do usuário
                            $_SESSION['nivel_acesso'] = $userData['nivel_acesso']; // Salvar nível de acesso na sessão

                            if ($userData['nivel_acesso'] === 'adm') {
                                // Redirecionar para a página de dashboard do administrador
                                header("Location: dashboard_adm_editar.php");
                                exit;
                            } else {
                                // Redirecionar para a página de dashboard do usuário
                                header("Location: dashboard_usuario.php");
                                exit;
                            }
                        }
                    }
                }

                $conn->close();
                ?>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="senha">Senha:</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </form>
            </div>
            <div class="card-footer text-center">
                Não possui uma conta? <a href="cadastro.php">Cadastrar</a>
            </div>
        </div>
    </div>

    <!-- Inclua os arquivos do Bootstrap (JavaScript) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
