<?php
require_once __DIR__ . '/import_response.php';

import_json_begin();

try {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/import_helpers.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new InvalidArgumentException('Metode tidak valid');
    }

    require_once __DIR__ . '/../koneksi.php';

    global $koneksi;

    $payload = $_POST;
    if (empty($_FILES['file']['tmp_name']) || ($_FILES['file']['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new InvalidArgumentException('File belum dipilih atau unggahan gagal');
    }

    $tmp = $_FILES['file']['tmp_name'];
    $name = $_FILES['file']['name'];
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    $rows = load_rows_from_file($tmp, $ext);

    if (empty($rows)) {
        throw new InvalidArgumentException('Tidak ada baris data yang ditemukan');
    }

    $headerIndex = isset($payload['header_index']) ? (int)$payload['header_index'] : 0;
    $header = $rows[$headerIndex];

    // user mapping: array index -> canonical field or 'skip'
    $mapping = json_decode($payload['mapping'] ?? '[]', true);
    if (!is_array($mapping)) { $mapping = []; }

    // mode: 'insert_only' (default) or 'insert_or_update'
    $mode = isset($payload['mode']) && $payload['mode'] === 'insert_or_update' ? 'insert_or_update' : 'insert_only';

    $inserted = 0; $updated = 0; $skipped = 0; $failed = 0;
    $failRows = [];

    // Check database connection before proceeding
    if (!$koneksi) {
        throw new RuntimeException('Koneksi database tidak tersedia');
    }

    ensure_import_logs_table();

    for ($i = $headerIndex + 1; $i < count($rows); $i++) {
        $raw = $rows[$i];
        $record = [];
        foreach ($raw as $idx => $cell) {
            if (!isset($mapping[$idx]) || $mapping[$idx] === 'skip') continue;
            $canon = $mapping[$idx];
            $record[$canon] = is_string($cell) ? trim($cell) : $cell;
        }

        // basic required checks
        $nik = trim($record['nik'] ?? '');
        $nama = trim($record['nama'] ?? '');
        if ($nik === '' || $nama === '') {
            $skipped++;
            $failRows[] = ['row' => $i+1, 'reason' => 'NIK atau Nama belum diisi', 'data' => $raw];
            continue;
        }

        $tgl = isset($record['tgl_lahir']) ? parse_date_value($record['tgl_lahir']) : null;
        $rt = mysqli_real_escape_string($koneksi, $record['rt'] ?? '');
        $rw = mysqli_real_escape_string($koneksi, $record['rw'] ?? '');
        $no_kk = mysqli_real_escape_string($koneksi, $record['no_kk'] ?? '');
        $kepala_kk = mysqli_real_escape_string($koneksi, $record['kepala_kk'] ?? '');
        $nikEsc = mysqli_real_escape_string($koneksi, $nik);
        $namaEsc = mysqli_real_escape_string($koneksi, $nama);
        $jenisKel = mysqli_real_escape_string($koneksi, $record['jenis_kelamin'] ?? '');
        $statusKeluarga = mysqli_real_escape_string($koneksi, $record['status_keluarga'] ?? '');
        $tempat = mysqli_real_escape_string($koneksi, $record['tempat_lahir'] ?? '');
        $statusP = mysqli_real_escape_string($koneksi, $record['status_pernikahan'] ?? '');
        $agama = mysqli_real_escape_string($koneksi, $record['agama'] ?? '');
        $kew = mysqli_real_escape_string($koneksi, $record['kewarganegaraan'] ?? '');
        $suku = mysqli_real_escape_string($koneksi, $record['suku'] ?? '');
        $pend = mysqli_real_escape_string($koneksi, $record['pendidikan'] ?? '');
        $pek = mysqli_real_escape_string($koneksi, $record['pekerjaan'] ?? '');
        $alamat = mysqli_real_escape_string($koneksi, $record['alamat'] ?? '');
        $umur = isset($record['umur']) ? (int)$record['umur'] : 'NULL';

        $existsQ = mysqli_query($koneksi, "SELECT id FROM tb_penduduk WHERE nik = '$nikEsc' LIMIT 1");
        $exists = $existsQ ? mysqli_fetch_assoc($existsQ) : null;

        $tglSql = $tgl === null ? 'NULL' : "'" . mysqli_real_escape_string($koneksi, $tgl) . "'";
        $umurSql = $umur === 'NULL' ? 'NULL' : (int)$umur;
        $tempatTgl = ($tgl !== null && $tgl !== '') ? ($tempat . ', ' . $tgl) : $tempat;

        if ($exists) {
            if ($mode === 'insert_or_update') {
                $sql = "UPDATE tb_penduduk SET
                    rt = '$rt', rw = '$rw', no_kk = '$no_kk', kepala_kk = '$kepala_kk', nama = '$namaEsc', jenis_kelamin = '$jenisKel', status_keluarga = '$statusKeluarga', tempat_lahir = '$tempat', tgl_lahir = $tglSql, status_pernikahan = '$statusP', agama = '$agama', kewarganegaraan = '$kew', suku = '$suku', pendidikan = '$pend', pekerjaan = '$pek', alamat = '$alamat', umur = $umurSql, tempat_tgl_lahir = '" . mysqli_real_escape_string($koneksi, $tempatTgl) . "'
                    WHERE nik = '$nikEsc'";
                $res = mysqli_query($koneksi, $sql);
                if ($res) { $updated++; } else { $failed++; $failRows[] = ['row' => $i+1, 'reason' => mysqli_error($koneksi), 'data' => $raw]; }
            } else {
                // Mode insert_only: data lama tidak ditimpa
                $skipped++;
                continue;
            }
        } else {
            $sql = "INSERT INTO tb_penduduk (rt, rw, no_kk, kepala_kk, nik, nama, jenis_kelamin, status_keluarga, tempat_lahir, tgl_lahir, status_pernikahan, agama, kewarganegaraan, suku, pendidikan, pekerjaan, alamat, umur, tempat_tgl_lahir) VALUES ('" . $rt . "', '" . $rw . "', '" . $no_kk . "', '" . $kepala_kk . "', '" . $nikEsc . "', '" . $namaEsc . "', '" . $jenisKel . "', '" . $statusKeluarga . "', '" . $tempat . "', $tglSql, '" . $statusP . "', '" . $agama . "', '" . $kew . "', '" . $suku . "', '" . $pend . "', '" . $pek . "', '" . $alamat . "', $umurSql, '" . mysqli_real_escape_string($koneksi, $tempatTgl) . "')";
            $res = mysqli_query($koneksi, $sql);
            if ($res) { $inserted++; } else { $failed++; $failRows[] = ['row' => $i+1, 'reason' => mysqli_error($koneksi), 'data' => $raw]; }
        }
    }

    // log
    mysqli_query($koneksi, sprintf("INSERT INTO import_logs (filename, user, inserted, updated, skipped, failed) VALUES ('%s','%s', %d, %d, %d, %d)",
        mysqli_real_escape_string($koneksi, $name),
        mysqli_real_escape_string($koneksi, $_SESSION['user'] ?? 'system'),
        $inserted, $updated, $skipped, $failed
    ));

    import_json_response([
        'ok' => true,
        'inserted' => $inserted,
        'updated' => $updated,
        'skipped' => $skipped,
        'failed' => $failed,
        'fail_rows' => $failRows,
    ]);

} catch (Throwable $e) {
    $status = $e instanceof InvalidArgumentException ? 400 : 500;
    import_json_response(['ok' => false, 'error' => $e->getMessage()], $status);
}
