<?php
// Konfigurasi Database
$host = '101.50.1.77';
$username = 'simdesid_admin';
$password = '+!IF?PeAd1V[{7C$'; // Petik tunggal agar karakter $ tidak terbaca variabel
$database = 'simdesid_db_balaidesa';

// Membuat koneksi ke MySQL
$koneksi = mysqli_connect($host, $username, $password, $database);

// Memeriksa apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
// Mengatur charset koneksi ke utf8mb4
mysqli_set_charset($koneksi, 'utf8mb4');

// Mengatur timezone ke Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');
?>