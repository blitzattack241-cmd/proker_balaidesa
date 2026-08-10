<?php
require_once __DIR__ . '/koneksi.php';
if (!$koneksi) {
    echo 'connect-failed:' . mysqli_connect_error();
    exit(1);
}
$result = mysqli_query($koneksi, "SHOW COLUMNS FROM tb_undangan_tujuan LIKE 'nama_jabatan_tujuan'");
if ($result && mysqli_num_rows($result) === 0) {
    mysqli_query($koneksi, "ALTER TABLE tb_undangan_tujuan ADD COLUMN nama_jabatan_tujuan VARCHAR(100) DEFAULT NULL");
    echo 'column-added';
} else {
    echo 'column-exists';
}
mysqli_close($koneksi);
