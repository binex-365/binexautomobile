<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

header('Content-Type: application/json');

$name = htmlspecialchars($_POST['name'] ?? '');
$phone = htmlspecialchars($_POST['phone'] ?? '');
$offer = htmlspecialchars($_POST['offer'] ?? '');
$carName = htmlspecialchars($_POST['carName'] ?? '');
$carModel = htmlspecialchars($_POST['carModel'] ?? '');
$carPrice = htmlspecialchars($_POST['carPrice'] ?? '');
$carImage = htmlspecialchars($_POST['carImage'] ?? '');


if (!$name || !$phone || !$offer) {
    echo json_encode(["success" => false, "error" => "Missing input"]);
    exit;
}

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->CharSet = 'UTF-8'; // ✅ Fixes ₦ symbol issue
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'oe201374@gmail.com';
    $mail->Password = 'ckgexbkbdvpoeuub';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('oe201374@gmail.com', 'Genkada Automobile');
    $mail->addAddress('oe201374@gmail.com'); //

    // Content
    $mail->isHTML(true); // Enable HTML mode
    $mail->Subject = "🚘 New Offer from $name";
    $mail->Body = "
        <h2>📩 New Offer Received</h2>
        <p><strong>Name:</strong> $name<br>
        <strong>Phone:</strong> $phone<br>
        <strong>Offer:</strong> ₦" . number_format((float)$offer, 2) . "</p>

        <h3>🚗 Car Details</h3>
        <p><strong>Car Name:</strong> $carName<br>
        <strong>Model:</strong> $carModel<br>
        <strong>Price:</strong> ₦" . number_format((float)$carPrice, 2) . "</p>

        <p><strong>Car Image:</strong><br>
        <img src='$carImage' alt='Car Image' width='300' style='border-radius:10px; box-shadow:0 0 10px #ccc;'></p>
    ";


    $mail->send();
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $mail->ErrorInfo]);
}