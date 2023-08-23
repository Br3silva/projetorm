<?php

// Carregar o Composer
require './vendor/autoload.php';

// Referenciar o namespace Dompdf
use Dompdf\Dompdf;

// Instanciar e usar a classe dompdf
$dompdf = new Dompdf(['enable_remote' => true]);

$dados = "
<style>
    .image-container {
        width: 19cm;
        height: 29.7cm;
        background-image: url('http://localhost/projetos/APPGREEN4T/seufundo.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        position: relative;
        max-width: 170%;
        max-height: 100%;
        top: 0%;
        left: 0%;
    }
    .pdf-content {
        position: absolute;
      
        max-width: 100%;
        max-height: 100%;
    }
</style>
<div class='image-container'>
    <div class='pdf-content'>
        <embed src='your-pdf-file.pdf' type='application/pdf' width='100%' height='100%'>
    </div>
</div>
";

// Instanciar o metodo loadHtml e enviar o conteudo do PDF
$dompdf->loadHtml($dados);

// Configurar o tamanho e a orientacao do papel
$dompdf->setPaper('A4', 'portrait');

// Renderizar o HTML como PDF
$dompdf->render();

// Gerar o PDF
$dompdf->stream();
?>
