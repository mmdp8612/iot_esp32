<?php
header('Content-Type: application/json');
require_once '../config.php';

$sql = "SELECT id, device_id, temperatura, humedad, fecha FROM mediciones ORDER BY id DESC LIMIT 1";
$stmt = $pdo->query($sql);
$medicion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$medicion) {
    echo json_encode([
        'success' => false,
        'message' => 'No hay mediciones'
    ]);
    exit;
}

echo json_encode([
    'success' => true,
    'data' => $medicion
]);