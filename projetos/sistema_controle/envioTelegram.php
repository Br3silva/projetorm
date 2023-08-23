<?php
$token = "6498811991:AAFMje1iz_LslVtmNsAd82QPBoRRDV9v-v0";
$group = "1958506581";

$message = "MENSAGEM PARA SER ENVIADA";

$data = array(
    'chat_id' => $group,
    'text' => $message,
    'parse_mode' => 'html', // Para aceitar tags HTML
    'disable_web_page_preview' => false // Para não mostrar o preview de um link
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot$token/sendMessage");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Accept: application/json"
));

$response = curl_exec($ch);
curl_close($ch);

// Exibe a resposta da API (pode ser útil para depuração)
echo $response;
?>
