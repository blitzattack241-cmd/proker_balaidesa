<?php
/**
 * Helper penomoran surat global.
 * Menghasilkan nomor dengan format:
 * 400.10.2.2/01/31.07.16/2026
 * Bagian tengah (01, 02, 03, dst.) selalu bertambah berdasarkan jumlah surat yang tersimpan.
 */
function daftarTabelNomorSuratGlobal(): array
{
    return [
        'tb_sktm_bumil' => 'id_sktm',
        'tb_sktm_kip' => 'id_sktm',
        'tb_sktm_kis' => 'id_sktm',
        'tb_sktm_rawat' => 'id_sktm',
        'tb_sktm_stunting' => 'id_sktm',
        'tb_surat_waris' => 'id_waris',
        'tb_surat_domisili' => 'id_domisili',
        'tb_surat_dukcapil' => 'id_surat',
        'tb_surat_garapan' => 'id_garapan',
        'tb_surat_kelahiran' => 'id_surat',
        'tb_surat_kematian' => 'id_surat',
        'tb_surat_pengantar' => 'id_surat',
        'tb_surat_undangan' => 'id_undangan',
    ];
}

function dapatkanJumlahRecordNomorSuratGlobal(mysqli $koneksi): int
{
    $total = 0;

    foreach (daftarTabelNomorSuratGlobal() as $tabel => $pk) {
        $check = mysqli_query($koneksi, "SHOW TABLES LIKE '$tabel'");
        if (!$check || mysqli_num_rows($check) === 0) {
            continue;
        }

        $result = mysqli_query($koneksi, "SELECT COUNT(*) AS cnt FROM `$tabel`");
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $total += isset($row['cnt']) ? (int) $row['cnt'] : 0;
        }
    }

    return $total;
}

function formatNomorSuratGlobal($nomorUrut): string
{
    $nomorUrut = max(1, (int) $nomorUrut);
    $nomorFormat = str_pad((string) $nomorUrut, 2, '0', STR_PAD_LEFT);
    return '400.10.2.2/' . $nomorFormat . '/31.07.16/' . date('Y');
}

function generateNomorSuratGlobal(mysqli $koneksi, bool $reserve = true): string
{
    $nextSequence = dapatkanJumlahRecordNomorSuratGlobal($koneksi) + 1;
    return formatNomorSuratGlobal($nextSequence);
}

function dapatkanNomorSuratGlobalPreview(mysqli $koneksi): string
{
    return generateNomorSuratGlobal($koneksi, false);
}

function alokasikanNomorSuratGlobalString(mysqli $koneksi)
{
    return generateNomorSuratGlobal($koneksi, true);
}

function dapatkanNomorUrutGlobalPreview(mysqli $koneksi)
{
    return dapatkanJumlahRecordNomorSuratGlobal($koneksi) + 1;
}

function alokasikanNomorUrutGlobal(mysqli $koneksi)
{
    return dapatkanJumlahRecordNomorSuratGlobal($koneksi) + 1;
}

function renumerasiNomorSuratGlobal(mysqli $koneksi)
{
    if (!($koneksi instanceof mysqli)) {
        return false;
    }

    mysqli_begin_transaction($koneksi);

    try {
        $sequence = 0;

        foreach (daftarTabelNomorSuratGlobal() as $tabel => $pk) {
            $check = mysqli_query($koneksi, "SHOW TABLES LIKE '$tabel'");
            if (!$check || mysqli_num_rows($check) === 0) {
                continue;
            }

            $columnCheck = mysqli_query($koneksi, "SHOW COLUMNS FROM `$tabel` LIKE 'nomor_surat'");
            if (!$columnCheck || mysqli_num_rows($columnCheck) === 0) {
                continue;
            }

            $select = mysqli_query($koneksi, "SELECT `$pk` FROM `$tabel` ORDER BY `$pk` ASC");
            if (!$select) {
                throw new RuntimeException(mysqli_error($koneksi));
            }

            while ($row = mysqli_fetch_assoc($select)) {
                $sequence++;
                $nomorSurat = mysqli_real_escape_string($koneksi, formatNomorSuratGlobal($sequence));
                $update = mysqli_query($koneksi, "UPDATE `$tabel` SET `nomor_surat` = '$nomorSurat' WHERE `$pk` = " . (int) $row[$pk]);
                if (!$update) {
                    throw new RuntimeException(mysqli_error($koneksi));
                }
            }
        }

        mysqli_commit($koneksi);
        return true;
    } catch (Throwable $e) {
        mysqli_rollback($koneksi);
        return false;
    }
}