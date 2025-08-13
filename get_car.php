<?php
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "No car ID provided."]);
    exit;
}

$carId = intval($_GET['id']);

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ? LIMIT 1");
    $stmt->execute([$carId]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($car) {
        // Build gallery_images array from individual image columns
        $galleryImages = [];
        $imageFields = ['image', 'front', 'back', 'interior', 'interior2', 'exterior', 'engine'];

        foreach ($imageFields as $field) {
            if (!empty($car[$field])) {
                $galleryImages[] = $car[$field];
            }
            // Optional: remove individual image fields from the output if you want
            unset($car[$field]);
        }

        $car['gallery_images'] = $galleryImages;

        echo json_encode($car);
    } else {
        echo json_encode(["error" => "Car not found."]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}