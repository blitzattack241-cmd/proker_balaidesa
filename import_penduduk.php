<?php
// Pastikan kamu sudah menginstal phpspreadsheet via composer: composer require phpoffice/phpspreadsheet
$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (!is_file($autoloadPath)) {
    echo "<script>alert('Composer autoload tidak ditemukan. Jalankan composer install terlebih dahulu.'); window.location='import_penduduk.php';</script>";
    exit;
}

require_once $autoloadPath;

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!class_exists('PhpOffice\\PhpSpreadsheet\\IOFactory')) {
    echo "<script>alert('PhpSpreadsheet tidak terpasang. Jalankan composer require phpoffice/phpspreadsheet.'); window.location='import_penduduk.php';</script>";
    exit;
}

require_once __DIR__ . '/koneksi.php';

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
        $alamat = trim($sheetData[$i]['A'] ?? '');
        $rt = trim($sheetData[$i]['B'] ?? '');
        $rw = trim($sheetData[$i]['C'] ?? '');
        $nama = trim($sheetData[$i]['D'] ?? '');
        $no_kk = trim($sheetData[$i]['E'] ?? '');
        $nik = trim($sheetData[$i]['F'] ?? '');
        $jenis_kelamin = trim($sheetData[$i]['G'] ?? '');
        $tempat_tgl_lahir = trim($sheetData[$i]['H'] ?? '');
        $umurValue = trim($sheetData[$i]['I'] ?? '');
        $umur = $umurValue === '' ? null : (int) $umurValue;
        $agama = trim($sheetData[$i]['J'] ?? '');
        $pekerjaan = trim($sheetData[$i]['K'] ?? '');

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