<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Create PDO instance
        $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Sanitize and fetch fields
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';
        $model = $_POST['model'];
        
        // File handling function
        function uploadImage($fileInputName) {
            $uploadDir = 'uploads/';
            if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
                $ext = pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION);
                $filename = uniqid($fileInputName . "_") . '.' . $ext;
                $destination = $uploadDir . $filename;
                move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $destination);
                return $destination;
            }
            return null;
        }

        // Upload images and get paths
        $mainImage = uploadImage('image');
        $frontImage = uploadImage('front');
        $backImage = uploadImage('back');
        $interior = uploadImage('interior');
        $interior2 = uploadImage('interior2');
        $exterior = uploadImage('exterior');
        $engine = uploadImage('engine');

        // Insert car into database
        $stmt = $pdo->prepare("INSERT INTO cars (name, description, price, model, image, front, back, interior, interior2, exterior, engine) 
            VALUES (:name, :description, :price, :model, :image, :front, :back, :interior, :interior2, :exterior, :engine)");

        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':model' => $model,
            ':image' => $mainImage,
            ':front' => $frontImage,
            ':back' => $backImage,
            ':interior' => $interior,
            ':interior2' => $interior2,
            ':exterior' => $exterior,
            ':engine' => $engine,
        ]);

        // ✅ Now get last inserted car
        $lastCarId = $pdo->lastInsertId();

        $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
        $stmt->execute([$lastCarId]);
        $newCar = $stmt->fetch(PDO::FETCH_ASSOC);

        // After successful car insertion
        header("Location: admin_dashboard.php?success=1");
        exit;

    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage();
    }
} else {
    echo "❌ Invalid request method.";
}
?>