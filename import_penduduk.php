<?php
// Pastikan kamu sudah menginstal phpspreadsheet via composer: composer require phpoffice/phpspreadsheet
$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (is_file($autoloadPath) && is_readable($autoloadPath)) {
    require_once $autoloadPath;
} else {
    $path = realpath($autoloadPath) ?: $autoloadPath;
    echo "<script>alert('Composer autoload tidak ditemukan atau tidak dapat dibaca: " . addslashes($path) . "'); window.location='import_penduduk.php';</script>";
    exit;
}

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!class_exists('PhpOffice\\PhpSpreadsheet\\IOFactory', true)) {
    $ioFactoryPath = __DIR__ . '/vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/IOFactory.php';
    if (is_file($ioFactoryPath) && is_readable($ioFactoryPath)) {
        require_once $ioFactoryPath;
    }
}

if (!class_exists('PhpOffice\\PhpSpreadsheet\\IOFactory', true)) {
    $ioFactoryPath = realpath(__DIR__ . '/vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/IOFactory.php') ?: __DIR__ . '/vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/IOFactory.php';
    echo "<script>alert('PhpSpreadsheet IOFactory class tidak ditemukan. Pastikan paket phpoffice/phpspreadsheet terpasang. Path: " . addslashes($ioFactoryPath) . "'); window.location='import_penduduk.php';</script>";
    exit;
}

require_once __DIR__ . '/koneksi.php';
$debugMode = isset($_GET['debug']) || isset($_POST['debug']);

if (isset($_POST['import'])) {
    $fileName = $_FILES['file_excel']['name'] ?? '';
    $fileTmp = $_FILES['file_excel']['tmp_name'] ?? null;
    $allowedExtensions = ['xlsx', 'xls'];
    $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!$fileTmp || !is_uploaded_file($fileTmp)) {
        echo "<script>alert('File Excel tidak valid atau gagal diunggah.'); window.location='import_penduduk.php';</script>";
        exit;
    }

    if (!in_array($extension, $allowedExtensions, true)) {
        echo "<script>alert('Ekstensi file tidak didukung. Gunakan .xlsx atau .xls.'); window.location='import_penduduk.php';</script>";
        exit;
    }

    try {
        $spreadsheet = IOFactory::load($fileTmp);
    } catch (Throwable $e) {
        echo "<script>alert('Gagal memuat file Excel: " . addslashes($e->getMessage()) . "'); window.location='import_penduduk.php';</script>";
        exit;
    }

    $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    $inserted = 0;
    $rowCount = count($sheetData);

    $normalizeHeader = function ($value) {
        $value = trim((string) $value);
        $value = preg_replace('/\x{FEFF}/u', '', $value);
        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/', '_', $value);
        return trim($value, '_');
    };

    $expectedColumns = [
        'nik' => ['nik', 'no_ktp', 'nokk', 'no_kk'],
        'nama' => ['nama', 'nama_lengkap'],
        'no_kk' => ['no_kk', 'nokk', 'no_kk'],
        'rt' => ['rt'],
        'rw' => ['rw'],
        'jenis_kelamin' => ['jk', 'jenis_kelamin', 'jeniskelamin', 'gender', 'sex'],
        'alamat' => ['alamat'],
        'tempat_lahir' => ['tempat_lahir', 'tempat_lahir', 'ttl'],
        'tanggal_lahir' => ['tanggal_lahir', 'tgl_lahir', 'tanggal_lahir', 'tgllahir', 'tanggallahir'],
        'umur' => ['umur'],
        'agama' => ['agama'],
        'pekerjaan' => ['pekerjaan'],
    ];

    $headerRow = [];
    foreach ($sheetData as $rowIndex => $row) {
        $hasNik = false;
        $hasNama = false;
        foreach ($row as $headerValue) {
            $normalizedHeader = $normalizeHeader($headerValue);
            foreach ($expectedColumns as $field => $aliases) {
                if (in_array($normalizedHeader, $aliases, true)) {
                    if ($field === 'nik') {
                        $hasNik = true;
                    }
                    if ($field === 'nama') {
                        $hasNama = true;
                    }
                }
            }
        }
        if ($hasNik && $hasNama) {
            $headerRow = $row;
            break;
        }
    }

    if (empty($headerRow)) {
        $headerRow = reset($sheetData) ?: [];
    }

    $columnMap = [];
    foreach ($headerRow as $col => $headerValue) {
        $normalizedHeader = $normalizeHeader($headerValue);
        foreach ($expectedColumns as $field => $aliases) {
            if (in_array($normalizedHeader, $aliases, true)) {
                $columnMap[$field] = $col;
                break;
            }
        }
    }

    if (empty($columnMap['nik']) || empty($columnMap['nama'])) {
        if ($debugMode) {
            header('Content-Type: text/plain; charset=utf-8');
            echo "DEBUG HEADER MAPPING\n";
            echo "headerRow: " . json_encode($headerRow, JSON_UNESCAPED_UNICODE) . "\n";
            echo "columnMap: " . json_encode($columnMap, JSON_UNESCAPED_UNICODE) . "\n";
            echo "normalized headers: \n";
            foreach ($headerRow as $col => $headerValue) {
                echo "$col => " . $normalizeHeader($headerValue) . "\n";
            }
            exit;
        }
        echo "<script>alert('Header Excel tidak valid. Pastikan file memiliki kolom NIK dan Nama.'); window.location='import_penduduk.php';</script>";
        exit;
    }

    $stmt = mysqli_prepare(
        $koneksi,
        "INSERT INTO tb_penduduk (nik, no_kk, nama, jenis_kelamin, tempat_tgl_lahir, umur, agama, pekerjaan, alamat, rt, rw)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE
            nama = VALUES(nama),
            no_kk = VALUES(no_kk),
            jenis_kelamin = VALUES(jenis_kelamin),
            tempat_tgl_lahir = VALUES(tempat_tgl_lahir),
            umur = VALUES(umur),
            agama = VALUES(agama),
            pekerjaan = VALUES(pekerjaan),
            alamat = VALUES(alamat),
            rt = VALUES(rt),
            rw = VALUES(rw)"
    );

    if ($stmt === false) {
        $error = mysqli_error($koneksi);
        echo "<script>alert('Gagal menyiapkan query import: " . addslashes($error) . "'); window.location='import_penduduk.php';</script>";
        exit;
    }

    for ($i = 2; $i <= $rowCount; $i++) {
        $row = $sheetData[$i] ?? [];
        $alamat = trim($row[$columnMap['alamat']] ?? '');
        $rt = trim($row[$columnMap['rt']] ?? '');
        $rw = trim($row[$columnMap['rw']] ?? '');
        $nama = trim($row[$columnMap['nama']] ?? '');
        $no_kk = trim($row[$columnMap['no_kk']] ?? '');
        $nik = trim($row[$columnMap['nik']] ?? '');
        $jenis_kelamin = trim($row[$columnMap['jenis_kelamin']] ?? '');

        $tempat = trim($row[$columnMap['tempat_lahir']] ?? '');
        $tanggal = trim($row[$columnMap['tanggal_lahir']] ?? '');
        $tempat_tgl_lahir = $tempat;
        if ($tanggal !== '') {
            $tempat_tgl_lahir = $tempat !== '' ? $tempat . ', ' . $tanggal : $tanggal;
        }

        $umurValue = trim($row[$columnMap['umur']] ?? '');
        $umur = $umurValue === '' ? null : (int) $umurValue;
        $agama = trim($row[$columnMap['agama']] ?? '');
        $pekerjaan = trim($row[$columnMap['pekerjaan']] ?? '');

        if ($nik === '' || $nama === '') {
            continue;
        }

        mysqli_stmt_bind_param(
            $stmt,
            'ssssissssss',
            $nik,
            $no_kk,
            $nama,
            $jenis_kelamin,
            $tempat_tgl_lahir,
            $umur,
            $agama,
            $pekerjaan,
            $alamat,
            $rt,
            $rw
        );

        if (mysqli_stmt_execute($stmt)) {
            $inserted++;
        } else {
            $error = mysqli_stmt_error($stmt);
            echo "<script>alert('Gagal mengimpor baris $i: " . addslashes($error) . "'); window.location='import_penduduk.php';</script>";
            mysqli_stmt_close($stmt);
            exit;
        }
    }

    mysqli_stmt_close($stmt);
    echo "<script>alert('Berhasil mengimpor $inserted data penduduk!'); window.location='index.php?page=penduduk';</script>";
}
?>

<!-- Form Upload File Excel -->
<form action="" method="POST" enctype="multipart/form-data" class="p-4 border rounded bg-light">
    <h5>Upload Data Penduduk (Excel)</h5>
    <input type="file" name="file_excel" accept=".xlsx, .xls" required class="form-control mb-3">
    <button type="submit" name="import" class="btn btn-primary">Import Data</button>
</form>