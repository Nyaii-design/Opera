<?php
require_once 'db.php';

$method = $_SERVER['REQUEST_METHOD'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

try {
    switch ($method) {
        case 'GET':
            if ($id) {
                $st = $dbh->prepare('SELECT * FROM mu WHERE id = ?');
                $st->execute([$id]);
                echo json_encode($st->fetch() ?: null);
            } else {
                $st = $dbh->query('SELECT * FROM mu ORDER BY id');
                echo json_encode($st->fetchAll());
            }
            break;

        case 'POST':
            $b = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            $st = $dbh->prepare('INSERT INTO mu (szerzo, cim) VALUES (?,?)');
            $st->execute([$b['szerzo'] ?? '', $b['cim'] ?? '']);
            $newId = (int)$dbh->lastInsertId();
            $st = $dbh->prepare('SELECT * FROM mu WHERE id = ?');
            $st->execute([$newId]);
            echo json_encode($st->fetch());
            break;

        case 'PUT':
            $b = json_decode(file_get_contents('php://input'), true);
            if (!$id) { http_response_code(400); echo json_encode(['error'=>'id kell']); break; }
            $st = $dbh->prepare('UPDATE mu SET szerzo=?, cim=? WHERE id=?');
            $st->execute([$b['szerzo'] ?? '', $b['cim'] ?? '', $id]);
            $st = $dbh->prepare('SELECT * FROM mu WHERE id = ?');
            $st->execute([$id]);
            echo json_encode($st->fetch());
            break;

        case 'DELETE':
            if (!$id) { http_response_code(400); echo json_encode(['error'=>'id kell']); break; }
            $st = $dbh->prepare('DELETE FROM mu WHERE id = ?');
            $st->execute([$id]);
            echo json_encode(['success' => true, 'id' => $id]);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Nem támogatott metódus']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
