<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed. Please use POST.']);
    exit;
}

// Support both form-urlencoded and JSON payloads
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

if (!is_array($data) || empty($data)) {
    $data = $_POST;
}

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$phone = trim($data['phone'] ?? '');
$subject = trim($data['subject'] ?? 'Portfolio Contact Inquiry');
$message = trim($data['message'] ?? '');

$errors = [];

if (empty($name) || strlen($name) < 2) {
    $errors[] = 'Please enter your full name (at least 2 characters).';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please provide a valid email address.';
}

if (empty($message) || strlen($message) < 5) {
    $errors[] = 'Please enter a message (at least 5 characters).';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'errors' => $errors,
        'message' => implode(' ', $errors)
    ]);
    exit;
}

// Sanitize inputs
$inquiry = [
    'id' => uniqid('inq_', true),
    'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
    'email' => filter_var($email, FILTER_SANITIZE_EMAIL),
    'phone' => htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'),
    'subject' => htmlspecialchars($subject, ENT_QUOTES, 'UTF-8'),
    'message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
    'submittedAt' => date('Y-m-d H:i:s'),
    'ipAddress' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
    'userAgent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
];

$inquiriesFile = __DIR__ . '/../data/inquiries.json';
$inquiries = [];

if (file_exists($inquiriesFile)) {
    $existing = file_get_contents($inquiriesFile);
    $decoded = json_decode($existing, true);
    if (is_array($decoded)) {
        $inquiries = $decoded;
    }
}

// Prepend new inquiry
array_unshift($inquiries, $inquiry);

// Save with pretty print and lock
file_put_contents($inquiriesFile, json_encode($inquiries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);

echo json_encode([
    'success' => true,
    'message' => 'Thank you, ' . htmlspecialchars($name) . '! Your message has been received successfully. Lokesh Pon will get in touch with you shortly.',
    'inquiryId' => $inquiry['id'],
    'timestamp' => $inquiry['submittedAt']
]);

