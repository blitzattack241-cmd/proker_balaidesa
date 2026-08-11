<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/import_helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid method']);
    exit;
}

if (empty($_FILES['file']['tmp_name']) || ($_FILES['file']['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
    echo json_encode(['error' => 'File not provided or upload error']);
    exit;
}

$tmp = $_FILES['file']['tmp_name'];
$name = $_FILES['file']['name'];
$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

try {
    $rows = load_rows_from_file($tmp, $ext);
} catch (Throwable $e) {
    echo json_encode(['error' => 'Failed to read file: ' . $e->getMessage()]);
    exit;
}

if (empty($rows)) {
    echo json_encode(['error' => 'No rows found in file']);
    exit;
}

// find header row index (first row with both nama and nik)
$headerIndex = null;
foreach ($rows as $idx => $row) {
    $hasNama = false; $hasNik = false;
    foreach ($row as $cell) {
        $norm = normalize_header((string)$cell);
        $map = canonical_map();
        if (isset($map[$norm]) && $map[$norm] === 'nama') $hasNama = true;
        if (isset($map[$norm]) && $map[$norm] === 'nik') $hasNik = true;
    }
    if ($hasNama && $hasNik) { $headerIndex = $idx; break; }
}
if ($headerIndex === null) { $headerIndex = 0; }

$headerRow = $rows[$headerIndex];
$sample = [];
for ($i = $headerIndex + 1; $i <= min($headerIndex + 9, count($rows)-1); $i++) {
    $sample[] = $rows[$i];
}

$suggest = suggest_mappings($headerRow);

$response = [
    'filename' => $name,
    'header_index' => $headerIndex,
    'headers' => array_values($headerRow),
    'suggestions' => $suggest,
    'sample' => $sample,
];

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;
