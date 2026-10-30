<?php
$type = $_GET['file'] ?? 'resume';

$baseDir = dirname(__DIR__);

if ($type === 'portfolio') {
    $filePath = $baseDir . DIRECTORY_SEPARATOR . 'Portfolio.pdf';
    $downloadName = 'Lokesh_Pon_Civil_Engineering_Portfolio.pdf';
} else {
    $filePath = $baseDir . DIRECTORY_SEPARATOR . 'Lokesh pon resume.pdf';
    $downloadName = 'Lokesh_Pon_Civil_Engineering_Resume.pdf';
}

if (!file_exists($filePath)) {
    http_response_code(404);
    echo "File not found.";
    exit;
}

header('Content-Description: File Transfer');
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $downloadName . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));
readfile($filePath);
exit;

