<!DOCTYPE html>
<html>
<head>
    <title>Página de Assinatura</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
  
      .signature-container {
            width: 600px;
            height: 200px;
            border: 1px solid #000;
            background-color: rgba(255, 255, 255, 0.8);
            margin: 50px auto;
            display: none;
        }
        .signature-image {

            position: fixed;
            top: 55%;
            left: 73%;
            width: 18%;
            padding: 5px;
            /* background-color: #f0f0f0; */
           /*  border: 1px solid #ccc; */



           /*  display: block !important;
            width:17%;
            height: 22%;
            object-fit: contain;
            position: absolute; */
            /* top: 52%; /* Posiciona o topo do container no meio da página */
           /*  left: 72%;   *//* Posiciona a esquerda do container no meio da página */
        }
        .background-container {
            background-image: url('seufundo.jpg') !important;
                background-size: cover !important;
                background-repeat: no-repeat !important;
            width: 21cm;
            height: 29.7cm;
            padding: 20px;
            margin: auto;
            position: relative;
        }
    </style>
</head>
<body>
<button id="btnImprimir" class="btn btn-secondary">Imprimir</button>
    
    
    <div class="container background-container">
        <!-- Conteúdo anterior permanece igual -->
        
        <div class="signature-container">
            <canvas id="canvas" width="400" height="200"></canvas>
        </div>
        
        <button id="btnAssinar" class="btn btn-primary">Assinar</button>
        
        <div id="botoesAposAssinatura" style="display: none;">
            <button id="btnSalvarAssinatura" class="btn btn-success">Salvar Assinatura</button>
            <button id="btnLimparAssinatura" class="btn btn-danger">Limpar Assinatura</button>
        </div>
        
        <div id="assinaturaSalva" style="display: none;">
            <h3>Assinatura Salva:</h3>
            <img id="assinaturaImg" class="signature-image" src="" alt="Assinatura">
            <button id="btnLimparAssinaturaSalva" class="btn btn-danger">Limpar Assinatura</button>
        </div>
    </div>
    
    <script>
        const canvas = document.getElementById("canvas");
        const ctx = canvas.getContext("2d");
        let desenhando = false;

        canvas.addEventListener("touchstart", (event) => {
            event.preventDefault();
            desenhando = true;
            const touch = event.touches[0];
            const x = touch.clientX - canvas.getBoundingClientRect().left;
            const y = touch.clientY - canvas.getBoundingClientRect().top;
            ctx.beginPath();
            ctx.moveTo(x, y);
        });

        canvas.addEventListener("touchmove", (event) => {
            event.preventDefault();
            if (!desenhando) return;

            const touch = event.touches[0];
            const x = touch.clientX - canvas.getBoundingClientRect().left;
            const y = touch.clientY - canvas.getBoundingClientRect().top;

            ctx.lineWidth = 2;
            ctx.lineCap = "round";
            ctx.strokeStyle = "black";

            ctx.lineTo(x, y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x, y);
        });

        canvas.addEventListener("touchend", () => {
            desenhando = false;
        });

        document.getElementById("btnAssinar").addEventListener("click", () => {
            document.getElementById("btnAssinar").style.display = "none";
            document.getElementById("botoesAposAssinatura").style.display = "block";
            document.querySelector(".signature-container").style.display = "block";
        });

        document.getElementById("btnSalvarAssinatura").addEventListener("click", () => {
            const assinaturaDataURL = canvas.toDataURL();
            const assinaturaImg = document.getElementById("assinaturaImg");

            assinaturaImg.src = assinaturaDataURL;

            document.getElementById("assinaturaSalva").style.display = "block";
            document.querySelector(".signature-container").style.display = "none";
            document.getElementById("botoesAposAssinatura").style.display = "none";
        });

        document.getElementById("btnLimparAssinatura").addEventListener("click", () => {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        });

        document.getElementById("btnLimparAssinaturaSalva").addEventListener("click", () => {
            document.getElementById("assinaturaImg").src = "";
            document.getElementById("assinaturaSalva").style.display = "none";
            document.getElementById("btnAssinar").style.display = "block";
            document.querySelector(".signature-container").style.display = "block";
        });
              // Botão Imprimir
              document.getElementById("btnImprimir").addEventListener("click", () => {
            window.print();
        });
    </script>
</body>
</html>
