<?php
// Script para validar se uma URL permite ser encapsulada em um Iframe.
// Checa os cabeçalhos X-Frame-Options e Content-Security-Policy.
header('Content-Type: application/json');

if (!isset($_GET['url'])) {
    echo json_encode(['can_frame' => false, 'error' => 'No URL']);
    exit;
}

$url = filter_var($_GET['url'], FILTER_SANITIZE_URL);

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    // Se não for uma URL válida com http/https (ex: games/quiz/index.html) permitimos iframe (jogo interno)
    echo json_encode(['can_frame' => true]);
    exit;
}

// Configura o contexto da requisição
$context = stream_context_create([
    'http' => [
        'method' => 'HEAD', // Queremos apenas os cabeçalhos, não o corpo da página (mais rápido)
        'timeout' => 3, // Timeout de 3 segundos para não travar a UI
        'user_agent' => 'DevPlay Content Verifier/1.0'
    ],
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false
    ]
]);

// Suprime warnings caso o site demore a responder ou recuse a chamada HEAD precipitadamente
$headers = @get_headers($url, 1, $context);

if (!$headers) {
    // Se a conexão falhar totalmente, assumimos que não dá pra usar iframe
    echo json_encode(['can_frame' => false, 'error' => 'Connection failed or timeout']);
    exit;
}

// Padroniza as chaves do array de cabeçalhos para minúsculo
$headersLower = array_change_key_case($headers, CASE_LOWER);

$canFrame = true;

// 1. Verificação Mestra: X-Frame-Options
// DENY: Não carrega em iframe lugar nenhum. SAMEORIGIN: Só carrega se o iframe estiver no mesmo domínio web.
if (isset($headersLower['x-frame-options'])) {
    $xfo = is_array($headersLower['x-frame-options']) ? end($headersLower['x-frame-options']) : $headersLower['x-frame-options'];
    $xfo = strtoupper($xfo);
    if (strpos($xfo, 'DENY') !== false || strpos($xfo, 'SAMEORIGIN') !== false) {
        $canFrame = false;
    }
}

// 2. Verificação Moderna: Content-Security-Policy (CSP)
// Impede iframes de domains não contidos na sub-diretiva frame-ancestors
if ($canFrame && isset($headersLower['content-security-policy'])) {
    $csp = is_array($headersLower['content-security-policy']) ? end($headersLower['content-security-policy']) : $headersLower['content-security-policy'];
    $csp = strtolower($csp);
    if (strpos($csp, 'frame-ancestors') !== false) {
        // Se a policy existe e não tem um wildcard global '*', bloqueamos por segurança preemptiva
        if (strpos($csp, 'frame-ancestors *') === false) {
            $canFrame = false;
        }
    }
}

echo json_encode(['can_frame' => $canFrame]);
