<?php
header('Content-Type: application/json');

require_once 'config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'DB connection failed']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['id'])) {
    echo json_encode(['success' => false, 'error' => 'No car ID provided']);
    exit;
}

$carId = $data['id'];

// Step 1: Delete from database
$stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
$deleted = $stmt->execute([$carId]);

if ($deleted) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to delete car from DB']);
}