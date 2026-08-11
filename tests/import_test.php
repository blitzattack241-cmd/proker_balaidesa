<?php
require_once __DIR__ . '/../api/import_helpers.php';

$headers = ['Nama Lengkap','NIK','Jenis Kelamin','Tgl Lahir','Alamat'];
$s = suggest_mappings($headers);
echo "Suggestions:\n";
print_r($s);

// normalization tests
echo "\nNormalize tests:\n";
foreach ($headers as $h) echo "$h => " . normalize_header($h) . "\n";
