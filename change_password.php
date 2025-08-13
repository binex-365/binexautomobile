<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Load current credentials
    $credentials = require 'admin_credentials.php';

    // Verify current password
    if (!password_verify($currentPassword, $credentials['password'])) {
        header("Location: admin_dashboard.php?msg=" . urlencode("Current password is incorrect"));
        exit;
    }

    // Check new passwords match
    if ($newPassword !== $confirmPassword) {
        header("Location: admin_dashboard.php?msg=" . urlencode("New passwords do not match"));
        exit;
    }

    // Hash new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update admin_credentials.php file
    $fileContent = "<?php\nreturn [\n" .
                   "    'username' => 'admin',\n" .
                   "    'password' => '" . $hashedPassword . "'\n" .
                   "];";

    if (file_put_contents('admin_credentials.php', $fileContent)) {
        // Destroy current session so admin must log in again
        session_destroy();
        header("Location: admin_login.php?msg=" . urlencode("Password changed successfully. Please log in again."));
        exit;
    } else {
        header("Location: admin_dashboard.php?msg=" . urlencode("Failed to update password"));
        exit;
    }
}
?>