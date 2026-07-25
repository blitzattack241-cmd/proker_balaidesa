<?php
$mysqli = new mysqli('localhost', 'root', '', 'db_balaidesa');
if ($mysqli->connect_errno) {
    echo "CONNECT_ERROR: " . $mysqli->connect_error . PHP_EOL;
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
    $res = $mysqli->query("SELECT COUNT(*) AS c FROM `$table`");
    if (!$res) {
        echo "$table:ERROR:" . $mysqli->error . PHP_EOL;
        continue;
    }
    $row = $res->fetch_assoc();
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
$res = $mysqli->query($query);
if ($res) {
    $row = $res->fetch_assoc();
    echo "distinct_nik:" . ($row['c'] ?? 0) . PHP_EOL;
} else {
    echo "distinct_nik:ERROR:" . $mysqli->error . PHP_EOL;
}
$mysqli->close();
