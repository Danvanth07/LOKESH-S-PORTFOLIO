<?php
$type   = $_GET['file']   ?? 'resume';
$inline = isset($_GET['inline']) && $_GET['inline'] === '1';

$baseDir = dirname(__DIR__);

if ($type === 'portfolio') {
    $filePath     = $baseDir . DIRECTORY_SEPARATOR . 'Portfolio.pdf';
    $downloadName = 'Lokesh_Pon_Civil_Engineering_Portfolio.pdf';
} elseif ($type === 'staad') {
    $filePath     = $baseDir . DIRECTORY_SEPARATOR . 'revit project case study phographs' . DIRECTORY_SEPARATOR . 'Lokesh Pon Final Staad.pdf';
    $downloadName = 'Lokesh_Pon_STAAD_Pro_Structural_Analysis.pdf';
} else {
    $filePath     = $baseDir . DIRECTORY_SEPARATOR . 'Lokesh pon resume.pdf';
    $downloadName = 'Lokesh_Pon_Civil_Engineering_Resume.pdf';
}

if (!file_exists($filePath)) {
    http_response_code(404);
    echo 'File not found.';
    exit;
}

$disposition = $inline ? 'inline' : 'attachment';

header('Content-Description: File Transfer');
header('Content-Type: application/pdf');
header('Content-Disposition: ' . $disposition . '; filename="' . $downloadName . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));
readfile($filePath);
exit;

