<?php
$_FILES['file_excel'] = [
    'name' => 'tmp_import_test.csv',
    'type' => 'text/csv',
    'tmp_name' => 'c:/xampp/htdocs/proker_balaidesa/tmp_import_test.csv',
    'error' => 0,
    'size' => filesize('c:/xampp/htdocs/proker_balaidesa/tmp_import_test.csv'),
];
$_POST['import'] = 1;

require_once 'pages/proses_import.php';
