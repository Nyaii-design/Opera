<?php
// Adatbázis kapcsolat - Nethely (vagy más) tárhelyen módosítsd!
// $dbh = new PDO('mysql:host=localhost;dbname=adatb', 'adatbf', 'JELSZO',
//     array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

try {
    $dbh = new PDO(
        'mysql:host=localhost;dbname=adatb;charset=utf8mb4',
        'adatbf',
        'JELSZO_IDE',
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        )
    );
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'DB kapcsolat sikertelen: ' . $e->getMessage()]);
    exit;
}

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST,PUT,DELETE,OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }
