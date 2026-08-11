<?php
$_POST['import'] = true;
$_FILES['file_excel'] = [
    'name' => 'tmp_import_test.csv',
    'type' => 'text/csv',
    'tmp_name' => __DIR__ . '/tmp_import_test.csv',
    'error' => 0,
    'size' => 1,
];
include __DIR__ . '/pages/proses_import.php';
