<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Halaman Admin
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>alert('Akses ditolak!'); window.location.href = 'index.php?page=dashboard';</script>";
    exit;
}

// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    exit;
}

// Cek parameter ID
$id = $_GET['id'] ?? 0;
$id = (int)$id;

// Cari tahu nama tabel yang digunakan
$tableTarget = 'tb_surat_kelahiran';
$checkTable = mysqli_query($koneksi, "SHOW TABLES LIKE 'surat_kelahiran'");
if ($checkTable && mysqli_num_rows($checkTable) > 0) { $tableTarget = 'surat_kelahiran'; }

// Ambil data lama untuk di-load ke form
$queryData = mysqli_query($koneksi, "SELECT * FROM `$tableTarget` WHERE id_surat = $id");
$data = mysqli_fetch_assoc($queryData);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location.href = 'index.php?page=surat-kelahiran';</script>";
    exit;
}

// Proses Update Data Form
if (isset($_POST['update'])) {
    // 1. Data Umum & KK
    $nomor_surat          = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $tanggal_surat        = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $nama_kepala_keluarga = mysqli_real_escape_string($koneksi, $_POST['nama_kepala_keluarga']);
    $nomor_kk             = mysqli_real_escape_string($koneksi, $_POST['nomor_kk']);

    // 2. Data Bayi
    $nama_bayi            = mysqli_real_escape_string($koneksi, $_POST['nama_bayi']);
    $jenis_kelamin_bayi   = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin_bayi']);
    $tempat_dilahirkan    = mysqli_real_escape_string($koneksi, $_POST['tempat_dilahirkan']);
    $tempat_kelahiran_kab = mysqli_real_escape_string($koneksi, $_POST['tempat_kelahiran_kab']);
    $hari_lahir_bayi      = mysqli_real_escape_string($koneksi, $_POST['hari_lahir_bayi']);
    $tanggal_lahir_bayi   = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir_bayi']);
    $pukul_lahir_bayi     = mysqli_real_escape_string($koneksi, $_POST['pukul_lahir_bayi']);
    $jenis_kelahiran      = mysqli_real_escape_string($koneksi, $_POST['jenis_kelahiran']);
    $kelahiran_ke         = mysqli_real_escape_string($koneksi, $_POST['kelahiran_ke']);
    $penolong_kelahiran   = mysqli_real_escape_string($koneksi, $_POST['penolong_kelahiran']);
    $berat_bayi_gram      = mysqli_real_escape_string($koneksi, $_POST['berat_bayi_gram']);
    $panjang_bayi_cm      = mysqli_real_escape_string($koneksi, $_POST['panjang_bayi_cm']);

    // 3. Data Ibu
    $nik_ibu              = mysqli_real_escape_string($koneksi, $_POST['nik_ibu']);
    $nama_ibu             = mysqli_real_escape_string($koneksi, $_POST['nama_ibu']);
    $tanggal_lahir_ibu    = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir_ibu']);
    $umur_ibu             = mysqli_real_escape_string($koneksi, $_POST['umur_ibu']);
    $pekerjaan_ibu        = mysqli_real_escape_string($koneksi, $_POST['pekerjaan_ibu']);
    $alamat_ibu           = mysqli_real_escape_string($koneksi, $_POST['alamat_ibu']);
    $desa_ibu             = mysqli_real_escape_string($koneksi, $_POST['desa_ibu']);
    $kecamatan_ibu        = mysqli_real_escape_string($koneksi, $_POST['kecamatan_ibu']);
    $kabupaten_ibu        = mysqli_real_escape_string($koneksi, $_POST['kabupaten_ibu']);

    // 4. Data Ayah
    $nik_ayah             = mysqli_real_escape_string($koneksi, $_POST['nik_ayah']);
    $nama_ayah            = mysqli_real_escape_string($koneksi, $_POST['nama_ayah']);
    $tanggal_lahir_ayah   = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir_ayah']);
    $umur_ayah            = mysqli_real_escape_string($koneksi, $_POST['umur_ayah']);
    $pekerjaan_ayah       = mysqli_real_escape_string($koneksi, $_POST['pekerjaan_ayah']);
    $alamat_ayah          = mysqli_real_escape_string($koneksi, $_POST['alamat_ayah']);

    // 5. Data Pelapor & Saksi
    $nik_pelapor          = mysqli_real_escape_string($koneksi, $_POST['nik_pelapor']);
    $nama_pelapor         = mysqli_real_escape_string($koneksi, $_POST['nama_pelapor']);
    $nik_saksi1           = mysqli_real_escape_string($koneksi, $_POST['nik_saksi1']);
    $nama_saksi1          = mysqli_real_escape_string($koneksi, $_POST['nama_saksi1']);
    $nik_saksi2           = mysqli_real_escape_string($koneksi, $_POST['nik_saksi2']);
    $nama_saksi2          = mysqli_real_escape_string($koneksi, $_POST['nama_saksi2']);

    // Query UPDATE masal
    $sqlUpdate = "UPDATE `$tableTarget` SET 
        nomor_surat = '$nomor_surat', tanggal_surat = '$tanggal_surat', nama_kepala_keluarga = '$nama_kepala_keluarga', nomor_kk = '$nomor_kk',
        nama_bayi = '$nama_bayi', jenis_kelamin_bayi = '$jenis_kelamin_bayi', tempat_dilahirkan = '$tempat_dilahirkan', tempat_kelahiran_kab = '$tempat_kelahiran_kab', hari_lahir_bayi = '$hari_lahir_bayi', tanggal_lahir_bayi = '$tanggal_lahir_bayi', pukul_lahir_bayi = '$pukul_lahir_bayi', jenis_kelahiran = '$jenis_kelahiran', kelahiran_ke = '$kelahiran_ke', penolong_kelahiran = '$penolong_kelahiran', berat_bayi_gram = '$berat_bayi_gram', panjang_bayi_cm = '$panjang_bayi_cm',
        nik_ibu = '$nik_ibu', nama_ibu = '$nama_ibu', tanggal_lahir_ibu = '$tanggal_lahir_ibu', umur_ibu = '$umur_ibu', pekerjaan_ibu = '$pekerjaan_ibu', alamat_ibu = '$alamat_ibu', desa_ibu = '$desa_ibu', kecamatan_ibu = '$kecamatan_ibu', kabupaten_ibu = '$kabupaten_ibu',
        nik_ayah = '$nik_ayah', nama_ayah = '$nama_ayah', tanggal_lahir_ayah = '$tanggal_lahir_ayah', umur_ayah = '$umur_ayah', pekerjaan_ayah = '$pekerjaan_ayah', alamat_ayah = '$alamat_ayah',
        nik_pelapor = '$nik_pelapor', nama_pelapor = '$nama_pelapor', nik_saksi1 = '$nik_saksi1', nama_saksi1 = '$nama_saksi1', nik_saksi2 = '$nik_saksi2', nama_saksi2 = '$nama_saksi2'
        WHERE id_surat = $id";
    
    if (mysqli_query($koneksi, $sqlUpdate)) {
        echo "<script>alert('Data Formulir F-2.01 Berhasil Diperbarui!'); window.location.href = 'index.php?page=surat-kelahiran';</script>";
    } else {
        echo "<div class='alert alert-danger m-4'>Error Update: " . mysqli_error($koneksi) . "</div>";
    }
}
?>

<style>
.page-title-modern {
    font-weight: 700;
    color: #2c3e50;
}

.card-modern {
    border: none !important;
    border-radius: 12px !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.section-form-title {
    font-size: 1.05rem;
    font-weight: 600;
    color: #0d6efd;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 8px;
    margin-top: 15px;
    margin-bottom: 15px;
}

.form-label-modern {
    font-weight: 600;
    color: #495057;
    font-size: 0.85rem;
}

.form-control-modern {
    border-radius: 8px !important;
    padding: 8px 12px !important;
    font-size: 0.9rem;
}
</style>

<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title-modern m-0">Edit Formulir Kelahiran (F-2.01)</h3>
            <p class="text-muted small m-0">Perbarui arsip registrasi kelahiran penduduk desa secara valid</p>
        </div>
    </div>

    <form action="" method="POST" class="needs-validation" novalidate>
        <div class="card card-modern p-4 mb-4">

            <!-- SECTION 1: DATA ATAS / KELUARGA -->
            <div class="section-form-title"><i class="fas fa-folder me-2"></i>1. REGISTRASI SURAT & KELUARGA</div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nomor Surat</label>
                    <input type="text" class="form-control form-control-modern" name="nomor_surat"
                        value="<?= htmlspecialchars($data['nomor_surat']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tanggal Surat Keluar</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_surat"
                        value="<?= $data['tanggal_surat']; ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Kepala Keluarga</label>
                    <input type="text" class="form-control form-control-modern" name="nama_kepala_keluarga"
                        value="<?= htmlspecialchars($data['nama_kepala_keluarga']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nomor KK</label>
                    <input type="text" class="form-control form-control-modern font-monospace" name="nomor_kk"
                        value="<?= htmlspecialchars($data['nomor_kk']); ?>" maxlength="16" required>
                </div>
            </div>

            <!-- SECTION 2: DATA ANAK / BAYI -->
            <div class="section-form-title text-success" style="border-color: #e8f5e9;"><i
                    class="fas fa-baby me-2"></i>2. DATA BAYI / ANAK</div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Lengkap Bayi</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_bayi"
                        value="<?= htmlspecialchars($data['nama_bayi']); ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Jenis Kelamin</label>
                    <select class="form-select form-control-modern" name="jenis_kelamin_bayi" required>
                        <option value="Laki-laki" <?= $data['jenis_kelamin_bayi'] == 'Laki-laki' ? 'selected' : ''; ?>>
                            Laki-laki</option>
                        <option value="Perempuan" <?= $data['jenis_kelamin_bayi'] == 'Perempuan' ? 'selected' : ''; ?>>
                            Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tempat Dilahirkan</label>
                    <input type="text" class="form-control form-control-modern" name="tempat_dilahirkan"
                        value="<?= htmlspecialchars($data['tempat_dilahirkan']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tempat Kelahiran (Kab/Kota)</label>
                    <input type="text" class="form-control form-control-modern" name="tempat_kelahiran_kab"
                        value="<?= htmlspecialchars($data['tempat_kelahiran_kab']); ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Hari Lahir</label>
                    <input type="text" class="form-control form-control-modern" name="hari_lahir_bayi"
                        value="<?= htmlspecialchars($data['hari_lahir_bayi']); ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_bayi"
                        value="<?= $data['tanggal_lahir_bayi']; ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pukul / Jam Lahir</label>
                    <input type="time" class="form-control form-control-modern" name="pukul_lahir_bayi"
                        value="<?= date('H:i', strtotime($data['pukul_lahir_bayi'])); ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Jenis Kelahiran</label>
                    <input type="text" class="form-control form-control-modern" name="jenis_kelahiran"
                        value="<?= htmlspecialchars($data['jenis_kelahiran']); ?>" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Anak Ke-</label>
                    <input type="number" class="form-control form-control-modern" name="kelahiran_ke"
                        value="<?= $data['kelahiran_ke']; ?>" min="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Penolong Kelahiran</label>
                    <input type="text" class="form-control form-control-modern" name="penolong_kelahiran"
                        value="<?= htmlspecialchars($data['penolong_kelahiran']); ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Berat Bayi (Gram)</label>
                    <input type="number" class="form-control form-control-modern" name="berat_bayi_gram"
                        value="<?= $data['berat_bayi_gram']; ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Panjang Bayi (Cm)</label>
                    <input type="number" class="form-control form-control-modern" name="panjang_bayi_cm"
                        value="<?= $data['panjang_bayi_cm']; ?>" required>
                </div>
            </div>

            <!-- SECTION 3: DATA IBU -->
            <div class="section-form-title text-danger" style="border-color: #ffebee;"><i
                    class="fas fa-female me-2"></i>3. DATA IBU</div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Ibu</label>
                    <input type="text" class="form-control form-control-modern" name="nik_ibu"
                        value="<?= htmlspecialchars($data['nik_ibu']); ?>" maxlength="16" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Lengkap Ibu</label>
                    <input type="text" class="form-control form-control-modern" name="nama_ibu"
                        value="<?= htmlspecialchars($data['nama_ibu']); ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tgl Lahir Ibu</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_ibu"
                        value="<?= $data['tanggal_lahir_ibu']; ?>" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur Ibu</label>
                    <input type="number" class="form-control form-control-modern" name="umur_ibu"
                        value="<?= $data['umur_ibu']; ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Pekerjaan Ibu</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_ibu"
                        value="<?= htmlspecialchars($data['pekerjaan_ibu']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Alamat Jalan/Dukuh</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_ibu"
                        value="<?= htmlspecialchars($data['alamat_ibu']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" name="desa_ibu"
                        value="<?= htmlspecialchars($data['desa_ibu']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" name="kecamatan_ibu"
                        value="<?= htmlspecialchars($data['kecamatan_ibu']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" name="kabupaten_ibu"
                        value="<?= htmlspecialchars($data['kabupaten_ibu']); ?>" required>
                </div>
            </div>

            <!-- SECTION 4: DATA AYAH -->
            <div class="section-form-title text-info" style="border-color: #e0f7fa;"><i class="fas fa-male me-2"></i>4.
                DATA AYAH</div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Ayah</label>
                    <input type="text" class="form-control form-control-modern" name="nik_ayah"
                        value="<?= htmlspecialchars($data['nik_ayah']); ?>" maxlength="16" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Lengkap Ayah</label>
                    <input type="text" class="form-control form-control-modern" name="nama_ayah"
                        value="<?= htmlspecialchars($data['nama_ayah']); ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tgl Lahir Ayah</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_ayah"
                        value="<?= $data['tanggal_lahir_ayah']; ?>" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur Ayah</label>
                    <input type="number" class="form-control form-control-modern" name="umur_ayah"
                        value="<?= $data['umur_ayah']; ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Pekerjaan Ayah</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_ayah"
                        value="<?= htmlspecialchars($data['pekerjaan_ayah']); ?>" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label form-label-modern">Alamat Lengkap Ayah</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_ayah"
                        value="<?= htmlspecialchars($data['alamat_ayah']); ?>" required>
                </div>
            </div>

            <!-- SECTION 5: PELAPOR & SAKSI -->
            <div class="section-form-title text-warning" style="border-color: #fffde7;"><i
                    class="fas fa-users me-2"></i>5. PELAPOR & SAKSI PENCATATAN</div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Pelapor</label>
                    <input type="text" class="form-control form-control-modern" name="nik_pelapor"
                        value="<?= htmlspecialchars($data['nik_pelapor']); ?>" maxlength="16" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Pelapor</label>
                    <input type="text" class="form-control form-control-modern" name="nama_pelapor"
                        value="<?= htmlspecialchars($data['nama_pelapor']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Saksi I</label>
                    <input type="text" class="form-control form-control-modern" name="nik_saksi1"
                        value="<?= htmlspecialchars($data['nik_saksi1']); ?>" maxlength="16" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Saksi I</label>
                    <input type="text" class="form-control form-control-modern" name="nama_saksi1"
                        value="<?= htmlspecialchars($data['nama_saksi1']); ?>" required>
                </div>
                <div class="col-md-3 offset-md-6">
                    <label class="form-label form-label-modern">NIK Saksi II</label>
                    <input type="text" class="form-control form-control-modern" name="nik_saksi2"
                        value="<?= htmlspecialchars($data['nik_saksi2']); ?>" maxlength="16" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Saksi II</label>
                    <input type="text" class="form-control form-control-modern" name="nama_saksi2"
                        value="<?= htmlspecialchars($data['nama_saksi2']); ?>" required>
                </div>
            </div>

            <!-- BUTTON ACTIONS -->
            <div class="d-flex justify-content-end gap-3 mt-5 pt-3 border-top">
                <a href="index.php?page=surat-kelahiran" class="btn btn-secondary px-4 py-2"
                    style="border-radius: 8px;"><i class="fas fa-arrow-left me-2"></i>Batal</a>
                <button type="submit" name="update" class="btn btn-success px-4 py-2" style="border-radius: 8px;"><i
                        class="fas fa-check me-2"></i>Simpan Perubahan</button>
            </div>

        </div>
    </form>
</div>

<script>
// Validasi Form
(function() {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>