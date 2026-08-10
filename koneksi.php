<?php
// Konfigurasi Database
$host = 'localhost';
$username = 'simdesid_admin';
$password = '+!IF?PeAd1V[{7C$'; // Petik tunggal agar karakter $ tidak terbaca variabel
$database = 'simdesid_db_balaidesa';

// Membuat koneksi ke MySQL
$koneksi = mysqli_connect($host, $username, $password, $database);

// Memeriksa apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Mengatur timezone ke Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');
?>