<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header('Content-Type: application/json');

$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';
$token = str_replace('Bearer ', '', $authHeader);

if (!$token) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'mensaje' => 'Token no proporcionado']);
    exit;
}

try {
    $decoded = JWT::decode($token, new Key('6f173078adae4cceb8d88c52c3e94b0f2486665e36c346c690c98cbae2e92eca', 'HS256'));

    $_REQUEST['userEmail'] = $decoded->email;

} catch (\Exception $e) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'mensaje' => 'Token inválido']);
    exit;
}
