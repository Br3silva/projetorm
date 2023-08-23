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
            background-image: url('seufundo.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            width: 21cm;
            height: 29.7cm;
            padding: 20px;
            margin: auto;
            position: relative;
        }
    </style>
</head>
<body>
    <div class="container background-container">
       <!--  <h1 class="text-center">Assine o Documento</h1> -->
        
        <div class="signature-container">
            <canvas id="canvas" width="400" height="200"></canvas>
        </div>
        
        <div id="botoesAssinatura">
            <button id="btnAssinar" class="btn btn-primary">Assinar</button>
        </div>
        
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

        document.getElementById("btnAssinar").addEventListener("click", () => {
            document.getElementById("botoesAssinatura").style.display = "none";
            document.getElementById("botoesAposAssinatura").style.display = "block";
            document.querySelector(".signature-container").style.display = "block";
        });

        document.getElementById("btnSalvarAssinatura").addEventListener("click", () => {
            const assinaturaDataURL = canvas.toDataURL();
            const assinaturaImg = document.getElementById("assinaturaImg");

            // Exibir a assinatura salva
            assinaturaImg.src = assinaturaDataURL;

            // Ocultar a área de assinatura e mostrar a assinatura salva
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
            document.getElementById("botoesAssinatura").style.display = "block";
            document.querySelector(".signature-container").style.display = "block";
        });


    </script>
</body>
</html>
