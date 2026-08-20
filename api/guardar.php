<?php
header('Content-Type: application/json');

require_once '../config.php';

$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (!$data) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'JSON inválido'
    ]);
    exit;
}

if (!isset($data['device_id']) || !isset($data['temperatura']) || !isset($data['humedad'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Faltan datos'
    ]);
    exit;
}

$sql = "INSERT INTO mediciones (device_id, temperatura, humedad) VALUES (:device_id, :temperatura, :humedad)";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':device_id' => $data['device_id'],
    ':temperatura' => $data['temperatura'],
    ':humedad' => $data['humedad']
]);

echo json_encode([
    'success' => true,
    'message' => 'Medición guardada',
    'id' => $pdo->lastInsertId()
]);