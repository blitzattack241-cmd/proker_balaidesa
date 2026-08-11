<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Csv;

$path = __DIR__ . '/tmp_import_test.csv';
if (!is_file($path)) {
    echo "Missing file: $path\n";
    exit(1);
}

$reader = new Csv();
$reader->setInputEncoding('UTF-8');
$reader->setDelimiter(',');
$spreadsheet = $reader->load($path);
$rows = $spreadsheet->getActiveSheet()->toArray();

echo "rows count=" . count($rows) . "\n";
foreach ($rows as $idx => $row) {
    echo "row[$idx] => " . json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
}

$normalizeHeader = function ($value) {
    $value = (string) $value;
    $value = trim($value);
    $value = preg_replace('/\x{FEFF}/u', '', $value);
    $value = mb_strtolower($value, 'UTF-8');
    $value = preg_replace('/[^\p{L}\p{N}]+/u', '', $value);
    return $value;
};

$canonicalHeaderMap = function ($headerName) use ($normalizeHeader) {
    $headerName = $normalizeHeader($headerName);
    $map = [
        'rt' => 'rt',
        'rw' => 'rw',
        'nokk' => 'no_kk',
        'nokkk' => 'no_kk',
        'no_kk' => 'no_kk',
        'noktp' => 'nik',
        'no_ktp' => 'nik',
        'nik' => 'nik',
        'nikk' => 'nik',
        'nama' => 'nama',
        'namalengkap' => 'nama',
        'namaanggota' => 'nama',
        'namaanggotakeluarga' => 'nama',
        'nama_lengkap' => 'nama',
        'kepalakk' => 'kepala_kk',
        'kepalakeluarga' => 'kepala_kk',
        'nama_kepala' => 'kepala_kk',
        'nama_kepala_keluarga' => 'kepala_kk',
        'jeniskelamin' => 'jenis_kelamin',
        'jk' => 'jenis_kelamin',
        'sex' => 'jenis_kelamin',
        'gender' => 'jenis_kelamin',
        'statuskeluarga' => 'status_keluarga',
        'status_keluarga' => 'status_keluarga',
        'hubungan' => 'status_keluarga',
        'tempatlahir' => 'tempat_lahir',
        'ttl' => 'tempat_lahir',
        'tanggallahir' => 'tgl_lahir',
        'tgllahir' => 'tgl_lahir',
        'tanggal_lahir' => 'tgl_lahir',
        'statusperkawinan' => 'status_pernikahan',
        'statuspernikahan' => 'status_pernikahan',
        'agama' => 'agama',
        'kewarganegaraan' => 'kewarganegaraan',
        'kewarganegara' => 'kewarganegaraan',
        'suku' => 'suku',
        'etnis' => 'suku',
        'pendidikan' => 'pendidikan',
        'pekerjaan' => 'pekerjaan',
        'alamat' => 'alamat',
        'alamattinggal' => 'alamat',
        'umur' => 'umur',
    ];

    if (isset($map[$headerName])) {
        return $map[$headerName];
    }
    if (str_contains($headerName, 'nik')) {
        return 'nik';
    }
    if (str_contains($headerName, 'nama')) {
        return 'nama';
    }
    if (str_contains($headerName, 'kk') && str_contains($headerName, 'no')) {
        return 'no_kk';
    }
    if (str_contains($headerName, 'rt')) {
        return 'rt';
    }
    if (str_contains($headerName, 'rw')) {
        return 'rw';
    }
    if (str_contains($headerName, 'jk') || str_contains($headerName, 'jenis') || str_contains($headerName, 'gender') || str_contains($headerName, 'sex')) {
        return 'jenis_kelamin';
    }
    if (str_contains($headerName, 'keluarga') || str_contains($headerName, 'hubungan')) {
        return 'status_keluarga';
    }
    if (str_contains($headerName, 'tempat') && str_contains($headerName, 'lahir')) {
        return 'tempat_lahir';
    }
    if (str_contains($headerName, 'ttl')) {
        return 'tempat_lahir';
    }
    if (str_contains($headerName, 'tanggal') || str_contains($headerName, 'tgllahir') || str_contains($headerName, 'tanggallahir')) {
        return 'tgl_lahir';
    }
    if (str_contains($headerName, 'nikah')) {
        return 'status_pernikahan';
    }
    if (str_contains($headerName, 'agama')) {
        return 'agama';
    }
    if (str_contains($headerName, 'kewarga')) {
        return 'kewarganegaraan';
    }
    if (str_contains($headerName, 'suku')) {
        return 'suku';
    }
    if (str_contains($headerName, 'pendidikan')) {
        return 'pendidikan';
    }
    if (str_contains($headerName, 'pekerjaan')) {
        return 'pekerjaan';
    }
    if (str_contains($headerName, 'alamat')) {
        return 'alamat';
    }
    if (str_contains($headerName, 'umur')) {
        return 'umur';
    }
    return null;
};

echo "Header normalization and canonical mapping:\n";
if (!empty($rows)) {
    foreach ($rows[0] as $col => $value) {
        $normalized = $normalizeHeader($value);
        $canon = $canonicalHeaderMap($value);
        echo "  col=$col raw='" . $value . "' normalized='" . $normalized . "' canonical='" . $canon . "'\n";
    }
}

echo "\nHeader row detection:\n";
foreach ($rows as $idx => $row) {
    $hasNik = false;
    $hasNama = false;
    foreach ($row as $headerValue) {
        $normalized = $normalizeHeader($headerValue);
        $canonical = $canonicalHeaderMap($headerValue);
        if ($canonical === 'nik') {
            $hasNik = true;
        }
        if ($canonical === 'nama') {
            $hasNama = true;
        }
        echo "row[$idx] cell='" . $headerValue . "' normalized='" . $normalized . "' canonical='" . $canonical . "'\n";
    }
    echo "  hasNik=" . ($hasNik ? 'yes' : 'no') . " hasNama=" . ($hasNama ? 'yes' : 'no') . "\n";
}
