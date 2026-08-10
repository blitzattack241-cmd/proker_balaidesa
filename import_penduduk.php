<?php
// Pastikan kamu sudah menginstal phpspreadsheet via composer: composer require phpoffice/phpspreadsheet
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

require_once __DIR__ . '/koneksi.php';

if (isset($_POST['import'])) {
    $fileName = $_FILES['file_excel']['name'] ?? '';
    $fileTmp = $_FILES['file_excel']['tmp_name'] ?? null;
    $fileType = $_FILES['file_excel']['type'] ?? '';
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

    for ($i = 2; $i <= $rowCount; $i++) {
        $alamat = mysqli_real_escape_string($koneksi, $sheetData[$i]['A'] ?? '');
        $rt = mysqli_real_escape_string($koneksi, $sheetData[$i]['B'] ?? '');
        $rw = mysqli_real_escape_string($koneksi, $sheetData[$i]['C'] ?? '');
        $nama = mysqli_real_escape_string($koneksi, $sheetData[$i]['D'] ?? '');
        $no_kk = mysqli_real_escape_string($koneksi, $sheetData[$i]['E'] ?? '');
        $nik = mysqli_real_escape_string($koneksi, $sheetData[$i]['F'] ?? '');
        $jenis_kelamin = mysqli_real_escape_string($koneksi, $sheetData[$i]['G'] ?? '');
        $tempat_tgl_lahir = mysqli_real_escape_string($koneksi, $sheetData[$i]['H'] ?? '');
        $umur = isset($sheetData[$i]['I']) ? (int) $sheetData[$i]['I'] : null;
        $agama = mysqli_real_escape_string($koneksi, $sheetData[$i]['J'] ?? '');
        $pekerjaan = mysqli_real_escape_string($koneksi, $sheetData[$i]['K'] ?? '');

        if ($nik === '') {
            continue;
        }

        $query = "INSERT INTO tb_penduduk \
            (nik, no_kk, nama, jenis_kelamin, tempat_tgl_lahir, umur, agama, pekerjaan, alamat, rt, rw)\
            VALUES \
            ('$nik', '$no_kk', '$nama', '$jenis_kelamin', '$tempat_tgl_lahir', " . ($umur !== null ? $umur : 'NULL') . ", '$agama', '$pekerjaan', '$alamat', '$rt', '$rw')\
            ON DUPLICATE KEY UPDATE \
            nama='$nama', no_kk='$no_kk', jenis_kelamin='$jenis_kelamin', tempat_tgl_lahir='$tempat_tgl_lahir', pekerjaan='$pekerjaan', alamat='$alamat', rt='$rt', rw='$rw'";

        if (mysqli_query($koneksi, $query)) {
            $inserted++;
        }
    }

    echo "<script>alert('Berhasil mengimpor $inserted data penduduk!'); window.location='index.php?page=penduduk';</script>";
}
?>

<!-- Form Upload File Excel -->
<form action="" method="POST" enctype="multipart/form-data" class="p-4 border rounded bg-light">
    <h5>Upload Data Penduduk (Excel)</h5>
    <input type="file" name="file_excel" accept=".xlsx, .xls" required class="form-control mb-3">
    <button type="submit" name="import" class="btn btn-primary">Import Data</button>
</form>