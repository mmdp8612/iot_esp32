<?php

header('Content-Type: application/json');

require_once '../config.php';


$sql = "
    SELECT
        id,
        device_id,
        temperatura,
        humedad,
        fecha
    FROM mediciones
    ORDER BY id DESC
    LIMIT 50
";

$stmt = $pdo->query($sql);

$mediciones =
    $stmt->fetchAll(PDO::FETCH_ASSOC);


$mediciones =
    array_reverse($mediciones);



$stmtTotal = $pdo->query("
    SELECT COUNT(*) AS total
    FROM mediciones
");

$total =
    $stmtTotal
        ->fetch(PDO::FETCH_ASSOC);


echo json_encode([

    'success' => true,

    'total' => (int) $total['total'],

    'data' => $mediciones

]);