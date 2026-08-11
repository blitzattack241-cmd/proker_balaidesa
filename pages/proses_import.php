<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../koneksi.php';
if (!$koneksi) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

$checkColumns = [
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

foreach ($checkColumns as $colName => $colDef) {
    if (!in_array($colName, $existingCols, true)) {
        mysqli_query($koneksi, "ALTER TABLE `tb_penduduk` ADD COLUMN `$colName` $colDef");
    }
}

$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (is_file($autoloadPath)) {
    require_once $autoloadPath;
}

$hasPhpSpreadsheet = class_exists('PhpOffice\\PhpSpreadsheet\\IOFactory');

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
            if ($GLOBALS['hasPhpSpreadsheet'] ?? false) {
                try {
                    return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $value)->format('Y-m-d');
                } catch (Throwable $e) {
                    return null;
                }
            }
        }

        $timestamp = strtotime(str_replace('/', '-', (string) $value));
        return $timestamp ? date('Y-m-d', $timestamp) : null;
    };

    $normalizeHeader = function ($value) {
        $value = strtolower(trim((string) $value));
        $value = preg_replace('/[^a-z0-9]+/', '', $value);
        return $value;
    };

    $normalizeCanonicalHeader = function ($headerName) {
        $headerName = strtolower(trim((string) $headerName));
        $headerName = preg_replace('/[^a-z0-9]+/', '', $headerName);

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

    $rows = [];
    $isExcel = in_array($fileExt, ['xlsx', 'xls'], true) && $hasPhpSpreadsheet;

    if ($isExcel) {
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fileTmpPath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray(null, true, true, true);
        } catch (Throwable $e) {
            $errors[] = 'Gagal membaca file Excel: ' . $e->getMessage();
        }
    } else {
        $handle = fopen($fileTmpPath, 'r');
        if ($handle !== false) {
            $firstLine = fgets($handle);
            $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
            rewind($handle);

            while (($data = fgetcsv($handle, 2000, $delimiter)) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }
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

    $headerMap = [];
    if ($headerRowIndex !== null) {
        foreach ($rows[$headerRowIndex] as $sourceKey => $headerValue) {
            $canonical = $normalizeCanonicalHeader($headerValue);
            if ($canonical !== null) {
                $headerMap[$sourceKey] = $canonical;
            }
        }
    }

    $dataRows = [];
    if ($headerRowIndex !== null) {
        for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
            $dataRows[] = $rows[$i];
        }
    } else {
        $dataRows = $rows;
    }

    $normalizeRecord = function ($row) use ($headerMap, $clean, $normalizeStatusPernikahan, $parseDate) {
        $mapped = [];
        if (!empty($headerMap)) {
            foreach ($row as $sourceKey => $value) {
                if (isset($headerMap[$sourceKey])) {
                    $mapped[$headerMap[$sourceKey]] = $value;
                }
            }
        }

        $rt = $mapped['rt'] ?? ($row['B'] ?? $row[1] ?? '');
        $rw = $mapped['rw'] ?? ($row['C'] ?? $row[2] ?? '');
        $no_kk = $mapped['no_kk'] ?? ($row['D'] ?? $row[3] ?? '');
        $kepala_kk = $mapped['kepala_kk'] ?? ($row['E'] ?? $row[4] ?? '');
        $nik = $mapped['nik'] ?? ($row['G'] ?? $row[6] ?? '');
        $nama = $mapped['nama'] ?? ($row['H'] ?? $row[7] ?? '');
        $jenis_kelamin = $mapped['jenis_kelamin'] ?? ($row['I'] ?? $row[8] ?? '');
        $status_keluarga = $mapped['status_keluarga'] ?? ($row['J'] ?? $row[9] ?? '');
        $tempat_lahir = $mapped['tempat_lahir'] ?? ($row['K'] ?? $row[10] ?? '');
        $tgl_lahir_raw = $mapped['tgl_lahir'] ?? ($row['L'] ?? $row[11] ?? '');
        $status_pernikahan_raw = $mapped['status_pernikahan'] ?? ($row['M'] ?? $row[12] ?? '');
        $agama = $mapped['agama'] ?? ($row['N'] ?? $row[13] ?? '');
        $kewarganegaraan = $mapped['kewarganegaraan'] ?? ($row['O'] ?? $row[14] ?? '');
        $suku = $mapped['suku'] ?? ($row['P'] ?? $row[15] ?? '');
        $pendidikan = $mapped['pendidikan'] ?? ($row['Q'] ?? $row[16] ?? '');
        $pekerjaan = $mapped['pekerjaan'] ?? ($row['R'] ?? $row[17] ?? '');
        $alamat = $mapped['alamat'] ?? ($row['A'] ?? $row[0] ?? '');
        $umur = $mapped['umur'] ?? '';

        $rt_clean = $clean($rt);
        $rw_clean = $clean($rw);
        $no_kk_clean = $clean($no_kk);
        $kepala_kk_clean = $clean($kepala_kk);
        $nik_clean = $clean($nik);
        $nama_clean = $clean($nama);
        $jenis_kelamin_clean = $clean($jenis_kelamin);
        $status_keluarga_clean = $clean($status_keluarga);
        $tempat_lahir_clean = $clean($tempat_lahir);
        $tgl_lahir_value = $parseDate($tgl_lahir_raw);
        $status_pernikahan_clean = $clean($normalizeStatusPernikahan($status_pernikahan_raw));
        $agama_clean = $clean($agama);
        $kewarganegaraan_clean = $clean($kewarganegaraan);
        $suku_clean = $clean($suku);
        $pendidikan_clean = $clean($pendidikan);
        $pekerjaan_clean = $clean($pekerjaan);
        $alamat_clean = $clean($alamat);
        $umur_clean = $clean($umur);

        return [
            'rt' => $rt_clean,
            'rw' => $rw_clean,
            'no_kk' => $no_kk_clean,
            'kepala_kk' => $kepala_kk_clean,
            'nik' => $nik_clean,
            'nama' => $nama_clean,
            'jenis_kelamin' => $jenis_kelamin_clean,
            'status_keluarga' => $status_keluarga_clean,
            'tempat_lahir' => $tempat_lahir_clean,
            'tgl_lahir' => $tgl_lahir_value,
            'status_pernikahan' => $status_pernikahan_clean,
            'agama' => $agama_clean,
            'kewarganegaraan' => $kewarganegaraan_clean,
            'suku' => $suku_clean,
            'pendidikan' => $pendidikan_clean,
            'pekerjaan' => $pekerjaan_clean,
            'alamat' => $alamat_clean,
            'umur' => $umur_clean,
        ];
    };

    foreach ($dataRows as $rowIndex => $row) {
        $record = $normalizeRecord($row);

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

    if (!empty($errors)) {
        $summary = "Import selesai. Ditambahkan/diupdate: $inserted. Dilewati: $skipped.\nError:\n" . implode("\n", array_slice($errors, 0, 5));
        echo "<script>alert('" . addslashes($summary) . "'); window.location='../index.php?page=penduduk';</script>";
    } else {
        $summary = "Berhasil memproses $inserted data penduduk. Dilewati: $skipped.";
        echo "<script>alert('" . addslashes($summary) . "'); window.location='../index.php?page=penduduk';</script>";
    }

    echo '<div style="padding:16px;font-family:Arial,sans-serif;">';
    echo '<strong>Hasil import:</strong><br>';
    echo 'Ditambahkan/diupdate: ' . $inserted . '<br>';
    echo 'Dilewati: ' . $skipped . '<br>';
    if (!empty($errors)) {
        echo 'Error: ' . htmlspecialchars(implode('<br>', array_slice($errors, 0, 5)), ENT_QUOTES, 'UTF-8');
    }
    echo '</div>';

    exit;
}
?>