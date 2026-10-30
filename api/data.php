<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');

$dataFile = __DIR__ . '/../data/portfolio_data.json';

if (!file_exists($dataFile)) {
    http_response_code(404);
    echo json_encode(['error' => 'Data file not found.']);
    exit;
}

$content = file_get_contents($dataFile);
echo $content;

