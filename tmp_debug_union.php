<?php
$koneksi = mysqli_connect('localhost', 'root', '', 'db_balaidesa');
if (!$koneksi) {
    die('connect_error');
}
mysqli_set_charset($koneksi, 'utf8mb4');

function tableExists(mysqli $koneksi, string $namaTable): bool
{
    $check = mysqli_query($koneksi, "SHOW TABLES LIKE '" . mysqli_real_escape_string($koneksi, $namaTable) . "'");
    return $check && mysqli_num_rows($check) > 0;
}

function columnExists(mysqli $koneksi, string $namaTable, string $namaKolom): bool
{
    $check = mysqli_query($koneksi, "SHOW COLUMNS FROM `" . mysqli_real_escape_string($koneksi, $namaTable) . "` LIKE '" . mysqli_real_escape_string($koneksi, $namaKolom) . "'");
    return $check && mysqli_num_rows($check) > 0;
}

function chooseColumnExpr(mysqli $koneksi, string $table, array $candidates, string $default = ''): string
{
    foreach ($candidates as $column) {
        if (columnExists($koneksi, $table, $column))
            return "`$column`";
    }
    return "'" . mysqli_real_escape_string($koneksi, $default) . "'";
}

function chooseNomorSuratExpr(mysqli $koneksi, string $table): string
{
    $candidates = ['nomor_surat', 'kode_surat', 'no_surat', 'surat_nomor', 'nomor'];
    $parts = [];

    foreach ($candidates as $column) {
        if (columnExists($koneksi, $table, $column)) {
            $parts[] = "NULLIF(TRIM(`$column`), '')";
        }
    }

    if (empty($parts)) {
        return "''";
    }

    return "TRIM(COALESCE(" . implode(', ', $parts) . ", ''))";
}

$sourceTables = [
    ['label' => 'Surat Garapan Sawah', 'candidates' => ['tb_surat_garapan', 'surat_garapan'], 'name' => ['nama_penggarap','nama_pemohon','nama_warga'], 'tujuan' => ['keperluan','perihal','keterangan']],
    ['label' => 'Surat Ahli Waris', 'candidates' => ['tb_surat_waris','surat_waris'], 'name' => ['nama_almarhum','nama_pasangan','nama_pemohon'], 'tujuan' => ['keperluan','keterangan','perihal']],
    ['label' => 'Surat Undangan', 'candidates' => ['tb_surat_undangan','surat_undangan'], 'name' => ['nama_pengundang','nama_pemohon','pengundang','nama_pejabat'], 'tujuan' => ['tujuan_surat','kepada','tujuan','penerima','acara','perihal']],
    ['label' => 'Surat Kelahiran', 'candidates' => ['tb_surat_kelahiran','surat_kelahiran'], 'name' => ['nama_bayi','nama_pelapor','nama_kepala_keluarga'], 'tujuan' => ['keperluan','keterangan_lain','keterangan']],
    ['label' => 'Surat Kematian', 'candidates' => ['tb_surat_kematian','surat_kematian'], 'name' => ['nama_jenazah','nama_pelapor'], 'tujuan' => ['keperluan','keterangan','tujuan']],
    ['label' => 'Surat Keterangan / Pengantar', 'candidates' => ['tb_surat_pengantar','surat_pengantar'], 'name' => ['nama_penduduk','nama_pemohon','nama_warga'], 'tujuan' => ['keperluan','keterangan_lain','keterangan']],
    ['label' => 'Surat Domisili', 'candidates' => ['tb_surat_domisili','surat_domisili'], 'name' => ['nama_warga','nama_pemohon'], 'tujuan' => ['keperluan','keterangan_lain']],
    ['label' => 'Surat Pengantar Dukcapil', 'candidates' => ['tb_surat_pengantar_dukcapil','tb_surat_dukcapil','surat_pengantar_dukcapil','surat_dukcapil'], 'name' => ['nama_pemohon','created_by'], 'tujuan' => ['jenis_dikirim','banyaknya']],
    ['label' => 'SKTM Ibu Hamil', 'candidates' => ['tb_sktm_bumil','sktm_bumil'], 'name' => ['nama_pemohon','nama_warga'], 'tujuan' => ['keperluan','keterangan_lain']],
    ['label' => 'SKTM Pembebasan Rawat', 'candidates' => ['tb_sktm_rawat','sktm_rawat','tb_sktm_pasien','sktm_pasien'], 'name' => ['nama_pemohon','nama_warga'], 'tujuan' => ['rumah_sakit_tujuan','keperluan']],
    ['label' => 'SKTM KIS', 'candidates' => ['tb_sktm_kis','sktm_kis'], 'name' => ['nama_warga','nama_pemohon'], 'tujuan' => ['keperluan','keterangan']],
    ['label' => 'SKTM KIP', 'candidates' => ['tb_sktm_kip','sktm_kip'], 'name' => ['nama_warga','nama_pemohon'], 'tujuan' => ['keperluan','keterangan']],
    ['label' => 'SKTM Stunting', 'candidates' => ['tb_sktm_stunting','sktm_stunting'], 'name' => ['nama_warga','nama_pemohon'], 'tujuan' => ['keperluan','keterangan']],
];

$unionQueries = [];
foreach ($sourceTables as $config) {
    $table = null;
    foreach ($config['candidates'] as $candidate) {
        if (tableExists($koneksi, $candidate)) {
            $table = $candidate;
            break;
        }
    }
    if (!$table) continue;

    $jenis = mysqli_real_escape_string($koneksi, $config['label']);
    $nomor = chooseNomorSuratExpr($koneksi, $table);
    $tanggal = chooseColumnExpr($koneksi, $table, ['tanggal_surat'], '');
    $nama = chooseColumnExpr($koneksi, $table, $config['name'], '');
    $tujuan = chooseColumnExpr($koneksi, $table, $config['tujuan'], '');

    $coll = '';
    $jenisExpr = "'" . $jenis . "'" . $coll . " AS jenis_surat";
    $applyTextCollation = function ($expr) use ($coll) {
        $trim = ltrim($expr);
        if ($trim === "''" || (isset($trim[0]) && $trim[0] === "'")) {
            return $expr . $coll;
        }
        if (strpos($expr, "`") !== false || stripos($expr, 'concat') !== false || stripos($expr, 'trim') !== false) {
            return 'CONVERT(' . $expr . " USING utf8mb4)";
        }
        return $expr . $coll;
    };

    $nomorExpr = $nomor . ' AS nomor_surat';
    $tanggalExpr = $tanggal . ' AS tanggal_surat';
    $namaExpr = $applyTextCollation($nama) . ' AS nama_pemohon';
    $tujuanExpr = $applyTextCollation($tujuan) . ' AS tujuan';

    $unionQueries[] = "SELECT $jenisExpr, $nomorExpr, $tanggalExpr, $namaExpr, $tujuanExpr FROM `" . mysqli_real_escape_string($koneksi, $table) . "`";
    echo "table=$table\n";
}

$query = implode(' UNION ALL ', $unionQueries) . ' ORDER BY tanggal_surat ASC, nomor_surat ASC';
echo "QUERY:\n$query\n";
$result = mysqli_query($koneksi, $query);
if (!$result) {
    echo 'ERROR: ' . mysqli_error($koneksi) . PHP_EOL;
    exit;
}
$count = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $count++;
    if ($count <= 5) {
        echo json_encode($row, JSON_UNESCAPED_UNICODE) . PHP_EOL;
    }
}
echo 'COUNT=' . $count . PHP_EOL;
