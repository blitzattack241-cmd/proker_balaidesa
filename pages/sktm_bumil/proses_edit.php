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

if (isset($_POST['update'])) {
    require_once __DIR__ . '/../../koneksi.php';

    // Ambil data input
    $id_sktm = mysqli_real_escape_string($koneksi, $_POST['id_sktm']);
    $nomor_surat = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $tanggal_surat = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $nama_warga = mysqli_real_escape_string($koneksi, $_POST['nama_warga']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $no_ktp = mysqli_real_escape_string($koneksi, $_POST['no_ktp']);
    $no_kk = mysqli_real_escape_string($koneksi, $_POST['no_kk']);
    $tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $agama = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $pekerjaan = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $kewarganegaraan = mysqli_real_escape_string($koneksi, $_POST['kewarganegaraan']);
    $alamat_tinggal = mysqli_real_escape_string($koneksi, $_POST['alamat_tinggal']);
    $keperluan = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    $berlaku_mulai = mysqli_real_escape_string($koneksi, $_POST['berlaku_mulai']);
    $berlaku_selesai = mysqli_real_escape_string($koneksi, $_POST['berlaku_selesai']);
    $keterangan_lain = mysqli_real_escape_string($koneksi, $_POST['keterangan_lain']);
    $id_pejabat = mysqli_real_escape_string($koneksi, $_POST['id_pejabat']);
    $nama_camat = mysqli_real_escape_string($koneksi, $_POST['nama_camat']);

    // Ambil data foto lama dari database untuk referensi ganti file
    $query_lama = mysqli_query($koneksi, "SELECT foto_depan, foto_ruang_tamu, foto_kamar, foto_dapur, foto_toilet FROM tb_sktm_bumil WHERE id_sktm = '$id_sktm'");
    $data_lama = mysqli_fetch_assoc($query_lama);

    $target_dir = "../../assets/img/sktm_bumil/";
    $daftar_foto = ['foto_depan', 'foto_ruang_tamu', 'foto_kamar', 'foto_dapur', 'foto_toilet'];
    $nama_file_final = [];

    foreach ($daftar_foto as $key_foto) {
        // Jika ada file baru yang diunggah
        if (isset($_FILES[$key_foto]) && $_FILES[$key_foto]['error'] === UPLOAD_ERR_OK) {
            $file_tmp = $_FILES[$key_foto]['tmp_name'];
            $file_name = $_FILES[$key_foto]['name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // Validasi Ekstensi
            $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png'];
            if (!in_array($file_ext, $ekstensi_diperbolehkan)) {
                echo "<script>
                        alert('Gagal! Format file $key_foto harus berupa JPG, JPEG, atau PNG.');
                        window.history.back();
                      </script>";
                exit;
            }

            // Hapus foto lama fisik jika sebelumnya ada file tersimpan
            $foto_lama = $data_lama[$key_foto];
            if (!empty($foto_lama) && file_exists($target_dir . $foto_lama)) {
                unlink($target_dir . $foto_lama);
            }

            // Upload foto baru dengan nama unik
            $new_name = $key_foto . "_" . uniqid() . "." . $file_ext;
            if (move_uploaded_file($file_tmp, $target_dir . $new_name)) {
                $nama_file_final[$key_foto] = $new_name;
            } else {
                $nama_file_final[$key_foto] = $foto_lama; // Jika gagal upload, pakai yang lama
            }
        } else {
            // Jika tidak upload file baru, pertahankan nama file lama
            $nama_file_final[$key_foto] = $data_lama[$key_foto];
        }
    }

    // Query update data
    $query_update = "UPDATE tb_sktm_bumil SET 
        nomor_surat = '$nomor_surat', 
        tanggal_surat = '$tanggal_surat', 
        nama_warga = '$nama_warga', 
        jenis_kelamin = '$jenis_kelamin', 
        no_ktp = '$no_ktp', 
        no_kk = '$no_kk', 
        tempat_lahir = '$tempat_lahir', 
        tanggal_lahir = '$tanggal_lahir', 
        agama = '$agama', 
        pekerjaan = '$pekerjaan', 
        kewarganegaraan = '$kewarganegaraan', 
        alamat_tinggal = '$alamat_tinggal', 
        keperluan = '$keperluan', 
        berlaku_mulai = '$berlaku_mulai', 
        berlaku_selesai = '$berlaku_selesai', 
        keterangan_lain = '$keterangan_lain', 
        id_pejabat = '$id_pejabat', 
        nama_camat = '$nama_camat',
        foto_depan = '" . $nama_file_final['foto_depan'] . "',
        foto_ruang_tamu = '" . $nama_file_final['foto_ruang_tamu'] . "',
        foto_kamar = '" . $nama_file_final['foto_kamar'] . "',
        foto_dapur = '" . $nama_file_final['foto_dapur'] . "',
        foto_toilet = '" . $nama_file_final['foto_toilet'] . "'
        WHERE id_sktm = '$id_sktm'";

    $eksekusi = mysqli_query($koneksi, $query_update);

    if ($eksekusi) {
        echo "<script>
                alert('Data SKTM Ibu Hamil berhasil diperbarui!');
                window.location.href = '../../index.php?page=sktm-bumil';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui data database. Silakan periksa kembali!');
                window.history.back();
              </script>";
    }
} else {
    header("Location: ../../index.php?page=sktm-bumil");
    exit;
}
?>