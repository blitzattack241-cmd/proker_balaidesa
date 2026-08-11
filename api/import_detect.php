<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', '0');
set_error_handler(function() {}, E_ALL);

ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Discard any output from session initialization
ob_clean();

// Now start the actual response buffer
$output = '';

try {
    require_once __DIR__ . '/import_helpers.php';
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Metode tidak valid');
    }

    if (empty($_FILES['file']['tmp_name']) || ($_FILES['file']['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new Exception('File belum dipilih atau terjadi kesalahan saat unggah');
    }

    $tmp = $_FILES['file']['tmp_name'];
    $name = $_FILES['file']['name'];
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    $rows = load_rows_from_file($tmp, $ext);

    if (empty($rows)) {
        throw new Exception('Tidak ada baris data yang ditemukan di dalam file');
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

    $output = json_encode($response, JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    $output = json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

ob_end_clean();
echo $output;
exit;
