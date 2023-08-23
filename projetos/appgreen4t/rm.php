<!DOCTYPE html>
<html>
<head>
    <title>Página com Imagem de Fundo</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('seufundo.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }

        .content {
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        @media print {
            body {
                background: url('seufundo.jpg') no-repeat center center fixed !important;
                background-size: cover !important;
            }

            .content {
                background: none !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="content">
 
    </div>
</body>
</html>
