<?php
require_once __DIR__ . '/import_response.php';

import_json_begin();

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/import_helpers.php';
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new InvalidArgumentException('Metode tidak valid');
    }

    if (empty($_FILES['file']['tmp_name']) || ($_FILES['file']['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new InvalidArgumentException('File belum dipilih atau terjadi kesalahan saat unggah');
    }

    $tmp = $_FILES['file']['tmp_name'];
    $name = $_FILES['file']['name'];
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    $rows = load_rows_from_file($tmp, $ext);

    if (empty($rows)) {
        throw new InvalidArgumentException('Tidak ada baris data yang ditemukan di dalam file');
    }

    $layout = detect_import_layout($rows);
    $headerIndex = $layout['header_index'];
    $headerRow = $layout['headers'];
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
        'mapping' => $layout['mapping'],
        'mapped_fields' => $layout['fields'],
        'sample' => $sample,
    ];

    import_json_response(array_merge(['ok' => true], $response));

} catch (Throwable $e) {
    $status = $e instanceof InvalidArgumentException ? 400 : 500;
    import_json_response(['ok' => false, 'error' => $e->getMessage()], $status);
}
