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
    $koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");

    // Ambil data dari form input
    $nomor_surat      = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $tanggal_surat    = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $nama_warga       = mysqli_real_escape_string($koneksi, $_POST['nama_warga']);
    $jenis_kelamin    = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $no_ktp           = mysqli_real_escape_string($koneksi, $_POST['no_ktp']);
    $no_kk            = mysqli_real_escape_string($koneksi, $_POST['no_kk']);
    $tempat_lahir     = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir    = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $agama            = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $pekerjaan        = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $kewarganegaraan  = mysqli_real_escape_string($koneksi, $_POST['kewarganegaraan']);
    $alamat_tinggal   = mysqli_real_escape_string($koneksi, $_POST['alamat_tinggal']);
    $keperluan        = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    $berlaku_mulai    = mysqli_real_escape_string($koneksi, $_POST['berlaku_mulai']);
    $berlaku_selesai  = mysqli_real_escape_string($koneksi, $_POST['berlaku_selesai']);
    $keterangan_lain  = mysqli_real_escape_string($koneksi, $_POST['keterangan_lain']);
    $id_pejabat       = mysqli_real_escape_string($koneksi, $_POST['id_pejabat']);
    $nama_camat       = mysqli_real_escape_string($koneksi, $_POST['nama_camat']);

    // Pengaturan Upload Foto Rumah
    // Sesuaikan target_dir ke folder penyimpanan aset gambar Anda (misal: assets/img/sktm_bumil/)
    $target_dir = "../../assets/img/sktm_bumil/";
    
    // Buat folder otomatis jika belum ada di server
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $daftar_foto = ['foto_depan', 'foto_ruang_tamu', 'foto_kamar', 'foto_dapur', 'foto_toilet'];
    $nama_file_baru = [];

    foreach ($daftar_foto as $key_foto) {
        if (isset($_FILES[$key_foto]) && $_FILES[$key_foto]['error'] === UPLOAD_ERR_OK) {
            $file_tmp  = $_FILES[$key_foto]['tmp_name'];
            $file_name = $_FILES[$key_foto]['name'];
            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            
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

    // Query simpan data ke tb_sktm_bumil
    $query_insert = "INSERT INTO tb_sktm_bumil (
        nomor_surat, tanggal_surat, nama_warga, jenis_kelamin, no_ktp, no_kk, 
        tempat_lahir, tanggal_lahir, agama, pekerjaan, kewarganegaraan, 
        alamat_tinggal, keperluan, berlaku_mulai, berlaku_selesai, keterangan_lain, 
        id_pejabat, nama_camat, foto_depan, foto_ruang_tamu, foto_kamar, foto_dapur, foto_toilet
    ) VALUES (
        '$nomor_surat', '$tanggal_surat', '$nama_warga', '$jenis_kelamin', '$no_ktp', '$no_kk', 
        '$tempat_lahir', '$tanggal_lahir', '$agama', '$pekerjaan', '$kewarganegaraan', 
        '$alamat_tinggal', '$keperluan', '$berlaku_mulai', '$berlaku_selesai', '$keterangan_lain', 
        '$id_pejabat', '$nama_camat', 
        '".$nama_file_baru['foto_depan']."', 
        '".$nama_file_baru['foto_ruang_tamu']."', 
        '".$nama_file_baru['foto_kamar']."', 
        '".$nama_file_baru['foto_dapur']."', 
        '".$nama_file_baru['foto_toilet']."'
    )";

    $eksekusi = mysqli_query($koneksi, $query_insert);

    if ($eksekusi) {
        // Berhasil, arahkan kembali ke menu list SKTM Bumil
        echo "<script>
                alert('Data SKTM Ibu Hamil berhasil disimpan!');
                window.location.href = '../../index.php?page=sktm-bumil';
              </script>";
    } else {
        // Gagal eksekusi database
        echo "<script>
                alert('Gagal menyimpan data ke database. Silakan periksa kembali!');
                window.history.back();
              </script>";
    }
} else {
    // Jika diakses tanpa submit form
    header("Location: ../../index.php?page=sktm-bumil");
    exit;
}
?>