<?php
// Konfigurasi Database
$host = '101.50.1.77';
$username = 'simdesid_admin';
$password = '+!IF?PeAd1V[{7C$'; // Petik tunggal agar karakter $ tidak terbaca variabel
$database = 'simdesid_db_balaidesa';

// Membuat koneksi ke MySQL
@$koneksi = @mysqli_connect($host, $username, $password, $database);

// Memeriksa apakah koneksi berhasil
if (!$koneksi) {
    // If this is an API call, don't die with plain text; let the API handle the error
    if (strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') === false) {
        die("Koneksi database gagal: " . mysqli_connect_error());
    }
    // For API calls, $koneksi will be false/null and the calling code should handle it
}
// Mengatur charset koneksi ke utf8mb4
mysqli_set_charset($koneksi, 'utf8mb4');

// Mengatur timezone ke Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');
?>