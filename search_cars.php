<?php
require_once 'config.php';

if (!isset($_GET['q'])) {
    echo json_encode([]);
    exit;
}

$search = trim($_GET['q']);

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("
      SELECT * FROM cars 
      WHERE name LIKE :search
      OR model LIKE :search
      OR price LIKE :search
      OR description LIKE :search
    ");

    $stmt->execute(['search' => '%' . $search . '%']);
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($cars);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}