<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../koneksi.php';
if (!$koneksi) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (is_file($autoloadPath)) {
    require_once $autoloadPath;
}

$manualAutoloadMap = [
    'PhpOffice\\PhpSpreadsheet\\' => __DIR__ . '/../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/',
    'Psr\\SimpleCache\\' => __DIR__ . '/../vendor/psr/simple-cache/src/',
    'Composer\\Pcre\\' => __DIR__ . '/../vendor/composer/pcre/src/',
];

spl_autoload_register(function ($class) use ($manualAutoloadMap) {
    foreach ($manualAutoloadMap as $prefix => $baseDir) {
        if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
            continue;
        }

        $relativeClass = substr($class, strlen($prefix));
        $relativePath = str_replace('\\', '/', $relativeClass) . '.php';
        $filePath = $baseDir . $relativePath;
        if (is_file($filePath)) {
            require_once $filePath;
            return true;
        }
    }

    return false;
});

$explicitLoads = [
    'PhpOffice\\PhpSpreadsheet\\IOFactory' => __DIR__ . '/../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/IOFactory.php',
    'PhpOffice\\PhpSpreadsheet\\Shared\\Date' => __DIR__ . '/../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Shared/Date.php',
    'PhpOffice\\PhpSpreadsheet\\Reader\\Csv' => __DIR__ . '/../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Reader/Csv.php',
    'Psr\\SimpleCache\\CacheInterface' => __DIR__ . '/../vendor/psr/simple-cache/src/CacheInterface.php',
    'Composer\\Pcre\\Preg' => __DIR__ . '/../vendor/composer/pcre/src/Preg.php',
];

foreach ($explicitLoads as $class => $path) {
    if ((class_exists($class, false) === false && interface_exists($class, false) === false) && is_file($path)) {
        require_once $path;
    }
}

if (isset($_GET['test'])) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "test=1 mode\n";
    echo "cwd: " . getcwd() . "\n";
    echo "autoload exists: " . (is_file($autoloadPath) ? 'yes' : 'no') . "\n";
    foreach ($explicitLoads as $className => $filePath) {
        echo "exists($className): " . (is_file($filePath) ? 'yes' : 'no') . " => $filePath\n";
    }
    echo "IOFactory class: " . (class_exists('PhpOffice\\PhpSpreadsheet\\IOFactory') ? 'yes' : 'no') . "\n";
    echo "Csv class: " . (class_exists('PhpOffice\\PhpSpreadsheet\\Reader\\Csv') ? 'yes' : 'no') . "\n";
    echo "Date class: " . (class_exists('PhpOffice\\PhpSpreadsheet\\Shared\\Date') ? 'yes' : 'no') . "\n";
    echo "requested method: " . $_SERVER['REQUEST_METHOD'] . "\n";
    exit;
}

$debugMode = isset($_GET['debug']) || isset($_POST['debug']);
$debugOutput = function (array $lines) {
    if (!headers_sent()) {
        header('Content-Type: text/plain; charset=utf-8');
    }
    echo "IMPORT DEBUG MODE\n\n";
    foreach ($lines as $line) {
        echo $line . "\n";
    }
    exit;
};

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || (!isset($_POST['import']) && !$debugMode)) {
    if ($debugMode) {
        $debugOutput([
            'Request method: ' . $_SERVER['REQUEST_METHOD'],
            'Import flag present: ' . (isset($_POST['import']) ? 'yes' : 'no'),
            'Debug mode: yes',
        ]);
    }
    header('Location: ../index.php?page=penduduk');
    exit;
}

$columnsToEnsure = [
    'rt' => 'VARCHAR(5) DEFAULT NULL',
    'rw' => 'VARCHAR(5) DEFAULT NULL',
    'no_kk' => 'VARCHAR(20) DEFAULT NULL',
    'kepala_kk' => 'VARCHAR(100) DEFAULT NULL',
    'jenis_kelamin' => 'VARCHAR(20) DEFAULT NULL',
    'status_keluarga' => 'VARCHAR(50) DEFAULT NULL',
    'tempat_lahir' => 'VARCHAR(50) DEFAULT NULL',
    'tgl_lahir' => 'DATE DEFAULT NULL',
    'status_pernikahan' => 'VARCHAR(50) DEFAULT NULL',
    'agama' => 'VARCHAR(30) DEFAULT NULL',
    'kewarganegaraan' => 'VARCHAR(50) DEFAULT NULL',
    'suku' => 'VARCHAR(50) DEFAULT NULL',
    'pendidikan' => 'VARCHAR(100) DEFAULT NULL',
    'pekerjaan' => 'VARCHAR(100) DEFAULT NULL',
    'umur' => 'INT(11) DEFAULT NULL',
    'alamat' => 'TEXT DEFAULT NULL',
    'tempat_tgl_lahir' => 'VARCHAR(100) DEFAULT NULL',
];

$existingColsQuery = mysqli_query($koneksi, 'SHOW COLUMNS FROM `tb_penduduk`');
$existingCols = [];
if ($existingColsQuery) {
    while ($col = mysqli_fetch_assoc($existingColsQuery)) {
        $existingCols[] = $col['Field'];
    }
}

foreach ($columnsToEnsure as $colName => $colDef) {
    if (!in_array($colName, $existingCols, true)) {
        mysqli_query($koneksi, "ALTER TABLE `tb_penduduk` ADD COLUMN `$colName` $colDef");
    }
}

if (isset($_POST['import'])) {
    if (empty($_FILES['file_excel']['tmp_name']) || ($_FILES['file_excel']['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        $uploadError = $_FILES['file_excel']['error'] ?? 'unknown';
        $message = 'File Excel tidak valid atau gagal diunggah. Error: ' . $uploadError;
        if ($debugMode) {
            $debugOutput([
                $message,
                'file_excel keys: ' . json_encode(array_keys($_FILES['file_excel'] ?? []), JSON_UNESCAPED_UNICODE),
            ]);
        }
        echo "<script>alert('" . addslashes($message) . "'); window.location='../index.php?page=penduduk';</script>";
        exit;
    }

    $fileTmpPath = $_FILES['file_excel']['tmp_name'];
    $fileName = $_FILES['file_excel']['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!is_uploaded_file($fileTmpPath) || !is_readable($fileTmpPath)) {
        $message = 'File tidak bisa dibaca dari server.';
        if ($debugMode) {
            $debugOutput([
                $message,
                'tmp_name: ' . $fileTmpPath,
                'is_uploaded_file: ' . (is_uploaded_file($fileTmpPath) ? 'yes' : 'no'),
                'is_readable: ' . (is_readable($fileTmpPath) ? 'yes' : 'no'),
            ]);
        }
        echo "<script>alert('" . addslashes($message) . "'); window.location='../index.php?page=penduduk';</script>";
        exit;
    }

    $inserted = 0;
    $skipped = 0;
    $errors = [];

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
        if (str_contains($headerName, 'suku') || str_contains($headerName, 'etnis')) {
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

    $clean = function ($value) use ($koneksi) {
        if ($value === null || $value === false) {
            return '';
        }

        $value = trim((string) $value);
        $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);
        return mysqli_real_escape_string($koneksi, $value);
    };

    $normalizeStatusPernikahan = function ($value) {
        $status = strtolower(trim((string) $value));
        if ($status === '') {
            return 'Belum Kawin';
        }
        if (str_contains($status, 'belum')) {
            return 'Belum Kawin';
        }
        if (str_contains($status, 'janda') || str_contains($status, 'duda') || str_contains($status, 'cerai')) {
            return 'Cerai / Janda / Duda';
        }
        if (str_contains($status, 'kawin') || str_contains($status, 'nikah')) {
            return 'Kawin';
        }
        return 'Belum Kawin';
    };

    $parseDate = function ($value) {
        if ($value === null || $value === false || trim((string) $value) === '') {
            return null;
        }

        if (is_numeric($value)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
            } catch (Throwable $e) {
                return null;
            }
        }

        $timestamp = strtotime(str_replace('/', '-', (string) $value));
        return $timestamp ? date('Y-m-d', $timestamp) : null;
    };

    $parseCsvDelimiter = function ($path) {
        $handle = fopen($path, 'r');
        if ($handle === false) {
            return ',';
        }

        $line = fgets($handle);
        fclose($handle);

        if ($line === false) {
            return ',';
        }

        $commaCount = substr_count($line, ',');
        $semicolonCount = substr_count($line, ';');
        return $semicolonCount > $commaCount ? ';' : ',';
    };

    $loadCsvRows = function ($path) use ($parseCsvDelimiter) {
        $rows = [];
        $delimiter = $parseCsvDelimiter($path);
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            return $rows;
        }

        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            if (count($data) === 1 && $data[0] === null) {
                continue;
            }
            if (isset($data[0])) {
                $data[0] = preg_replace('/^\x{FEFF}/u', '', $data[0]);
            }
            $rows[] = $data;
        }
        fclose($handle);
        return $rows;
    };

    try {
        if ($fileExt === 'csv') {
            $rows = $loadCsvRows($fileTmpPath);
        } else {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileTmpPath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($fileTmpPath);
            $rows = $spreadsheet->getActiveSheet()->toArray();
        }
    } catch (Throwable $e) {
        $errors[] = 'Gagal membaca file: ' . $e->getMessage();
        $rows = [];
    }

    if (!empty($errors)) {
        if ($debugMode) {
            $debugOutput(array_merge([
                'File: ' . $fileName,
                'File extension: ' . $fileExt,
                'Rows read: ' . count($rows),
                'Header row index: not yet determined',
                'Header row: not available',
            ], $errors));
        }
        echo "<script>alert('" . addslashes(implode("\n", $errors)) . "'); window.location='../index.php?page=penduduk';</script>";
        exit;
    }

    $headerRowIndex = null;
    foreach ($rows as $idx => $row) {
        $hasNik = false;
        $hasNama = false;
        foreach ($row as $cellValue) {
            $normalized = $normalizeHeader($cellValue);
            $canonical = $canonicalHeaderMap($normalized);
            if ($canonical === 'nik') {
                $hasNik = true;
            }
            if ($canonical === 'nama') {
                $hasNama = true;
            }
        }
        if ($hasNik && $hasNama) {
            $headerRowIndex = $idx;
            break;
        }
    }

    if ($headerRowIndex === null && !empty($rows)) {
        $headerRowIndex = 0;
    }

    $headerRow = $headerRowIndex !== null ? $rows[$headerRowIndex] : [];
    $headerMap = [];
    foreach ($headerRow as $sourceKey => $headerValue) {
        $canonical = $canonicalHeaderMap($headerValue);
        if ($canonical !== null) {
            $headerMap[$sourceKey] = $canonical;
        }
    }

    $headerMapValues = array_values($headerMap);
    if (!in_array('nik', $headerMapValues, true) || !in_array('nama', $headerMapValues, true)) {
        $message = 'Header Excel tidak valid. Pastikan file memiliki kolom NIK dan Nama.';
        if ($debugMode) {
            $debugOutput([
                'Header validation failed',
                'headerRowIndex: ' . ($headerRowIndex === null ? 'none' : $headerRowIndex),
                'headerRow: ' . json_encode($headerRow, JSON_UNESCAPED_UNICODE),
                'headerMap: ' . json_encode($headerMap, JSON_UNESCAPED_UNICODE),
            ]);
        }
        echo "<script>alert('" . addslashes($message) . "'); window.location='../index.php?page=penduduk';</script>";
        exit;
    }

    $dataRows = [];
    if ($headerRowIndex !== null) {
        for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
            $dataRows[] = $rows[$i];
        }
    }

    if ($debugMode) {
        $debugOutput([
            'fileName: ' . $fileName,
            'fileExt: ' . $fileExt,
            'rowsRead: ' . count($rows),
            'headerRowIndex: ' . ($headerRowIndex === null ? 'none' : $headerRowIndex),
            'headerRow: ' . json_encode($headerRow, JSON_UNESCAPED_UNICODE),
            'headerMap: ' . json_encode($headerMap, JSON_UNESCAPED_UNICODE),
            'dataRowsCount: ' . count($dataRows),
            'firstDataRow: ' . json_encode($dataRows[0] ?? [], JSON_UNESCAPED_UNICODE),
        ]);
    }

    foreach ($dataRows as $rowIndex => $row) {
        $mapped = [];
        foreach ($row as $sourceKey => $value) {
            if (isset($headerMap[$sourceKey])) {
                $mapped[$headerMap[$sourceKey]] = $value;
            }
        }

        if ($debugMode && $rowIndex < 3) {
            $debugOutput([
                'processing row index: ' . $rowIndex,
                'rawRow: ' . json_encode($row, JSON_UNESCAPED_UNICODE),
                'mappedRow: ' . json_encode($mapped, JSON_UNESCAPED_UNICODE),
            ]);
        }

        $record = [
            'rt' => $clean($mapped['rt'] ?? ''),
            'rw' => $clean($mapped['rw'] ?? ''),
            'no_kk' => $clean($mapped['no_kk'] ?? ''),
            'kepala_kk' => $clean($mapped['kepala_kk'] ?? ''),
            'nik' => $clean($mapped['nik'] ?? ''),
            'nama' => $clean($mapped['nama'] ?? ''),
            'jenis_kelamin' => $clean($mapped['jenis_kelamin'] ?? ''),
            'status_keluarga' => $clean($mapped['status_keluarga'] ?? ''),
            'tempat_lahir' => $clean($mapped['tempat_lahir'] ?? ''),
            'tgl_lahir' => $parseDate($mapped['tgl_lahir'] ?? ''),
            'status_pernikahan' => $clean($normalizeStatusPernikahan($mapped['status_pernikahan'] ?? '')),
            'agama' => $clean($mapped['agama'] ?? ''),
            'kewarganegaraan' => $clean($mapped['kewarganegaraan'] ?? ''),
            'suku' => $clean($mapped['suku'] ?? ''),
            'pendidikan' => $clean($mapped['pendidikan'] ?? ''),
            'pekerjaan' => $clean($mapped['pekerjaan'] ?? ''),
            'alamat' => $clean($mapped['alamat'] ?? ''),
            'umur' => $clean($mapped['umur'] ?? ''),
        ];

        if ($record['nik'] === '' || $record['nama'] === '') {
            $skipped++;
            continue;
        }

        $nikEsc = mysqli_real_escape_string($koneksi, $record['nik']);
        $existingQuery = mysqli_query($koneksi, "SELECT id FROM tb_penduduk WHERE nik = '$nikEsc' LIMIT 1");
        $existingRow = $existingQuery ? mysqli_fetch_assoc($existingQuery) : null;

        $tglLahirSql = ($record['tgl_lahir'] === null || $record['tgl_lahir'] === '') ? 'NULL' : "'" . mysqli_real_escape_string($koneksi, $record['tgl_lahir']) . "'";
        $umurSql = $record['umur'] === '' ? 'NULL' : (int) $record['umur'];
        $tempatTglLahirValue = $record['tgl_lahir'] !== null && $record['tgl_lahir'] !== ''
            ? $record['tempat_lahir'] . ', ' . $record['tgl_lahir']
            : $record['tempat_lahir'];

        $sql = $existingRow
            ? "UPDATE tb_penduduk SET
                rt = '" . $record['rt'] . "',
                rw = '" . $record['rw'] . "',
                no_kk = '" . $record['no_kk'] . "',
                kepala_kk = '" . $record['kepala_kk'] . "',
                nama = '" . $record['nama'] . "',
                jenis_kelamin = '" . $record['jenis_kelamin'] . "',
                status_keluarga = '" . $record['status_keluarga'] . "',
                tempat_lahir = '" . $record['tempat_lahir'] . "',
                tgl_lahir = $tglLahirSql,
                status_pernikahan = '" . $record['status_pernikahan'] . "',
                agama = '" . $record['agama'] . "',
                kewarganegaraan = '" . $record['kewarganegaraan'] . "',
                suku = '" . $record['suku'] . "',
                pendidikan = '" . $record['pendidikan'] . "',
                pekerjaan = '" . $record['pekerjaan'] . "',
                alamat = '" . $record['alamat'] . "',
                umur = $umurSql,
                tempat_tgl_lahir = '" . mysqli_real_escape_string($koneksi, $tempatTglLahirValue) . "'
              WHERE nik = '$nikEsc'"
            : "INSERT INTO tb_penduduk (
                rt, rw, no_kk, kepala_kk, nik, nama, jenis_kelamin, status_keluarga, tempat_lahir, tgl_lahir, status_pernikahan, agama, kewarganegaraan, suku, pendidikan, pekerjaan, alamat, umur, tempat_tgl_lahir
              ) VALUES (
                '" . $record['rt'] . "',
                '" . $record['rw'] . "',
                '" . $record['no_kk'] . "',
                '" . $record['kepala_kk'] . "',
                '" . $record['nik'] . "',
                '" . $record['nama'] . "',
                '" . $record['jenis_kelamin'] . "',
                '" . $record['status_keluarga'] . "',
                '" . $record['tempat_lahir'] . "',
                $tglLahirSql,
                '" . $record['status_pernikahan'] . "',
                '" . $record['agama'] . "',
                '" . $record['kewarganegaraan'] . "',
                '" . $record['suku'] . "',
                '" . $record['pendidikan'] . "',
                '" . $record['pekerjaan'] . "',
                '" . $record['alamat'] . "',
                $umurSql,
                '" . mysqli_real_escape_string($koneksi, $tempatTglLahirValue) . "'
              )";

        $result = mysqli_query($koneksi, $sql);
        if ($result) {
            $inserted++;
        } else {
            $errors[] = 'Baris ' . ($rowIndex + 2) . ' (NIK: ' . $record['nik'] . '): ' . mysqli_error($koneksi);
        }
    }

    $summary = $errors
        ? "Import selesai. Ditambahkan/diupdate: $inserted. Dilewati: $skipped.\nError:\n" . implode("\n", array_slice($errors, 0, 5))
        : "Berhasil memproses $inserted data penduduk. Dilewati: $skipped.";

    echo "<script>alert('" . addslashes($summary) . "'); window.location='../index.php?page=penduduk';</script>";
    exit;
}
?>