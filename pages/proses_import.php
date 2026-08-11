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

spl_autoload_register(function ($class) {
    $phpSpreadsheetPrefix = 'PhpOffice\\PhpSpreadsheet\\';
    if (strncmp($class, $phpSpreadsheetPrefix, strlen($phpSpreadsheetPrefix)) === 0) {
        $relativeClass = substr($class, strlen($phpSpreadsheetPrefix));
        $relativePath = str_replace('\\', '/', $relativeClass) . '.php';
        $filePath = __DIR__ . '/../vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/' . $relativePath;
        if (is_file($filePath)) {
            require_once $filePath;
            return true;
        }
    }

    $psrPrefix = 'Psr\\SimpleCache\\';
    if (strncmp($class, $psrPrefix, strlen($psrPrefix)) === 0) {
        $relativeClass = substr($class, strlen($psrPrefix));
        $relativePath = str_replace('\\', '/', $relativeClass) . '.php';
        $filePath = __DIR__ . '/../vendor/psr/simple-cache/src/' . $relativePath;
        if (is_file($filePath)) {
            require_once $filePath;
            return true;
        }
    }

    return false;
});

if (isset($_GET['test'])) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "test=1 mode\n";
    echo "autoload exists: " . (is_file($autoloadPath) ? 'yes' : 'no') . "\n";
    echo "IOFactory class: " . (class_exists('PhpOffice\\PhpSpreadsheet\\IOFactory') ? 'yes' : 'no') . "\n";
    echo "Csv class: " . (class_exists('PhpOffice\\PhpSpreadsheet\\Reader\\Csv') ? 'yes' : 'no') . "\n";
    echo "Date class: " . (class_exists('PhpOffice\\PhpSpreadsheet\\Shared\\Date') ? 'yes' : 'no') . "\n";
    echo "requested method: " . $_SERVER['REQUEST_METHOD'] . "\n";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['import'])) {
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
    if (empty($_FILES['file_excel']['tmp_name'])) {
        echo "<script>alert('File Excel tidak valid atau gagal diunggah.'); window.location='../index.php?page=penduduk';</script>";
        exit;
    }

    $fileTmpPath = $_FILES['file_excel']['tmp_name'];
    $fileName = $_FILES['file_excel']['name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!is_uploaded_file($fileTmpPath) || !is_readable($fileTmpPath)) {
        echo "<script>alert('File tidak bisa dibaca dari server.'); window.location='../index.php?page=penduduk';</script>";
        exit;
    }

    $inserted = 0;
    $skipped = 0;
    $errors = [];

    $normalizeHeader = function ($value) {
        $value = strtolower(trim((string) $value));
        $value = preg_replace('/[^a-z0-9]+/', '', $value);
        return $value;
    };

    $canonicalHeaderMap = function ($headerName) use ($normalizeHeader) {
        $headerName = $normalizeHeader($headerName);
        $map = [
            'rt' => 'rt',
            'rw' => 'rw',
            'nokk' => 'no_kk',
            'kepalakk' => 'kepala_kk',
            'kepalakeluarga' => 'kepala_kk',
            'nik' => 'nik',
            'nama' => 'nama',
            'jeniskelamin' => 'jenis_kelamin',
            'jk' => 'jenis_kelamin',
            'statuskeluarga' => 'status_keluarga',
            'tempatlahir' => 'tempat_lahir',
            'tanggallahir' => 'tgl_lahir',
            'tgllahir' => 'tgl_lahir',
            'statusperkawinan' => 'status_pernikahan',
            'statuspernikahan' => 'status_pernikahan',
            'agama' => 'agama',
            'kewarganegaraan' => 'kewarganegaraan',
            'kewarganegara' => 'kewarganegaraan',
            'suku' => 'suku',
            'pendidikan' => 'pendidikan',
            'pekerjaan' => 'pekerjaan',
            'alamat' => 'alamat',
            'alamattinggal' => 'alamat',
            'umur' => 'umur',
        ];

        return $map[$headerName] ?? null;
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

    try {
        if ($fileExt === 'csv') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            $reader->setInputEncoding('UTF-8');
            $reader->setDelimiter($parseCsvDelimiter($fileTmpPath));
            $spreadsheet = $reader->load($fileTmpPath);
        } else {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($fileTmpPath);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($fileTmpPath);
        }

        $rows = $spreadsheet->getActiveSheet()->toArray();
    } catch (Throwable $e) {
        $errors[] = 'Gagal membaca file: ' . $e->getMessage();
        $rows = [];
    }

    if (!empty($errors)) {
        echo "<script>alert('" . addslashes(implode("\n", $errors)) . "'); window.location='../index.php?page=penduduk';</script>";
        exit;
    }

    $headerRowIndex = null;
    foreach ($rows as $idx => $row) {
        foreach ($row as $cellValue) {
            $normalized = $normalizeHeader($cellValue);
            if (in_array($normalized, ['nik', 'nama', 'nokk', 'rt', 'rw', 'jeniskelamin', 'pekerjaan', 'alamat'], true)) {
                $headerRowIndex = $idx;
                break 2;
            }
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

    $dataRows = [];
    if ($headerRowIndex !== null) {
        for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
            $dataRows[] = $rows[$i];
        }
    }

    foreach ($dataRows as $rowIndex => $row) {
        $mapped = [];
        foreach ($row as $sourceKey => $value) {
            if (isset($headerMap[$sourceKey])) {
                $mapped[$headerMap[$sourceKey]] = $value;
            }
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