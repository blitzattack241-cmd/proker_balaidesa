<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    return;
}

// Proses Simpan Data Penduduk
if (isset($_POST['simpan'])) {
    $nik              = mysqli_real_escape_string($koneksi, trim($_POST['nik']));
    $no_kk            = mysqli_real_escape_string($koneksi, trim($_POST['no_kk']));
    $nama             = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $jenis_kelamin    = mysqli_real_escape_string($koneksi, trim($_POST['jenis_kelamin']));
    $tempat_tgl_lahir = mysqli_real_escape_string($koneksi, trim($_POST['tempat_tgl_lahir']));
    $umur             = (int)$_POST['umur'];
    $agama            = mysqli_real_escape_string($koneksi, trim($_POST['agama']));
    $pekerjaan        = mysqli_real_escape_string($koneksi, trim($_POST['pekerjaan']));
    $alamat           = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));
    $rt               = mysqli_real_escape_string($koneksi, trim($_POST['rt']));
    $rw               = mysqli_real_escape_string($koneksi, trim($_POST['rw']));

    // Cek apakah NIK sudah terdaftar
    $cekNik = mysqli_query($koneksi, "SELECT nik FROM tb_penduduk WHERE nik = '$nik'");
    if (mysqli_num_rows($cekNik) > 0) {
        echo "<script>alert('NIK $nik sudah terdaftar di database!');</script>";
    } else {
        $query = "INSERT INTO tb_penduduk (nik, no_kk, nama, jenis_kelamin, tempat_tgl_lahir, umur, agama, pekerjaan, alamat, rt, rw) 
                  VALUES ('$nik', '$no_kk', '$nama', '$jenis_kelamin', '$tempat_tgl_lahir', '$umur', '$agama', '$pekerjaan', '$alamat', '$rt', '$rw')";
        
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
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">NIK (Nomor Induk Kependudukan) <span
                            class="text-danger">*</span></label>
                    <input type="text" name="nik" class="form-control" maxlength="16"
                        placeholder="Masukkan 16 digit NIK" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">No. KK (Kartu Keluarga)</label>
                    <input type="text" name="no_kk" class="form-control" maxlength="16"
                        placeholder="Masukkan 16 digit No. KK">
                </div>

                <div class="col-md-8">
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

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Tempat, Tanggal Lahir</label>
                    <input type="text" name="tempat_tgl_lahir" class="form-control"
                        placeholder="Contoh: Kudus, 12 Agustus 1995">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Umur (Tahun)</label>
                    <input type="number" name="umur" class="form-control" placeholder="Contoh: 28">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Agama</label>
                    <select name="agama" class="form-select">
                        <option value="Islam" selected>Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                        <option value="Khonghucu">Khonghucu</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pekerjaan</label>
                    <input type="text" name="pekerjaan" class="form-control"
                        placeholder="Contoh: Wiraswasta, Buruh, PNS">
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Alamat / Dukuh</label>
                    <input type="text" name="alamat" class="form-control" placeholder="Contoh: Dukuh Berugenjang">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">RT</label>
                    <input type="text" name="rt" class="form-control" placeholder="001">
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-semibold">RW</label>
                    <input type="text" name="rw" class="form-control" placeholder="002">
                </div>

                <div class="col-12 mt-4 text-end">
                    <button type="reset" class="btn btn-light me-2 fw-semibold px-4">Reset</button>
                    <button type="submit" name="simpan" class="btn btn-primary fw-semibold px-4">
                        <i class="fas fa-save me-1"></i> Simpan Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>