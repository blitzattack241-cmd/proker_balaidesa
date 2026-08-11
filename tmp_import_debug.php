<?php
$_SERVER['REQUEST_METHOD'] = 'POST';
$_GET['debug'] = '1';
$_POST['import'] = '1';
$_FILES['file_excel'] = [
    'name' => 'tmp_import_test.csv',
    'type' => 'text/csv',
    'tmp_name' => __DIR__ . '/tmp_import_test.csv',
    'error' => 0,
    'size' => filesize(__DIR__ . '/tmp_import_test.csv'),
];
require __DIR__ . '/pages/proses_import.php';
