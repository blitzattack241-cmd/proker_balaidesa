<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek hak akses admin
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>alert('Akses ditolak!'); window.location.href='../../index.php';</script>";
    exit;
}

$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Ambil & Amankan Data Form Utama
    $nomor_surat = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $tanggal_surat = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $keperluan = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    $nama_almarhum = mysqli_real_escape_string($koneksi, $_POST['nama_almarhum']);
    $bin_binti = mysqli_real_escape_string($koneksi, $_POST['bin_binti']);
    $tanggal_meninggal = mysqli_real_escape_string($koneksi, $_POST['tanggal_meninggal']);
    $tempat_meninggal = mysqli_real_escape_string($koneksi, $_POST['tempat_meninggal']);
    $alamat_terakhir = mysqli_real_escape_string($koneksi, $_POST['alamat_terakhir']);
    $nama_pasangan = mysqli_real_escape_string($koneksi, $_POST['nama_pasangan']);
    $status_pasangan = mysqli_real_escape_string($koneksi, $_POST['status_pasangan']);
    $id_pejabat = intval($_POST['id_pejabat']);
    $nama_camat = mysqli_real_escape_string($koneksi, $_POST['nama_camat']);

    // Mulai Database Transaction
    mysqli_begin_transaction($koneksi);

    try {
        // 2. Insert ke tabel utama: tb_surat_waris
        $query_surat = "INSERT INTO tb_surat_waris (
            nomor_surat, tanggal_surat, keperluan, nama_almarhum, bin_binti, 
            tanggal_meninggal, tempat_meninggal, alamat_terakhir, 
            nama_pasangan, status_pasangan, id_pejabat, nama_camat
        ) VALUES (
            '$nomor_surat', '$tanggal_surat', '$keperluan', '$nama_almarhum', '$bin_binti', 
            '$tanggal_meninggal', '$tempat_meninggal', '$alamat_terakhir', 
            '$nama_pasangan', '$status_pasangan', $id_pejabat, '$nama_camat'
        )";

        if (!mysqli_query($koneksi, $query_surat)) {
            throw new Exception("Gagal menyimpan data utama surat waris.");
        }

        // Ambil ID utama yang baru saja didapatkan
        $id_waris = mysqli_insert_id($koneksi);

        // 3. Insert ke tabel detail: tb_waris_detail_anak (Loop Array)
        if (isset($_POST['nama_anak']) && is_array($_POST['nama_anak'])) {
            $nama_anak_arr = $_POST['nama_anak'];
            $pekerjaan_anak_arr = $_POST['pekerjaan_anak'];
            $alamat_anak_arr = $_POST['alamat_anak'];

            for ($i = 0; $i < count($nama_anak_arr); $i++) {
                $nama_anak = mysqli_real_escape_string($koneksi, $nama_anak_arr[$i]);
                $pekerjaan_anak = mysqli_real_escape_string($koneksi, $pekerjaan_anak_arr[$i]);
                $alamat_anak = mysqli_real_escape_string($koneksi, $alamat_anak_arr[$i]);

                if (!empty($nama_anak)) {
                    $query_anak = "INSERT INTO tb_waris_detail_anak (id_waris, nama_anak, pekerjaan, alamat_tinggal, status_hidup) 
                                   VALUES ($id_waris, '$nama_anak', '$pekerjaan_anak', '$alamat_anak', 'Hidup')";
                    if (!mysqli_query($koneksi, $query_anak)) {
                        throw new Exception("Gagal menyimpan data rincian anak ke-" . ($i + 1));
                    }
                }
            }
        }

        // 4. Insert ke tabel detail: tb_waris_detail_saksi (Loop Array)
        if (isset($_POST['nama_saksi']) && is_array($_POST['nama_saksi'])) {
            $nama_saksi_arr = $_POST['nama_saksi'];
            $pekerjaan_saksi_arr = $_POST['pekerjaan_saksi'];
            $alamat_saksi_arr = $_POST['alamat_saksi'];

            for ($j = 0; $j < count($nama_saksi_arr); $j++) {
                $nama_saksi = mysqli_real_escape_string($koneksi, $nama_saksi_arr[$j]);
                $pekerjaan_saksi = mysqli_real_escape_string($koneksi, $pekerjaan_saksi_arr[$j]);
                $alamat_saksi = mysqli_real_escape_string($koneksi, $alamat_saksi_arr[$j]);

                if (!empty($nama_saksi)) {
                    $query_saksi = "INSERT INTO tb_waris_detail_saksi (id_waris, nama_saksi, pekerjaan, alamat_saksi) 
                                    VALUES ($id_waris, '$nama_saksi', '$pekerjaan_saksi', '$alamat_saksi')";
                    if (!mysqli_query($koneksi, $query_saksi)) {
                        throw new Exception("Gagal menyimpan data saksi ke-" . ($j + 1));
                    }
                }
            }
        }

        // Jika semua query sukses, terapkan ke database
        mysqli_commit($koneksi);

        echo "<script>
                alert('Arsip Surat Keterangan Ahli Waris berhasil disimpan!');
                window.location.href='../../index.php?page=surat-waris';
              </script>";

    } catch (Exception $e) {
        // Jika ada yang gagal, batalkan semua transaksi
        mysqli_rollback($koneksi);

        echo "<script>
                alert('Terjadi kesalahan: " . $e->getMessage() . "');
                window.history.back();
              </script>";
    }
} else {
    header("Location: ../../index.php?page=surat-waris");
    exit;
}
?>