<?php
// Konfigurasi Database
$host     = "localhost";
$username = "root";
$password = "";
$database = "db_balaidesa";

// Membuat koneksi ke MySQL
$koneksi = mysqli_connect($host, $username, $password, $database);

// Memeriksa apakah koneksi berhasil
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Mengatur timezone ke Asia/Jakarta agar tanggal surat selalu akurat
date_default_timezone_set('Asia/Jakarta');
?>