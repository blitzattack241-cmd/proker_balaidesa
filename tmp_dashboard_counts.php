<?php
require_once __DIR__ . '/koneksi.php';
if (!$koneksi) {
    echo "CONNECT_ERROR: " . mysqli_connect_error() . PHP_EOL;
    exit(1);
}
$tables = [
    'tb_surat_garapan',
    'tb_surat_kelahiran',
    'tb_surat_kematian',
    'tb_surat_undangan',
    'tb_surat_pengantar',
    'tb_surat_domisili',
    'tb_surat_dukcapil',
    'tb_surat_waris',
    'tb_sktm_bumil',
    'tb_sktm_kip',
    'tb_sktm_kis',
    'tb_sktm_rawat',
    'tb_sktm_stunting'
];
foreach ($tables as $table) {
    $res = mysqli_query($koneksi, "SELECT COUNT(*) AS c FROM `$table`");
    if (!$res) {
        echo "$table:ERROR:" . mysqli_error($koneksi) . PHP_EOL;
        continue;
    }
    $row = mysqli_fetch_assoc($res);
    echo "$table:" . ($row['c'] ?? 0) . PHP_EOL;
}
$query = "SELECT COUNT(DISTINCT nik) AS c FROM ("
    . "SELECT nik FROM tb_surat_pengantar UNION ALL "
    . "SELECT nik FROM tb_surat_domisili UNION ALL "
    . "SELECT nik FROM tb_surat_kelahiran UNION ALL "
    . "SELECT nik FROM tb_surat_kematian UNION ALL "
    . "SELECT nik FROM tb_surat_dukcapil UNION ALL "
    . "SELECT nik FROM tb_surat_waris UNION ALL "
    . "SELECT nik FROM tb_surat_garapan UNION ALL "
    . "SELECT nik FROM tb_sktm_bumil UNION ALL "
    . "SELECT nik FROM tb_sktm_kip UNION ALL "
    . "SELECT nik FROM tb_sktm_kis UNION ALL "
    . "SELECT nik FROM tb_sktm_rawat UNION ALL "
    . "SELECT nik FROM tb_sktm_stunting) x";
$res = mysqli_query($koneksi, $query);
if ($res) {
    $row = mysqli_fetch_assoc($res);
    echo "distinct_nik:" . ($row['c'] ?? 0) . PHP_EOL;
} else {
    echo "distinct_nik:ERROR:" . mysqli_error($koneksi) . PHP_EOL;
}
mysqli_close($koneksi);
