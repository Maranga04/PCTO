<?php
require 'db.php';

function getAziende() {
    $pdo = getDB();
    $stmt = $pdo->query('SELECT * FROM Azienda');
    $aziende = $stmt->fetchAll();
    header('Content-Type: application/json');
    echo json_encode($aziende);
}

function addRecensione() {
    $pdo = getDB();
    $data = json_decode(file_get_contents('php://input'), true);
    $stmt = $pdo->prepare('INSERT INTO Recensione (voto, commento, idStudente) VALUES (?, ?, ?)');
    $stmt->execute([$data['voto'], $data['commento'], $data['idStudente']]);
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
}

function handleRequest() {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = explode('/', $uri);

    if ($uri[1] !== 'api') {
        header("HTTP/1.1 404 Not Found");
        exit();
    }

    switch ($uri[2]) {
        case 'aziende':
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                getAziende();
            } else {
                header("HTTP/1.1 405 Method Not Allowed");
            }
            break;
        case 'recensioni':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                addRecensione();
            } else {
                header("HTTP/1.1 405 Method Not Allowed");
            }
            break;
        default:
            header("HTTP/1.1 404 Not Found");
    }
}
?>
