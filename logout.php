<?php
session_start();

// Hapus semua variabel session
$_SESSION = [];

// Hancurkan session
session_unset();
session_destroy();

// Alihkan pengguna kembali ke halaman login
header("Location: login.php");
exit;
?>