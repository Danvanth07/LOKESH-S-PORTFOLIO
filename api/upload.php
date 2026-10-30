<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed. Use POST.']);
    exit;
}

$targetType = $_POST['type'] ?? 'coverPhoto'; // 'coverPhoto' or 'avatar'
$uploadDir = __DIR__ . '/../assets/uploads/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    $errMap = [
        UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive in php.ini.',
        UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE specified in form.',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
        UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.'
    ];
    $code = $_FILES['photo']['error'] ?? UPLOAD_ERR_NO_FILE;
    $msg = $errMap[$code] ?? 'File upload error occurred.';
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $msg]);
    exit;
}

$file = $_FILES['photo'];
$maxSize = 15 * 1024 * 1024; // 15MB limit

if ($file['size'] > $maxSize) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'File size exceeds 15MB limit.']);
    exit;
}

// Validate mime type
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($file['tmp_name']);
$allowedMimes = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp',
    'image/gif'  => 'gif'
];

if (!isset($allowedMimes[$mimeType])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Invalid file format. Allowed formats: JPG, PNG, WEBP, GIF.']);
    exit;
}

$ext = $allowedMimes[$mimeType];
$safePrefix = ($targetType === 'avatar') ? 'avatar_' : 'cover_';
$filename = $safePrefix . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
$targetPath = $uploadDir . $filename;
$relativeUrl = 'assets/uploads/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Failed to save uploaded file.']);
    exit;
}

// Update portfolio_data.json so the new image is immediately remembered
$dataFile = __DIR__ . '/../data/portfolio_data.json';
if (file_exists($dataFile)) {
    $content = file_get_contents($dataFile);
    $data = json_decode($content, true);
    if (is_array($data)) {
        if ($targetType === 'avatar') {
            $data['profile']['avatar'] = $relativeUrl;
        } else {
            $data['profile']['coverPhoto'] = $relativeUrl;
        }
        file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
    }
}

echo json_encode([
    'success' => true,
    'message' => ($targetType === 'avatar' ? 'Profile photo' : 'Cover photo') . ' uploaded and applied successfully!',
    'type' => $targetType,
    'url' => $relativeUrl,
    'filename' => $filename
]);

