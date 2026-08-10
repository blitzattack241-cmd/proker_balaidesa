<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek hak akses admin
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak!');
            window.location.href = '../../index.php?page=dashboard';
          </script>";
    exit;
}

if (isset($_POST['simpan'])) {
    require_once __DIR__ . '/../../koneksi.php';
    if (!$koneksi) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    require_once __DIR__ . '/../../includes/nomor_surat_helper.php';

    // Reservasi nomor surat definitif di sini (saat benar-benar disimpan)
    $nomor_surat = generateNomorSuratGlobal($koneksi, true);
    $tanggal_surat = $_POST['tanggal_surat'] ?? '';
    $nama_warga = $_POST['nama_warga'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $no_ktp = $_POST['no_ktp'] ?? '';
    $no_kk = $_POST['no_kk'] ?? '';
    $tempat_lahir = $_POST['tempat_lahir'] ?? '';
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $agama = $_POST['agama'] ?? '';
    $pekerjaan = $_POST['pekerjaan'] ?? '';
    $kewarganegaraan = $_POST['kewarganegaraan'] ?? '';
    $alamat_tinggal = $_POST['alamat_tinggal'] ?? '';
    $keperluan = $_POST['keperluan'] ?? '';
    $berlaku_mulai = $_POST['berlaku_mulai'] ?? '';
    $berlaku_selesai = $_POST['berlaku_selesai'] ?? '';
    $keterangan_lain = $_POST['keterangan_lain'] ?? '';
    $id_pejabat = $_POST['id_pejabat'] ?? '';
    $nama_camat = $_POST['nama_camat'] ?? '';

    // Pengaturan Upload Foto Rumah
    $target_dir = "../../assets/img/sktm_bumil/";

    // Buat folder otomatis jika belum ada di server
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $daftar_foto = ['foto_depan', 'foto_ruang_tamu', 'foto_kamar', 'foto_dapur', 'foto_toilet'];
    $nama_file_baru = [];

    foreach ($daftar_foto as $key_foto) {
        if (isset($_FILES[$key_foto]) && $_FILES[$key_foto]['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES[$key_foto]['tmp_name'];
            $file_name = $_FILES[$key_foto]['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // Validasi Ekstensi Gambar
            $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png'];
            if (!in_array($file_ext, $ekstensi_diperbolehkan)) {
                echo "<script>
                        alert('Gagal! Format file $key_foto harus berupa JPG, JPEG, atau PNG.');
                        window.history.back();
                      </script>";
                exit;
            }

            // Generate nama unik baru untuk menghindari file tertimpa
            $new_name = $key_foto . "_" . uniqid() . "." . $file_ext;
            $target_file = $target_dir . $new_name;

            // Pindahkan file ke target folder
            if (move_uploaded_file($file_tmp, $target_file)) {
                $nama_file_baru[$key_foto] = $new_name;
            } else {
                $nama_file_baru[$key_foto] = "";
            }
        } else {
            $nama_file_baru[$key_foto] = "";
        }
    }

    // Menggunakan Prepared Statement untuk Menghindari Error & SQL Injection
    $query_insert = "INSERT INTO tb_sktm_bumil (
        nomor_surat, tanggal_surat, nama_warga, jenis_kelamin, no_ktp, no_kk, 
        tempat_lahir, tanggal_lahir, agama, pekerjaan, kewarganegaraan, 
        alamat_tinggal, keperluan, berlaku_mulai, berlaku_selesai, keterangan_lain, 
        id_pejabat, nama_camat, foto_depan, foto_ruang_tamu, foto_kamar, foto_dapur, foto_toilet
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($koneksi, $query_insert);

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssssssssssissssss",
            $nomor_surat,
            $tanggal_surat,
            $nama_warga,
            $jenis_kelamin,
            $no_ktp,
            $no_kk,
            $tempat_lahir,
            $tanggal_lahir,
            $agama,
            $pekerjaan,
            $kewarganegaraan,
            $alamat_tinggal,
            $keperluan,
            $berlaku_mulai,
            $berlaku_selesai,
            $keterangan_lain,
            $id_pejabat,
            $nama_camat,
            $nama_file_baru['foto_depan'],
            $nama_file_baru['foto_ruang_tamu'],
            $nama_file_baru['foto_kamar'],
            $nama_file_baru['foto_dapur'],
            $nama_file_baru['foto_toilet']
        );

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    alert('Data SKTM Ibu Hamil berhasil disimpan!');
                    window.location.href = '../../index.php?page=sktm-bumil';
                  </script>";
        } else {
            $error_msg = mysqli_stmt_error($stmt);
            echo "<script>
                    alert('Gagal menyimpan data ke database! Error: " . addslashes($error_msg) . "');
                    window.history.back();
                  </script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_msg = mysqli_error($koneksi);
        echo "<script>
                alert('Gagal menyiapkan query database! Error: " . addslashes($error_msg) . "');
                window.history.back();
              </script>";
    }
} else {
    header("Location: ../../index.php?page=sktm-bumil");
    exit;
}
?>