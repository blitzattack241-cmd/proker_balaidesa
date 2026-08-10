<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi Database
require_once __DIR__ . '/../koneksi.php';
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    return;
}

// Proses Simpan Data Penduduk
if (isset($_POST['simpan'])) {
    $nik = mysqli_real_escape_string($koneksi, trim($_POST['nik']));
    $no_kk = mysqli_real_escape_string($koneksi, trim($_POST['no_kk']));
    $kepala_kk = mysqli_real_escape_string($koneksi, trim($_POST['kepala_kk']));
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $jenis_kelamin = mysqli_real_escape_string($koneksi, trim($_POST['jenis_kelamin']));
    $status_keluarga = mysqli_real_escape_string($koneksi, trim($_POST['status_keluarga']));
    $tempat_lahir = mysqli_real_escape_string($koneksi, trim($_POST['tempat_lahir']));
    $tgl_lahir = mysqli_real_escape_string($koneksi, trim($_POST['tgl_lahir']));
    $status_pernikahan = mysqli_real_escape_string($koneksi, trim($_POST['status_pernikahan']));
    $agama = mysqli_real_escape_string($koneksi, trim($_POST['agama']));
    $kewarganegaraan = mysqli_real_escape_string($koneksi, trim($_POST['kewarganegaraan']));
    $suku = mysqli_real_escape_string($koneksi, trim($_POST['suku']));
    $pendidikan = mysqli_real_escape_string($koneksi, trim($_POST['pendidikan']));
    $pekerjaan = mysqli_real_escape_string($koneksi, trim($_POST['pekerjaan']));
    $rt = mysqli_real_escape_string($koneksi, trim($_POST['rt']));
    $rw = mysqli_real_escape_string($koneksi, trim($_POST['rw']));

    // Format nilai tanggal untuk Query
    $tgl_lahir_val = !empty($tgl_lahir) ? "'$tgl_lahir'" : "NULL";

    // Cek apakah NIK sudah terdaftar
    $cekNik = mysqli_query($koneksi, "SELECT nik FROM tb_penduduk WHERE nik = '$nik'");
    if (mysqli_num_rows($cekNik) > 0) {
        echo "<script>alert('NIK $nik sudah terdaftar di database!');</script>";
    } else {
        $query = "INSERT INTO tb_penduduk 
            (rt, rw, no_kk, kepala_kk, nik, nama, jenis_kelamin, status_keluarga, tempat_lahir, tgl_lahir, status_pernikahan, agama, kewarganegaraan, suku, pendidikan, pekerjaan) 
            VALUES 
            ('$rt', '$rw', '$no_kk', '$kepala_kk', '$nik', '$nama', '$jenis_kelamin', '$status_keluarga', '$tempat_lahir', $tgl_lahir_val, '$status_pernikahan', '$agama', '$kewarganegaraan', '$suku', '$pendidikan', '$pekerjaan')";

        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Data penduduk berhasil ditambahkan!'); window.location='index.php?page=penduduk';</script>";
            exit();
        } else {
            echo "<script>alert('Gagal menyimpan data: " . mysqli_error($koneksi) . "');</script>";
        }
    }
}
?>

<style>
    .form-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 2rem;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #0f172a;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1.25rem;
    }
</style>

<div class="container-fluid px-4 py-3">

    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Tambah Penduduk Baru</h2>
            <p class="text-muted mb-0">Isi formulir di bawah untuk menambahkan data penduduk Desa Berugenjang.</p>
        </div>
        <a href="index.php?page=penduduk" class="btn btn-outline-secondary rounded-3 fw-semibold px-3 py-2 shadow-sm">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Form Tambah Penduduk -->
    <div class="form-card shadow-sm">
        <form action="" method="POST">

            <!-- SEKSI 1: KELUARGA & WILAYAH -->
            <div class="section-title">
                <i class="fas fa-home me-2 text-primary"></i>Data Keluarga & Wilayah
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">No. KK (Kartu Keluarga)</label>
                    <input type="text" name="no_kk" class="form-control" maxlength="16" placeholder="16 digit No. KK">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Nama Kepala Keluarga</label>
                    <input type="text" name="kepala_kk" class="form-control" placeholder="Nama Kepala KK">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">RT</label>
                    <input type="text" name="rt" class="form-control" placeholder="Contoh: 1">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">RW</label>
                    <input type="text" name="rw" class="form-control" placeholder="Contoh: 1">
                </div>
            </div>

            <!-- SEKSI 2: IDENTITAS DIRI -->
            <div class="section-title">
                <i class="fas fa-user me-2 text-primary"></i>Identitas Diri
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">NIK (Nomor Induk Kependudukan) <span
                            class="text-danger">*</span></label>
                    <input type="text" name="nik" class="form-control" maxlength="16"
                        placeholder="Masukkan 16 digit NIK" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status Hubungan Keluarga</label>
                    <select name="status_keluarga" class="form-select">
                        <option value="KEPALA KELUARGA">KEPALA KELUARGA</option>
                        <option value="SUAMI">SUAMI</option>
                        <option value="ISTRI">ISTRI</option>
                        <option value="ANAK" selected>ANAK</option>
                        <option value="CUCU">CUCU</option>
                        <option value="ORANG TUA">ORANG TUA</option>
                        <option value="MERTUA">MERTUA</option>
                        <option value="FAMILI LAIN">FAMILI LAIN</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status Pernikahan</label>
                    <select name="status_pernikahan" class="form-select">
                        <option value="BELUM KAWIN" selected>BELUM KAWIN</option>
                        <option value="KAWIN">KAWIN</option>
                        <option value="CERAI HIDUP">CERAI HIDUP</option>
                        <option value="CERAI MATI">CERAI MATI</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" class="form-control" placeholder="Contoh: Kudus">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" class="form-control">
                </div>
            </div>

            <!-- SEKSI 3: INFORMASI TAMBAHAN -->
            <div class="section-title">
                <i class="fas fa-info-circle me-2 text-primary"></i>Informasi Tambahan
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Agama</label>
                    <select name="agama" class="form-select">
                        <option value="ISLAM" selected>ISLAM</option>
                        <option value="KRISTEN">KRISTEN</option>
                        <option value="KATOLIK">KATOLIK</option>
                        <option value="HINDU">HINDU</option>
                        <option value="BUDDHA">BUDDHA</option>
                        <option value="KHONGHUCU">KHONGHUCU</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Kewarganegaraan</label>
                    <input type="text" name="kewarganegaraan" class="form-control" value="WNI">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Suku</label>
                    <input type="text" name="suku" class="form-control" value="JAWA">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pendidikan Terakhir</label>
                    <input type="text" name="pendidikan" class="form-control" placeholder="Contoh: SLTA/SEDERAJAT">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pekerjaan</label>
                    <input type="text" name="pekerjaan" class="form-control" placeholder="Contoh: WIRASWASTA">
                </div>
            </div>

            <!-- BUTTON ACTION -->
            <div class="col-12 mt-4 text-end border-top pt-3">
                <button type="reset" class="btn btn-light me-2 fw-semibold px-4">Reset</button>
                <button type="submit" name="simpan" class="btn btn-primary fw-semibold px-4">
                    <i class="fas fa-save me-1"></i> Simpan Data
                </button>
            </div>

        </form>
    </div>
</div>