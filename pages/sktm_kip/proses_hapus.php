<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Akses Admin
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak! Anda tidak memiliki izin untuk menghapus data.');
            window.location.href = '../../index.php?page=dashboard';
          </script>";
    exit;
}

// Membuka Koneksi ke Database
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
if (mysqli_connect_errno()) {
    echo "<script>
            alert('Koneksi database gagal: " . mysqli_connect_error() . "');
            window.location.href = '../../index.php?page=sktm-kip';
          </script>";
    exit;
}

// Memastikan parameter ID dikirimkan dan aman
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_sktm = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Cek terlebih dahulu apakah data dengan ID tersebut memang ada di database
    $check_query = mysqli_query($koneksi, "SELECT id_sktm FROM tb_sktm_kip WHERE id_sktm = '$id_sktm'");
    
    if (mysqli_num_rows($check_query) > 0) {
        // Lakukan proses penghapusan data
        $delete = mysqli_query($koneksi, "DELETE FROM tb_sktm_kip WHERE id_sktm = '$id_sktm'");

        if ($delete) {
            echo "<script>
                    alert('Data SKTM KIP berhasil dihapus!');
                    window.location.href = '../../index.php?page=sktm-kip';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');
                    window.location.href = '../../index.php?page=sktm-kip';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Data tidak ditemukan atau sudah terhapus sebelumnya.');
                window.location.href = '../../index.php?page=sktm-kip';
              </script>";
    }
} else {
    echo "<script>
            alert('ID Data tidak valid atau tidak disertakan.');
            window.location.href = '../../index.php?page=sktm-kip';
          </script>";
}

// Tutup koneksi database
mysqli_close($koneksi);
?>