<?php
file_put_contents("debug.txt", print_r($_POST, true));
// Load configuration
require_once 'config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// --- reCAPTCHA Validation ---whil
if (!isset($_POST['g-recaptcha-response'])) {
    echo json_encode(["success" => false, "message" => "reCAPTCHA is missing."]);
    exit;
}

$recaptcha = $_POST['g-recaptcha-response'];
$verifyURL = 'https://www.google.com/recaptcha/api/siteverify';
$data = [
    'secret' => RECAPTCHA_SECRET_KEY,
    'response' => $recaptcha
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $verifyURL,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query($data),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => true
]);

$response = curl_exec($curl);
$curlError = curl_error($curl);
curl_close($curl);

if ($response === false) {
    echo json_encode(["success" => false, "message" => "cURL Error: $curlError"]);
    exit;
}

$responseData = json_decode($response);
if (!$responseData || !$responseData->success) {
    echo json_encode(["success" => false, "message" => "reCAPTCHA verification failed."]);
    exit;
}

// --- Get Form Data ---
$name      = trim($_POST['fullName'] ?? '');
$email     = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
$phone     = trim($_POST['phone'] ?? '');
$carName   = $_POST['car_name'] ?? '';
$carModel  = $_POST['car_model'] ?? '';
$carPrice  = $_POST['car_price'] ?? '';
$date      = $_POST['date'] ?? '';
$time      = $_POST['time'] ?? '';
$note      = $_POST['note'] ?? '';

// --- Handle Uploaded Image ---
$uploadedImage = '';

// ✅ Handle uploaded image from file input (drag-and-drop)
if (isset($_FILES['carImageUpload']) && $_FILES['carImageUpload']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = __DIR__ . "/uploads/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $tmpName = $_FILES['carImageUpload']['tmp_name'];
    $originalName = basename($_FILES['carImageUpload']['name']);
    $safeName = time() . '_' . preg_replace('/[^a-zA-Z0-9\.\-_]/', '_', $originalName);
    $destination = $uploadDir . $safeName;

    if (move_uploaded_file($tmpName, $destination)) {
        $uploadedImage = "uploads/" . $safeName;
    }
}

// ✅ If no uploaded file, fall back to carImage from form (handle local or remote)
if (empty($uploadedImage) && isset($_POST['carImage'])) {
    $carImageUrl = $_POST['carImage'];

    // Check if it's a URL and starts with "http"
    if (strpos($carImageUrl, 'http') === 0) {
        // Download it temporarily
        $imageContent = file_get_contents($carImageUrl);
        if ($imageContent !== false) {
            $tempFileName = __DIR__ . "/uploads/temp_" . time() . ".jpg";
            file_put_contents($tempFileName, $imageContent);
            $uploadedImage = $tempFileName; // Local path now
        }
    } else {
        // It's already a local path (like: uploads/car.jpg)
        $uploadedImage = $carImageUrl;
    }
}

// --- Save to MySQL ---
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("INSERT INTO inspections 
        (name, email, phone, car_name, car_model, car_price, inspection_date, inspection_time, note, uploaded_image, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    $stmt->execute([
        $name, $email, $phone, $carName, $carModel, $carPrice,
        $date, $time, $note, $uploadedImage
    ]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    exit;
}

// --- Send Email to Admin & Customer ---
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = SMTP_HOST;
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = 'tls';
    $mail->Port       = SMTP_PORT;

    $mail->setFrom(SMTP_FROM, SMTP_FROM_NAME);
    $mail->addAddress("oe201374@gmail.com"); // Admin
    if (!empty($email)) {
        $mail->addAddress($email); // Send to customer too
    }

    $mail->isHTML(true);
    $mail->Subject = "Car Inspection Request - $carName";

    // ✅ INSERTED: embed image into email if available
   if (!empty($uploadedImage)) {
        // ✅ Normalize slashes and check if $uploadedImage is already an absolute path
        $uploadedImage = str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $uploadedImage);

        $imagePath = (preg_match('/^[a-zA-Z]:\\\\/', $uploadedImage) || strpos($uploadedImage, DIRECTORY_SEPARATOR) === 0)
            ? $uploadedImage
            : __DIR__ . DIRECTORY_SEPARATOR . $uploadedImage;

        $mail->AddEmbeddedImage($imagePath, 'carImageCID');
    }

    // ✅ INSERTED ICON LOGIC BELOW
    $iconHTML = '';
    if (stripos($carName, 'service') !== false || stripos($carModel, 'service') !== false) {
        $iconHTML = "<div style='text-align:center; margin-bottom:15px;'>
            <img src='" . BASE_URL . "assets/gear-icon.png' alt='Service Icon' style='width:80px;'>
        </div>";
    } else {
        $iconHTML = "<div style='text-align:center; margin-bottom:15px;'>
            <img src='" . BASE_URL . "assets/car-icon.png' alt='Car Icon' style='width:80px;'>
        </div>";
    }

    $emailBody = $iconHTML . "
        <h2>Inspection Booking Details</h2>
        <p><strong>Name:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Phone:</strong> $phone</p>
        <p><strong>Car Name:</strong> $carName</p>
        <p><strong>Model:</strong> $carModel</p>
        <p><strong>Price:</strong> $carPrice</p>
        <p><strong>Date:</strong> $date</p>
        <p><strong>Time:</strong> $time</p>
        <p><strong>Note:</strong> $note</p>";

    // ✅ REPLACED: use embedded image reference instead of URL
    if (!empty($uploadedImage)) {
        $emailBody .= "<p><strong>Uploaded Image:</strong><br>
        <img src='cid:carImageCID' style='max-width:300px; border:1px solid #ccc;'></p>";
    }

    $mail->Body = $emailBody;
    $mail->send();

    // --- Confirmation Email to Customer ---
    if (!empty($email)) {
        $confirmation = new PHPMailer(true);
        $confirmation->isSMTP();
        $confirmation->Host       = SMTP_HOST;
        $confirmation->SMTPAuth   = true;
        $confirmation->Username   = SMTP_USER;
        $confirmation->Password   = SMTP_PASS;
        $confirmation->SMTPSecure = 'tls';
        $confirmation->Port       = SMTP_PORT;

        $confirmation->setFrom(SMTP_FROM, SMTP_FROM_NAME);
        $confirmation->addAddress($email, $name);
        $confirmation->isHTML(true);
        $confirmation->Subject = "Your Inspection Request for $carName";

        $confirmation->Body = "
            <div style='font-family: Poppins, sans-serif;'>
              <h2 style='color: #007cf0;'>Thank you, $name!</h2>
              <p>We’ve received your inspection request for <strong>$carName ($carModel)</strong>.</p>
              <p>Preferred Date: <strong>$date</strong><br>
              Preferred Time: <strong>$time</strong></p>
              <p>We’ll contact you shortly to confirm everything.</p>
              <p style='margin-top: 20px;'>Regards,<br><strong>Genkada Automobile</strong></p>
            </div>";
        $confirmation->send();
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Email Error: " . $mail->ErrorInfo]);
    exit;
}

// ✅ Simulate 3-second processing time
sleep(3);

// --- Final Success Response ---
echo json_encode(["success" => true, "message" => "Inspection request submitted successfully."]);
exit;