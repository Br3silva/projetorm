<!DOCTYPE html>
<html>
<head>
    <title>Página de Assinatura de Documento</title>
    <!-- Incluindo os arquivos CSS do Bootstrap e estilos customizados -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .container {
            position: relative;
            width: 794px; /* Largura de uma página A4 em pixels */
            height: 1123px; /* Altura de uma página A4 em pixels */
            background-image: url('fundo_a4.jpg'); /* Substitua pelo caminho do arquivo JPEG */
            background-size: cover;
        }
        .signature-container {
        
            position: absolute;
            bottom: 100px;
            left: 50%;
            transform: translateX(-50%);
        }
        canvas {
            border: 1px solid black;
            display: block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="signature-container">
            <button class="btn btn-primary" data-toggle="modal" data-target="#assinaturaModal">Assinar</button>
        </div>
    </div>

    <!-- Modal para a janela de assinatura -->
    <div class="modal fade" id="assinaturaModal" tabindex="-1" role="dialog" aria-labelledby="assinaturaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assinaturaModalLabel">Assine o Documento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <canvas id="canvas" width="300" height="200"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="salvarAssinatura()">Salvar Assinatura</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluindo os arquivos JavaScript do Bootstrap e scripts relacionados à assinatura -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
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
        });

        function salvarAssinatura() {
            const assinaturaDataURL = canvas.toDataURL();
            // Redirecionar para a página de visualização de PDF com a assinatura
            window.location.href = "gerar_pdf.php?assinatura=" + encodeURIComponent(assinaturaDataURL);
        }
    </script>
</body>
</html>

