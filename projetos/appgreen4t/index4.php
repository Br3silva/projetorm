<!DOCTYPE html>
<html>
<head>
    <title>Página de Assinatura de Documento</title>
    <!-- Incluindo os arquivos CSS do Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
     
        <div class="form-group">
            <label for="assinatura">Assinatura:</label><br>
            <canvas id="canvas" width="600" height="200" style="border: 1px solid black;"></canvas><br>
            <button type="button" class="btn btn-danger" onclick="limparAssinatura()">Limpar</button>
            <button type="button" class="btn btn-primary" onclick="salvarAssinatura()">Salvar</button>
            <input type="hidden" id="assinatura" name="assinatura">
        </div>
    <div class="container mt-5">
        <h1>Assine o Documento</h1>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nome">Nome do Cliente:</label>
                    <input type="text" class="form-control" id="nome" name="nome">
                </div>
                <div class="form-group col-md-6">
                    <label for="razaoSocial">Razão Social:</label>
                    <input type="text" class="form-control" id="razaoSocial" name="razaoSocial">
                </div>
            </div>
            <div class="form-group">
                <label for="endereco">Endereço:</label>
                <input type="text" class="form-control" id="endereco" name="endereco">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="cidade">Cidade:</label>
                    <input type="text" class="form-control" id="cidade" name="cidade">
                </div>
                <div class="form-group col-md-4">
                    <label for="estado">Estado:</label>
                    <input type="text" class="form-control" id="estado" name="estado">
                </div>
                <div class="form-group col-md-2">
                    <label for="codigoProjeto">Código do Projeto:</label>
                    <input type="text" class="form-control" id="codigoProjeto" name="codigoProjeto">
                </div>
            </div>
            <div class="form-group">
                <label for="pessoaContato">Pessoa de Contato:</label>
                <input type="text" class="form-control" id="pessoaContato" name="pessoaContato">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="telefoneRamal">Telefone Ramal:</label>
                    <input type="text" class="form-control" id="telefoneRamal" name="telefoneRamal">
                </div>
                <div class="form-group col-md-6">
                    <label for="departamento">Departamento:</label>
                    <input type="text" class="form-control" id="departamento" name="departamento">
                </div>
            </div>
            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="placaECB">Número da Placa ECB-S:</label>
                <input type="text" class="form-control" id="placaECB" name="placaECB">
            </div>
            <div class="form-group">
                <label for="tipoManutencao">Título Manutenção:</label>
                <select class="form-control" id="tipoManutencao" name="tipoManutencao">
                    <option value="contratual">Contratual</option>
                    <option value="at_estrategico">Atendimento Estratégico</option>
                    <option value="avulso">Avulso</option>
                </select>
            </div>
            <div class="form-group">
                <label for="servicoCompleto">O serviço foi executado por completo?</label>
                <select class="form-control" id="servicoCompleto" name="servicoCompleto">
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>
                </select>
            </div>
            <div class="form-group">
                <label for="roteiroTecnicos">Número do Roteiro e Relação de Técnicos:</label>
                <input type="text" class="form-control" id="roteiroTecnicos" name="roteiroTecnicos">
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="matricula">Matrícula:</label>
                    <input type="text" class="form-control" id="matricula" name="matricula">
                </div>
                <div class="form-group col-md-6">
                    <label for="dataHoraChegada">Data e Hora de Chegada:</label>
                    <input type="datetime-local" class="form-control" id="dataHoraChegada" name="dataHoraChegada">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="dataHoraSaida">Data e Hora de Saída:</label>
                    <input type="datetime-local" class="form-control" id="dataHoraSaida" name="dataHoraSaida">
                </div>
                <div class="form-group col-md-6">
                    <label for="acoesPendentes">Ações Pendentes:</label>
                    <input type="text" class="form-control" id="acoesPendentes" name="acoesPendentes">
                </div>
            </div>
            <div class="form-group">
                <label for="vulnerabilidade">Existe vulnerabilidade devido às ações pendentes?</label>
                <select class="form-control" id="vulnerabilidade" name="vulnerabilidade">
                    <option value="sim">Sim</option>
                    <option value="nao">Não</option>
                </select>
            </div>
            <!-- <div class="form-group">
                <label for="assinatura">Assinatura:</label><br>
                <canvas id="canvas" width="400" height="200" style="border: 1px solid black;"></canvas><br>
                <button type="button" class="btn btn-danger" onclick="limparAssinatura()">Limpar</button>
                <input type="hidden" id="assinatura" name="assinatura">
            </div> -->

            <!-- <br><br> -->
            <!-- <button type="submit" class="btn btn-primary">Assinar</button> -->
        </form>
        <div class="container mt-5">
        <h2>Assine o Documento</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="assinatura">Assinatura:</label><br>
                <canvas id="canvas" width="600" height="200" style="border: 1px solid black;"></canvas><br>
                <button type="button" class="btn btn-danger" onclick="limparAssinatura()">Limpar</button>
                <input type="hidden" id="assinatura" name="assinatura">
            </div>

            <br><br>
            <button type="submit" class="btn btn-primary">Assinar</button>
        </form>
    </div>

    <!-- Incluindo os arquivos JavaScript do Bootstrap e scripts relacionados à assinatura -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>

            // Função para salvar a assinatura como imagem PNG
    function salvarAssinatura() {
        const dataURL = canvas.toDataURL("image/png");
        const a = document.createElement("a");
        a.href = dataURL;
        a.download = "assinatura.png";
        a.click();
    }
        const canvas = document.getElementById("canvas");
        const ctx = canvas.getContext("2d");
        let desenhando = false;

        canvas.addEventListener("mousedown", () => {
            desenhando = true;
            ctx.beginPath();
        });

        canvas.addEventListener("mousemove", (event) => {
            if (!desenhando) return;

            const x = event.clientX - canvas.getBoundingClientRect().left;
            const y = event.clientY - canvas.getBoundingClientRect().top;

            ctx.lineWidth = 2;
            ctx.lineCap = "round";
            ctx.strokeStyle = "black";

            ctx.lineTo(x, y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x, y);
        });

        canvas.addEventListener("mouseup", () => {
            desenhando = false;
            document.getElementById("assinatura").value = canvas.toDataURL();
        });

        function limparAssinatura() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            document.getElementById("assinatura").value = "";
        }
    </script>
    </div>

    <!-- Incluindo os arquivos JavaScript do Bootstrap e scripts relacionados à assinatura -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.
