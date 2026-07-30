<?php
/**
 * Helper penomoran surat global.
 * Menghasilkan nomor dengan format:
 * 400.10.2.2/01/31.07.16/2026
 * Nomor tengah (01, 02, 03, dst.) akan selalu bertambah global untuk semua jenis surat.
 */
function generateNomorSuratGlobal(mysqli $koneksi, bool $reserve = true): string
{
    $tahun = date('Y');

    $checkTable = mysqli_query($koneksi, "SHOW TABLES LIKE 'tb_nomor_surat_global'");
    if ($checkTable && mysqli_num_rows($checkTable) === 0) {
        $sqlCreate = "
            CREATE TABLE IF NOT EXISTS tb_nomor_surat_global (
                id INT AUTO_INCREMENT PRIMARY KEY,
                tahun VARCHAR(4) NOT NULL,
                nomor INT NOT NULL DEFAULT 0,
                UNIQUE KEY uk_tahun (tahun)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ";
        mysqli_query($koneksi, $sqlCreate);
    }

    $result = mysqli_query($koneksi, "SELECT nomor FROM tb_nomor_surat_global WHERE tahun = '" . mysqli_real_escape_string($koneksi, $tahun) . "' FOR UPDATE");
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $nextNomor = (int) ($row['nomor'] ?? 0) + 1;
    } else {
        $nextNomor = 1;
    }

    if ($reserve) {
        mysqli_begin_transaction($koneksi);
        try {
            $result = mysqli_query($koneksi, "SELECT nomor FROM tb_nomor_surat_global WHERE tahun = '" . mysqli_real_escape_string($koneksi, $tahun) . "' FOR UPDATE");
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $nextNomor = (int) ($row['nomor'] ?? 0) + 1;
                mysqli_query($koneksi, "UPDATE tb_nomor_surat_global SET nomor = $nextNomor WHERE tahun = '" . mysqli_real_escape_string($koneksi, $tahun) . "'");
            } else {
                mysqli_query($koneksi, "INSERT INTO tb_nomor_surat_global (tahun, nomor) VALUES ('" . mysqli_real_escape_string($koneksi, $tahun) . "', 1)");
            }

            mysqli_commit($koneksi);
        } catch (Throwable $e) {
            mysqli_rollback($koneksi);
            throw $e;
        }
    }

    $nomorFormat = str_pad((string) $nextNomor, 2, '0', STR_PAD_LEFT);
    return '400.10.2.2/' . $nomorFormat . '/31.07.16/' . $tahun;
}