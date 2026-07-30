<?php
require 'koneksi.php';

$tahun = date('Y');

mysqli_begin_transaction($koneksi);

$query = mysqli_query($koneksi,"
SELECT MAX(nomor) AS terakhir
FROM nomor_surat_global
WHERE tahun='$tahun'
FOR UPDATE
");

$data = mysqli_fetch_assoc($query);

$nomor = (int)$data['terakhir'] + 1;

mysqli_query($koneksi,"
INSERT INTO nomor_surat_global(nomor,tahun)
VALUES('$nomor','$tahun')
");

mysqli_commit($koneksi);

return sprintf("%02d",$nomor);