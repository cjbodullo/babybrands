<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_form_error'] = 'Method not allowed.';
    header('Location: ../?page=winner-photo-release');
    exit();
}

$dbConfig = require __DIR__ . '/../config/database.php';

function redirectWithError($message)
{
    $_SESSION['flash_form_error'] = $message;
    header('Location: ../?page=winner-photo-release');
    exit();
}

function clean($key)
{
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : '';
}

$firstName = clean('first_name');
$lastName = clean('last_name');
$email = clean('email');
$phone = clean('phone');
$address1 = clean('address_1');
$address2 = clean('address_2');
$city = clean('city');
$province = clean('province');
$postalCode = clean('postal_code');
$agreeTerms = isset($_POST['agree_terms']) ? 1 : 0;

if (
    $firstName === '' || $lastName === '' || $email === '' || $phone === '' ||
    $address1 === '' || $city === '' || $province === '' || $postalCode === '' || $agreeTerms !== 1
) {
    redirectWithError('Please complete all required fields.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectWithError('Please enter a valid email address.');
}

if (!isset($_FILES['winner_photo']) || !is_array($_FILES['winner_photo'])) {
    redirectWithError('Please upload your winner photo.');
}

$photo = $_FILES['winner_photo'];
if (($photo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
    redirectWithError('Photo upload failed. Please try again.');
}

$maxBytes = 10 * 1024 * 1024;
if (($photo['size'] ?? 0) > $maxBytes) {
    redirectWithError('Photo exceeds the 10MB maximum size.');
}

$tmpName = $photo['tmp_name'] ?? '';
if ($tmpName === '' || !is_uploaded_file($tmpName)) {
    redirectWithError('Invalid uploaded file.');
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = $finfo ? finfo_file($finfo, $tmpName) : '';
if ($finfo) {
    finfo_close($finfo);
}

$allowedMimeToExt = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
];

if (!isset($allowedMimeToExt[$mimeType])) {
    redirectWithError('Only PNG and JPG images are allowed.');
}

// Save uploads into external WordPress folder:
// d:/xampp/htdocs/wordpress/wp-content/uploads
$wpUploadsBaseDir = getenv('WP_UPLOADS_DIR');
if (!$wpUploadsBaseDir) {
    $wpUploadsBaseDir = dirname(__DIR__, 2) . '/wordpress/wp-content/uploads';
}

$wpUploadsBaseDir = rtrim(str_replace('\\', '/', $wpUploadsBaseDir), '/');
$uploadDir = $wpUploadsBaseDir . '/' . date('Y') . '/' . date('m');

if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true)) {
    redirectWithError('Unable to create upload directory.');
}

$safeFileName =  'winner_' . bin2hex(random_bytes(6)) . '.' . $allowedMimeToExt[$mimeType];
$absolutePath = $uploadDir . '/' . $safeFileName;
$relativePath = 'wp-content/uploads/' . date('Y') . '/' . date('m') . '/' . $safeFileName;

if (!move_uploaded_file($tmpName, $absolutePath)) {
    redirectWithError('Unable to save uploaded photo.');
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $mysqli = new mysqli(
        $dbConfig['host'],
        $dbConfig['username'],
        $dbConfig['password'],
        '',
        (int) $dbConfig['port']
    );
    $mysqli->set_charset('utf8mb4');

    $databaseName = $dbConfig['database'];
    $mysqli->query("CREATE DATABASE IF NOT EXISTS `$databaseName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $mysqli->select_db($databaseName);

    $mysqli->query(
        "CREATE TABLE IF NOT EXISTS wp_winner_photo_release_submissions (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(120) NOT NULL,
            last_name VARCHAR(120) NOT NULL,
            email VARCHAR(190) NOT NULL,
            phone VARCHAR(40) NOT NULL,
            address_1 VARCHAR(255) NOT NULL,
            address_2 VARCHAR(255) NULL,
            city VARCHAR(120) NOT NULL,
            province VARCHAR(120) NOT NULL,
            postal_code VARCHAR(25) NOT NULL,
            winner_photo_path VARCHAR(255) NOT NULL,
            status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
            agreed_terms TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );

    $statusColumnCheck = $mysqli->query("SHOW COLUMNS FROM wp_winner_photo_release_submissions LIKE 'status'");
    if ($statusColumnCheck && $statusColumnCheck->num_rows === 0) {
        $mysqli->query(
            "ALTER TABLE wp_winner_photo_release_submissions
             ADD COLUMN status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending' AFTER winner_photo_path"
        );
    }

    $stmt = $mysqli->prepare(
        "INSERT INTO wp_winner_photo_release_submissions
        (first_name, last_name, email, phone, address_1, address_2, city, province, postal_code, winner_photo_path, status, agreed_terms)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)"
    );
    $stmt->bind_param(
        'ssssssssssi',
        $firstName,
        $lastName,
        $email,
        $phone,
        $address1,
        $address2,
        $city,
        $province,
        $postalCode,
        $relativePath,
        $agreeTerms
    );

    $stmt->execute();
    $stmt->close();
    $mysqli->close();
} catch (Throwable $e) {
    if (file_exists($absolutePath)) {
        unlink($absolutePath);
    }
    redirectWithError('Database save failed: ' . $e->getMessage());
}

$_SESSION['flash_form_success'] = 'Form submitted successfully. Thank you for your consent.';
header('Location: ../?page=winner-photo-release');
exit();
