<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi Database
require_once __DIR__ . '/../koneksi.php';
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// --- OTOMATISASI PENYESUAIAN STRUKTUR TABEL ---
$checkColumns = [
    'rt' => "VARCHAR(10) DEFAULT NULL",
    'rw' => "VARCHAR(10) DEFAULT NULL",
    'no_kk' => "VARCHAR(30) DEFAULT NULL",
    'kepala_kk' => "VARCHAR(100) DEFAULT NULL",
    'jenis_kelamin' => "VARCHAR(20) DEFAULT NULL",
    'status_keluarga' => "VARCHAR(50) DEFAULT NULL",
    'tempat_lahir' => "VARCHAR(50) DEFAULT NULL",
    'tgl_lahir' => "DATE DEFAULT NULL",
    'status_pernikahan' => "VARCHAR(50) DEFAULT NULL",
    'agama' => "VARCHAR(30) DEFAULT NULL",
    'kewarganegaraan' => "VARCHAR(50) DEFAULT NULL",
    'suku' => "VARCHAR(50) DEFAULT NULL",
    'pendidikan' => "VARCHAR(100) DEFAULT NULL",
    'pekerjaan' => "VARCHAR(100) DEFAULT NULL"
];

// Periksa kolom satu per satu, buat jika belum ada
$existingColsQuery = mysqli_query($koneksi, "SHOW COLUMNS FROM `tb_penduduk`");
$existingCols = [];
if ($existingColsQuery) {
    while ($col = mysqli_fetch_assoc($existingColsQuery)) {
        $existingCols[] = $col['Field'];
    }
}

foreach ($checkColumns as $colName => $colDef) {
    if (!in_array($colName, $existingCols)) {
        mysqli_query($koneksi, "ALTER TABLE `tb_penduduk` ADD COLUMN `$colName` $colDef");
    }
}

// Autoload Composer untuk PhpSpreadsheet (jika ada)
if (file_exists('../vendor/autoload.php')) {
    require_once '../vendor/autoload.php';
} elseif (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
}

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

if (isset($_POST['import'])) {
    if (isset($_FILES['file_excel']['tmp_name']) && !empty($_FILES['file_excel']['tmp_name'])) {

        $fileTmpPath = $_FILES['file_excel']['tmp_name'];
        $fileName = $_FILES['file_excel']['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        $inserted = 0;
        $errors = [];

        // Helper fungsi pembersih data
        $clean = function ($val) use ($koneksi) {
            if ($val === null || $val === false)
                return '';
            $val = trim((string) $val);
            $val = ltrim($val, "'_");
            if (is_numeric($val) && strpos(strtoupper($val), 'E') !== false) {
                $val = sprintf('%.0f', (float) $val);
            }
            return mysqli_real_escape_string($koneksi, $val);
        };

        // Helper konversi Status Pernikahan agar seragam (PERBAIKAN UTAMA)
        $normalizeStatusPernikahan = function ($val) {
            $status = strtolower(trim((string) $val));
            if (empty($status))
                return 'Belum Kawin';

            if (strpos($status, 'belum') !== false) {
                return 'Belum Kawin';
            } elseif (strpos($status, 'janda') !== false || strpos($status, 'duda') !== false || strpos($status, 'cerai') !== false) {
                return 'Cerai / Janda / Duda';
            } elseif (strpos($status, 'kawin') !== false || strpos($status, 'nikah') !== false) {
                return 'Kawin';
            }
            return 'Belum Kawin';
        };

        // Helper konversi Tanggal Excel / String ke format Y-m-d
        $parseDate = function ($val) {
            if (empty($val))
                return null;
            if (is_numeric($val)) {
                try {
                    $dateTime = Date::excelToDateTimeObject($val);
                    return $dateTime->format('Y-m-d');
                } catch (\Exception $e) {
                    return null;
                }
            }
            $timestamp = strtotime(str_replace('/', '-', $val));
            return $timestamp ? date('Y-m-d', $timestamp) : null;
        };

        // --- SKENARIO 1: IMPORT FILE EXCEL (.XLSX / .XLS) METODE PHPSPREADSHEET ---
        if (in_array($fileExt, ['xlsx', 'xls']) && class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
            try {
                $spreadsheet = IOFactory::load($fileTmpPath);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray(null, true, true, true);

                $lastKepalaKk = '';

                foreach ($rows as $rowIndex => $row) {
                    // Skip baris judul / header
                    if ($rowIndex < 4)
                        continue;

                    $rt = $clean($row['B'] ?? '');
                    $rw = $clean($row['C'] ?? '');
                    $no_kk = $clean($row['D'] ?? '');
                    $kepala_kk = $clean($row['E'] ?? '');
                    $nik = $clean($row['G'] ?? '');
                    $nama = $clean($row['H'] ?? '');
                    $jenis_kelamin = $clean($row['I'] ?? '');
                    $status_keluarga = $clean($row['J'] ?? '');
                    $tempat_lahir = $clean($row['K'] ?? '');
                    $raw_tgl_lahir = $row['L'] ?? '';
                    $raw_status = $row['M'] ?? '';
                    $status_pernikahan = $clean($normalizeStatusPernikahan($raw_status));
                    $agama = $clean($row['N'] ?? '');
                    $kewarganegaraan = $clean($row['O'] ?? '');
                    $suku = $clean($row['P'] ?? '');
                    $pendidikan = $clean($row['Q'] ?? '');
                    $pekerjaan = $clean($row['R'] ?? '');

                    // Simpan kepala KK terakhir jika baris berikutnya kosong (anggota keluarga)
                    if (!empty($kepala_kk)) {
                        $lastKepalaKk = $kepala_kk;
                    } else {
                        $kepala_kk = $lastKepalaKk;
                    }

                    $tgl_lahir = $parseDate($raw_tgl_lahir);
                    $tgl_lahir_val = $tgl_lahir ? "'$tgl_lahir'" : "NULL";

                    // Hanya proses jika NIK dan NAMA tidak kosong serta NIK berupa angka/valid
                    if (!empty($nik) && !empty($nama) && strtolower($nik) !== 'nik') {
                        $query = "INSERT INTO tb_penduduk 
                            (rt, rw, no_kk, kepala_kk, nik, nama, jenis_kelamin, status_keluarga, tempat_lahir, tgl_lahir, status_pernikahan, agama, kewarganegaraan, suku, pendidikan, pekerjaan)
                            VALUES 
                            ('$rt', '$rw', '$no_kk', '$kepala_kk', '$nik', '$nama', '$jenis_kelamin', '$status_keluarga', '$tempat_lahir', $tgl_lahir_val, '$status_pernikahan', '$agama', '$kewarganegaraan', '$suku', '$pendidikan', '$pekerjaan')
                            ON DUPLICATE KEY UPDATE 
                            rt='$rt', rw='$rw', no_kk='$no_kk', kepala_kk='$kepala_kk', nama='$nama', jenis_kelamin='$jenis_kelamin', status_keluarga='$status_keluarga', tempat_lahir='$tempat_lahir', tgl_lahir=$tgl_lahir_val, status_pernikahan='$status_pernikahan', agama='$agama', kewarganegaraan='$kewarganegaraan', suku='$suku', pendidikan='$pendidikan', pekerjaan='$pekerjaan'";

                        if (mysqli_query($koneksi, $query)) {
                            $inserted++;
                        } else {
                            $errors[] = "Baris $rowIndex (NIK: $nik): " . mysqli_error($koneksi);
                        }
                    }
                }
            } catch (\Exception $e) {
                $errors[] = "Gagal membaca file Excel: " . $e->getMessage();
            }

            // --- SKENARIO 2: IMPORT FILE CSV (FALLBACK) ---
        } else {
            $handle = fopen($fileTmpPath, "r");

            if ($handle !== FALSE) {
                $row = 0;
                $firstLine = fgets($handle);
                $delimiter = (strpos($firstLine, ';') !== false) ? ';' : ',';
                rewind($handle);

                $lastKepalaKk = '';

                while (($data = fgetcsv($handle, 2000, $delimiter)) !== FALSE) {
                    $row++;
                    if ($row <= 3)
                        continue;

                    $rt = $clean($data[1] ?? '');
                    $rw = $clean($data[2] ?? '');
                    $no_kk = $clean($data[3] ?? '');
                    $kepala_kk = $clean($data[4] ?? '');
                    $nik = $clean($data[6] ?? '');
                    $nama = $clean($data[7] ?? '');
                    $jenis_kelamin = $clean($data[8] ?? '');
                    $status_keluarga = $clean($data[9] ?? '');
                    $tempat_lahir = $clean($data[10] ?? '');
                    $raw_tgl_lahir = $data[11] ?? '';
                    $raw_status = $data[12] ?? '';
                    $status_pernikahan = $clean($normalizeStatusPernikahan($raw_status));
                    $agama = $clean($data[13] ?? '');
                    $kewarganegaraan = $clean($data[14] ?? '');
                    $suku = $clean($data[15] ?? '');
                    $pendidikan = $clean($data[16] ?? '');
                    $pekerjaan = $clean($data[17] ?? '');

                    if (!empty($kepala_kk)) {
                        $lastKepalaKk = $kepala_kk;
                    } else {
                        $kepala_kk = $lastKepalaKk;
                    }

                    $tgl_lahir = $parseDate($raw_tgl_lahir);
                    $tgl_lahir_val = $tgl_lahir ? "'$tgl_lahir'" : "NULL";

                    if (!empty($nik) && !empty($nama) && strtolower($nik) !== 'nik') {
                        $query = "INSERT INTO tb_penduduk 
                            (rt, rw, no_kk, kepala_kk, nik, nama, jenis_kelamin, status_keluarga, tempat_lahir, tgl_lahir, status_pernikahan, agama, kewarganegaraan, suku, pendidikan, pekerjaan)
                            VALUES 
                            ('$rt', '$rw', '$no_kk', '$kepala_kk', '$nik', '$nama', '$jenis_kelamin', '$status_keluarga', '$tempat_lahir', $tgl_lahir_val, '$status_pernikahan', '$agama', '$kewarganegaraan', '$suku', '$pendidikan', '$pekerjaan')
                            ON DUPLICATE KEY UPDATE 
                            rt='$rt', rw='$rw', no_kk='$no_kk', kepala_kk='$kepala_kk', nama='$nama', jenis_kelamin='$jenis_kelamin', status_keluarga='$status_keluarga', tempat_lahir='$tempat_lahir', tgl_lahir=$tgl_lahir_val, status_pernikahan='$status_pernikahan', agama='$agama', kewarganegaraan='$kewarganegaraan', suku='$suku', pendidikan='$pendidikan', pekerjaan='$pekerjaan'";

                        if (mysqli_query($koneksi, $query)) {
                            $inserted++;
                        } else {
                            $errors[] = "Baris $row (NIK: $nik): " . mysqli_error($koneksi);
                        }
                    }
                }
                fclose($handle);
            }
        }

        // Response & Notifikasi
        if (!empty($errors)) {
            $msgError = implode("\\n", array_slice($errors, 0, 5));
            echo "<script>alert('Terimpor: $inserted data.\\nAda error:\\n$msgError'); window.location='../index.php?page=penduduk';</script>";
        } else {
            echo "<script>alert('Berhasil mengimpor $inserted data penduduk!'); window.location='../index.php?page=penduduk';</script>";
        }
        exit();
    }
}
?>