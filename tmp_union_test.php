<?php
$koneksi = new mysqli('localhost', 'root', '', 'db_balaidesa');
if ($koneksi->connect_errno) {
    echo 'CONNECT_ERROR: ' . $koneksi->connect_error . PHP_EOL;
    exit(1);
}
$koneksi->set_charset('utf8mb4');

function tableExists($koneksi, $namaTable) {
    $check = mysqli_query($koneksi, "SHOW TABLES LIKE '" . mysqli_real_escape_string($koneksi, $namaTable) . "'");
    return $check && mysqli_num_rows($check) > 0;
}

function columnExists($koneksi, $namaTable, $namaKolom) {
    $check = mysqli_query($koneksi, "SHOW COLUMNS FROM `" . mysqli_real_escape_string($koneksi, $namaTable) . "` LIKE '" . mysqli_real_escape_string($koneksi, $namaKolom) . "'");
    return $check && mysqli_num_rows($check) > 0;
}

function chooseColumnExpr($koneksi, $table, $candidates, $default = '') {
    foreach ($candidates as $column) {
        if (columnExists($koneksi, $table, $column)) return "`$column`";
    }
    return "'" . mysqli_real_escape_string($koneksi, $default) . "'";
}

$table = 'tb_surat_kelahiran';
$nomor = chooseColumnExpr($koneksi, $table, ['nomor_surat'], '');
$query = "SELECT COUNT(*) AS total_rows FROM `$table`";
$result = mysqli_query($koneksi, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo 'total_rows=' . $row['total_rows'] . PHP_EOL;
}
$query = "SELECT id_surat, `nomor_surat` AS nomor_surat FROM `$table` ORDER BY id_surat DESC LIMIT 10";
echo $query . PHP_EOL;
$result = mysqli_query($koneksi, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo 'id=' . $row['id_surat'] . ' nomor=' . var_export($row['nomor_surat'], true) . PHP_EOL;
    }
} else {
    echo 'ERROR: ' . mysqli_error($koneksi) . PHP_EOL;
}
$koneksi->close();
