<?php
require_once __DIR__ . '/koneksi.php';
if (!$koneksi) {
    echo "CONNECT_ERROR: " . mysqli_connect_error() . PHP_EOL;
    exit(1);
}
$res = mysqli_query($koneksi, 'SHOW TABLES');
while ($row = mysqli_fetch_row($res)) {
    echo $row[0] . PHP_EOL;
}
mysqli_close($koneksi);
