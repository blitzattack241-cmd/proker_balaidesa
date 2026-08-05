<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Halaman Access Control
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

// Target nama tabel master
$tableTarget = 'tb_surat_kelahiran';

require_once __DIR__ . '/../../includes/nomor_surat_helper.php';

// Nomor surat global otomatis untuk semua jenis surat
$nomor_surat_otomatis = generateNomorSuratGlobal($koneksi, false); // preview saja, tidak menambah nomor


// PROSES SIMPAN DATA FORM
if (isset($_POST['simpan'])) {
    // 1. Data Umum & KK
    $nomor_surat = mysqli_real_escape_string($koneksi, generateNomorSuratGlobal($koneksi, true));
    $tanggal_surat = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $nama_kepala_keluarga = mysqli_real_escape_string($koneksi, $_POST['nama_kepala_keluarga']);
    $nomor_kk = mysqli_real_escape_string($koneksi, $_POST['nomor_kk']);

    // 2. Data Bayi
    $nama_bayi = mysqli_real_escape_string($koneksi, $_POST['nama_bayi']);
    $jenis_kelamin_bayi = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin_bayi']);
    $tempat_dilahirkan = mysqli_real_escape_string($koneksi, $_POST['tempat_dilahirkan']);
    $tempat_kelahiran_kab = mysqli_real_escape_string($koneksi, $_POST['tempat_kelahiran_kab']);
    $hari_lahir_bayi = mysqli_real_escape_string($koneksi, $_POST['hari_lahir_bayi']);
    $tanggal_lahir_bayi = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir_bayi']);
    $pukul_lahir_bayi = mysqli_real_escape_string($koneksi, $_POST['pukul_lahir_bayi']);
    $jenis_kelahiran = mysqli_real_escape_string($koneksi, $_POST['jenis_kelahiran']);
    $kelahiran_ke = mysqli_real_escape_string($koneksi, $_POST['kelahiran_ke']);
    $penolong_kelahiran = mysqli_real_escape_string($koneksi, $_POST['penolong_kelahiran']);
    $berat_bayi_gram = mysqli_real_escape_string($koneksi, $_POST['berat_bayi_gram']);
    $panjang_bayi_cm = mysqli_real_escape_string($koneksi, $_POST['panjang_bayi_cm']);

    // 3. Data Ibu
    $nik_ibu = mysqli_real_escape_string($koneksi, $_POST['nik_ibu']);
    $nama_ibu = mysqli_real_escape_string($koneksi, $_POST['nama_ibu']);
    $tanggal_lahir_ibu = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir_ibu']);
    $umur_ibu = mysqli_real_escape_string($koneksi, $_POST['umur_ibu']);
    $pekerjaan_ibu = mysqli_real_escape_string($koneksi, $_POST['pekerjaan_ibu']);
    $alamat_ibu = mysqli_real_escape_string($koneksi, $_POST['alamat_ibu']);
    $desa_ibu = mysqli_real_escape_string($koneksi, $_POST['desa_ibu']);
    $kecamatan_ibu = mysqli_real_escape_string($koneksi, $_POST['kecamatan_ibu']);
    $kabupaten_ibu = mysqli_real_escape_string($koneksi, $_POST['kabupaten_ibu']);

    // 4. Data Ayah
    $nik_ayah = mysqli_real_escape_string($koneksi, $_POST['nik_ayah']);
    $nama_ayah = mysqli_real_escape_string($koneksi, $_POST['nama_ayah']);
    $tanggal_lahir_ayah = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir_ayah']);
    $umur_ayah = mysqli_real_escape_string($koneksi, $_POST['umur_ayah']);
    $pekerjaan_ayah = mysqli_real_escape_string($koneksi, $_POST['pekerjaan_ayah']);
    $alamat_ayah = mysqli_real_escape_string($koneksi, $_POST['alamat_ayah']);

    // 5. Data Pelapor & Saksi
    $nik_pelapor = mysqli_real_escape_string($koneksi, $_POST['nik_pelapor']);
    $nama_pelapor = mysqli_real_escape_string($koneksi, $_POST['nama_pelapor']);
    $nik_saksi1 = mysqli_real_escape_string($koneksi, $_POST['nik_saksi1']);
    $nama_saksi1 = mysqli_real_escape_string($koneksi, $_POST['nama_saksi1']);
    $nik_saksi2 = mysqli_real_escape_string($koneksi, $_POST['nik_saksi2']);
    $nama_saksi2 = mysqli_real_escape_string($koneksi, $_POST['nama_saksi2']);

    // Query INSERT masal ke kolom master
    $sqlInsert = "INSERT INTO `$tableTarget` (
        nomor_surat, tanggal_surat, nama_kepala_keluarga, nomor_kk,
        nama_bayi, jenis_kelamin_bayi, tempat_dilahirkan, tempat_kelahiran_kab, hari_lahir_bayi, tanggal_lahir_bayi, pukul_lahir_bayi, jenis_kelahiran, kelahiran_ke, penolong_kelahiran, berat_bayi_gram, panjang_bayi_cm,
        nik_ibu, nama_ibu, tanggal_lahir_ibu, umur_ibu, pekerjaan_ibu, alamat_ibu, desa_ibu, kecamatan_ibu, kabupaten_ibu,
        nik_ayah, nama_ayah, tanggal_lahir_ayah, umur_ayah, pekerjaan_ayah, alamat_ayah,
        nik_pelapor, nama_pelapor, nik_saksi1, nama_saksi1, nik_saksi2, nama_saksi2
    ) VALUES (
        '$nomor_surat', '$tanggal_surat', '$nama_kepala_keluarga', '$nomor_kk',
        '$nama_bayi', '$jenis_kelamin_bayi', '$tempat_dilahirkan', '$tempat_kelahiran_kab', '$hari_lahir_bayi', '$tanggal_lahir_bayi', '$pukul_lahir_bayi', '$jenis_kelahiran', '$kelahiran_ke', '$penolong_kelahiran', '$berat_bayi_gram', '$panjang_bayi_cm',
        '$nik_ibu', '$nama_ibu', '$tanggal_lahir_ibu', '$umur_ibu', '$pekerjaan_ibu', '$alamat_ibu', '$desa_ibu', '$kecamatan_ibu', '$kabupaten_ibu',
        '$nik_ayah', '$nama_ayah', '$tanggal_lahir_ayah', '$umur_ayah', '$pekerjaan_ayah', '$alamat_ayah',
        '$nik_pelapor', '$nama_pelapor', '$nik_saksi1', '$nama_saksi1', '$nik_saksi2', '$nama_saksi2'
    )";

    if (mysqli_query($koneksi, $sqlInsert)) {
        echo "<script>alert('Data Formulir F-2.01 Berhasil Disimpan!'); window.location.href = 'index.php?page=surat-kelahiran';</script>";
    } else {
        echo "<div class='alert alert-danger m-4'>Error: " . mysqli_error($koneksi) . "</div>";
    }
}
?>

<!-- Library Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

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

    .box-pencarian-container {
        background-color: #f8faff;
        border: 1px dashed #0d6efd;
        border-radius: 10px;
        padding: 15px;
    }
</style>

<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="page-title-modern m-0">Tambah Formulir Kelahiran (F-2.01)</h3>
            <p class="text-muted small m-0">Input data registrasi kelahiran penduduk desa secara lengkap sesuai standar
                Capil</p>
        </div>
    </div>

    <!-- Box Pencarian Penduduk Terpadu -->
    <div class="card card-modern mb-4">
        <div class="card-body box-pencarian-container">
            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-search me-1"></i> PENCARIAN DATA PENDUDUK OTOMATIS
            </h6>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label form-label-modern text-danger"><i class="fas fa-female me-1"></i> Cari Data
                        Ibu</label>
                    <select id="cari_ibu" class="form-select" style="width: 100%;"></select>
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern text-info"><i class="fas fa-male me-1"></i> Cari Data
                        Ayah</label>
                    <select id="cari_ayah" class="form-select" style="width: 100%;"></select>
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern text-warning"><i class="fas fa-user-edit me-1"></i> Cari
                        Data Pelapor</label>
                    <select id="cari_pelapor" class="form-select" style="width: 100%;"></select>
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-modern text-secondary"><i class="fas fa-user-friends me-1"></i>
                        Cari Data Saksi I</label>
                    <select id="cari_saksi1" class="form-select" style="width: 100%;"></select>
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-modern text-secondary"><i class="fas fa-user-friends me-1"></i>
                        Cari Data Saksi II</label>
                    <select id="cari_saksi2" class="form-select" style="width: 100%;"></select>
                </div>
            </div>
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
                        value="<?= htmlspecialchars($nomor_surat_otomatis); ?>" required>
                    <small class="text-muted" style="font-size: 0.75rem;">*Terisi otomatis (dapat diubah manual)</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tanggal Surat Keluar</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_surat"
                        value="<?= date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Kepala Keluarga</label>
                    <input type="text" class="form-control form-control-modern" name="nama_kepala_keluarga"
                        id="input_nama_kepala_keluarga" placeholder="Nama Kepala Keluarga" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nomor KK</label>
                    <input type="text" class="form-control form-control-modern font-monospace" name="nomor_kk"
                        id="input_nomor_kk" maxlength="16" value="331904" placeholder="16 Digit No. KK" required>
                </div>
            </div>

            <!-- SECTION 2: DATA ANAK / BAYI -->
            <div class="section-form-title text-success" style="border-color: #e8f5e9;"><i
                    class="fas fa-baby me-2"></i>2. DATA BAYI / ANAK</div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Lengkap Bayi</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_bayi"
                        placeholder="NAMA LENGKAP BAYI" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Jenis Kelamin</label>
                    <select class="form-select form-control-modern" name="jenis_kelamin_bayi" required>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tempat Dilahirkan</label>
                    <input type="text" class="form-control form-control-modern" name="tempat_dilahirkan"
                        placeholder="RS / Puskesmas / Rumah" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tempat Kelahiran (Kab/Kota)</label>
                    <input type="text" class="form-control form-control-modern" name="tempat_kelahiran_kab"
                        value="Kudus" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Hari Lahir</label>
                    <input type="text" class="form-control form-control-modern" name="hari_lahir_bayi"
                        placeholder="Senin/Selasa..." required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_bayi" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pukul / Jam Lahir</label>
                    <input type="time" class="form-control form-control-modern" name="pukul_lahir_bayi" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Jenis Kelahiran</label>
                    <input type="text" class="form-control form-control-modern" name="jenis_kelahiran" value="Tunggal"
                        placeholder="Tunggal/Kembar" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Anak Ke-</label>
                    <input type="number" class="form-control form-control-modern" name="kelahiran_ke" min="1"
                        placeholder="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Penolong Kelahiran</label>
                    <input type="text" class="form-control form-control-modern" name="penolong_kelahiran"
                        placeholder="Dokter / Bidan / Dukun" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Berat Bayi (Gram)</label>
                    <input type="number" class="form-control form-control-modern" name="berat_bayi_gram"
                        placeholder="e.g. 3000" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Panjang Bayi (Cm)</label>
                    <input type="number" class="form-control form-control-modern" name="panjang_bayi_cm"
                        placeholder="e.g. 50" required>
                </div>
            </div>

            <!-- SECTION 3: DATA IBU -->
            <div class="section-form-title text-danger" style="border-color: #ffebee;"><i
                    class="fas fa-female me-2"></i>3. DATA IBU</div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Ibu</label>
                    <input type="text" class="form-control form-control-modern" name="nik_ibu" id="input_nik_ibu"
                        maxlength="16" placeholder="16 Digit NIK Ibu" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Lengkap Ibu</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_ibu"
                        id="input_nama_ibu" placeholder="Nama Lengkap Ibu" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tgl Lahir Ibu</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_ibu"
                        id="input_tanggal_lahir_ibu" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur Ibu</label>
                    <input type="number" class="form-control form-control-modern" name="umur_ibu" id="input_umur_ibu"
                        placeholder="Thn" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Pekerjaan Ibu</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_ibu"
                        id="input_pekerjaan_ibu" placeholder="IRT / Swasta" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Alamat Jalan/Dukuh</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_ibu" id="input_alamat_ibu"
                        placeholder="Rt 01 Rw 02" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" name="desa_ibu" id="input_desa_ibu"
                        value="Berugenjang" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" name="kecamatan_ibu"
                        id="input_kecamatan_ibu" value="Undaan" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" name="kabupaten_ibu"
                        id="input_kabupaten_ibu" value="Kudus" required>
                </div>
            </div>

            <!-- SECTION 4: DATA AYAH -->
            <div class="section-form-title text-info" style="border-color: #e0f7fa;"><i class="fas fa-male me-2"></i>4.
                DATA AYAH</div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Ayah</label>
                    <input type="text" class="form-control form-control-modern" name="nik_ayah" id="input_nik_ayah"
                        maxlength="16" placeholder="16 Digit NIK Ayah" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Lengkap Ayah</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_ayah"
                        id="input_nama_ayah" placeholder="Nama Lengkap Ayah" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tgl Lahir Ayah</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_ayah"
                        id="input_tanggal_lahir_ayah" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur Ayah</label>
                    <input type="number" class="form-control form-control-modern" name="umur_ayah" id="input_umur_ayah"
                        placeholder="Thn" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Pekerjaan Ayah</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_ayah"
                        id="input_pekerjaan_ayah" placeholder="Pekerjaan Ayah" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label form-label-modern">Alamat Lengkap Ayah</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_ayah"
                        id="input_alamat_ayah"
                        placeholder="Isi alamat jika berbeda dengan Ibu, jika sama tulis 'Sama dengan Ibu'" required>
                </div>
            </div>

            <!-- SECTION 5: PELAPOR & SAKSI -->
            <div class="section-form-title text-warning" style="border-color: #fffde7;"><i
                    class="fas fa-users me-2"></i>5. PELAPOR & SAKSI PENCATATAN</div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Pelapor</label>
                    <input type="text" class="form-control form-control-modern" name="nik_pelapor"
                        id="input_nik_pelapor" maxlength="16" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Pelapor</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_pelapor"
                        id="input_nama_pelapor" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Saksi I</label>
                    <input type="text" class="form-control form-control-modern" name="nik_saksi1" id="input_nik_saksi1"
                        maxlength="16" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Saksi I</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_saksi1"
                        id="input_nama_saksi1" required>
                </div>
                <div class="col-md-3 offset-md-6">
                    <label class="form-label form-label-modern">NIK Saksi II</label>
                    <input type="text" class="form-control form-control-modern" name="nik_saksi2" id="input_nik_saksi2"
                        maxlength="16" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Saksi II</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_saksi2"
                        id="input_nama_saksi2" required>
                </div>
            </div>

            <!-- FOOTER ACTION BUTTONS -->
            <div class="d-flex justify-content-end gap-3 mt-5 pt-3 border-top">
                <a href="index.php?page=surat-kelahiran" class="btn btn-secondary px-4 py-2"
                    style="border-radius: 8px;"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
                <button type="submit" name="simpan" class="btn btn-primary px-4 py-2"
                    style="border-radius: 8px; background: linear-gradient(135deg, #0d6efd, #0056b3);"><i
                        class="fas fa-save me-2"></i>Simpan Formulir</button>
            </div>

        </div>
    </form>
</div>

<!-- Library jQuery & Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Fungsi untuk Menghitung Umur Otomatis
    function hitungUmur(tglLahir) {
        if (!tglLahir) return '';
        var today = new Date();
        var birthDate = new Date(tglLahir);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age >= 0 ? age : '';
    }

    $(document).ready(function () {

        // Helper Konfigurasi Select2 AJAX
        function buildSelect2Config(placeholderText) {
            return {
                theme: 'bootstrap-5',
                placeholder: placeholderText,
                allowClear: true,
                minimumInputLength: 2,
                ajax: {
                    url: 'api/get_penduduk.php',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.results
                        };
                    },
                    cache: true
                }
            };
        }

        // 1. SELECT2 CARI IBU
        $('#cari_ibu').select2(buildSelect2Config('-- Cari NIK / Nama Ibu --'));
        $('#cari_ibu').on('select2:select', function (e) {
            var d = e.params.data;
            console.log('===== IBU DATA =====');
            console.log('Full Data Object:', d);
            console.log('tgl_lahir:', d.tgl_lahir);
            console.log('tanggal_lahir:', d.tanggal_lahir);
            console.log('umur:', d.umur);
            console.log('Debug Info:', d._debug_tgl);

            $('#input_nik_ibu').val(d.nik || d.id || '');
            $('#input_nama_ibu').val(d.nama || '');

            // Mengisi Tanggal Lahir Ibu
            var tglLahir = d.tgl_lahir || d.tanggal_lahir || '';
            console.log('tglLahir to set:', tglLahir);

            if (tglLahir && tglLahir.trim() !== '') {
                $('#input_tanggal_lahir_ibu').val(tglLahir.trim());
                console.log('Set tanggal_lahir_ibu to:', tglLahir.trim());

                var umurValue = parseInt(d.umur) || 0;
                if (umurValue > 0) {
                    $('#input_umur_ibu').val(umurValue);
                    console.log('Set umur_ibu to:', umurValue);
                } else {
                    var calc = hitungUmur(tglLahir);
                    $('#input_umur_ibu').val(calc);
                    console.log('Calculated umur_ibu:', calc);
                }
            } else {
                console.log('No tglLahir found, trying umur only');
                if (d.umur && parseInt(d.umur) > 0) {
                    $('#input_umur_ibu').val(parseInt(d.umur));
                    console.log('Set umur_ibu from API:', d.umur);
                }
            }

            if (d.pekerjaan) $('#input_pekerjaan_ibu').val(d.pekerjaan);
            if (d.alamat_tinggal || d.alamat_jalan || d.alamat_lengkap || d.alamat) {
                $('#input_alamat_ibu').val(d.alamat_tinggal || d.alamat_jalan || d.alamat_lengkap || d
                    .alamat);
            }
            if (d.no_kk || d.nokk) $('#input_nomor_kk').val(d.no_kk || d.nokk);
            if (d.desa) $('#input_desa_ibu').val(d.desa);
            if (d.kecamatan) $('#input_kecamatan_ibu').val(d.kecamatan);
            if (d.kabupaten) $('#input_kabupaten_ibu').val(d.kabupaten);
        });

        $('#cari_ibu').on('select2:clear', function () {
            $('#input_nik_ibu, #input_nama_ibu, #input_tanggal_lahir_ibu, #input_umur_ibu, #input_pekerjaan_ibu, #input_alamat_ibu')
                .val('');
            $('#input_desa_ibu').val('Berugenjang');
            $('#input_kecamatan_ibu').val('Undaan');
            $('#input_kabupaten_ibu').val('Kudus');
        });

        // 2. SELECT2 CARI AYAH
        $('#cari_ayah').select2(buildSelect2Config('-- Cari NIK / Nama Ayah --'));
        $('#cari_ayah').on('select2:select', function (e) {
            var d = e.params.data;
            console.log('===== AYAH DATA =====');
            console.log('Full Data Object:', d);
            console.log('tgl_lahir:', d.tgl_lahir);
            console.log('tanggal_lahir:', d.tanggal_lahir);
            console.log('umur:', d.umur);
            console.log('Debug Info:', d._debug_tgl);

            $('#input_nik_ayah').val(d.nik || d.id || '');
            $('#input_nama_ayah').val(d.nama || '');

            // Mengisi Tanggal Lahir Ayah
            var tglLahir = d.tgl_lahir || d.tanggal_lahir || '';
            console.log('tglLahir to set:', tglLahir);

            if (tglLahir && tglLahir.trim() !== '') {
                $('#input_tanggal_lahir_ayah').val(tglLahir.trim());
                console.log('Set tanggal_lahir_ayah to:', tglLahir.trim());

                var umurValue = parseInt(d.umur) || 0;
                if (umurValue > 0) {
                    $('#input_umur_ayah').val(umurValue);
                    console.log('Set umur_ayah to:', umurValue);
                } else {
                    var calc = hitungUmur(tglLahir);
                    $('#input_umur_ayah').val(calc);
                    console.log('Calculated umur_ayah:', calc);
                }
            } else {
                console.log('No tglLahir found, trying umur only');
                if (d.umur && parseInt(d.umur) > 0) {
                    $('#input_umur_ayah').val(parseInt(d.umur));
                    console.log('Set umur_ayah from API:', d.umur);
                }
            }

            if (d.pekerjaan) $('#input_pekerjaan_ayah').val(d.pekerjaan);
            if (d.alamat_tinggal || d.alamat_jalan || d.alamat_lengkap || d.alamat) {
                $('#input_alamat_ayah').val(d.alamat_tinggal || d.alamat_jalan || d.alamat_lengkap || d
                    .alamat);
            }

            // Otomatis isi Nama Kepala Keluarga jika belum terisi
            if (!$('#input_nama_kepala_keluarga').val()) {
                $('#input_nama_kepala_keluarga').val(d.nama || '');
            }
            if (d.no_kk || d.nokk) $('#input_nomor_kk').val(d.no_kk || d.nokk);
        });

        $('#cari_ayah').on('select2:clear', function () {
            $('#input_nik_ayah, #input_nama_ayah, #input_tanggal_lahir_ayah, #input_umur_ayah, #input_pekerjaan_ayah, #input_alamat_ayah')
                .val('');
        });

        // 3. SELECT2 CARI PELAPOR
        $('#cari_pelapor').select2(buildSelect2Config('-- Cari NIK / Nama Pelapor --'));
        $('#cari_pelapor').on('select2:select', function (e) {
            var d = e.params.data;
            $('#input_nik_pelapor').val(d.nik || d.id || '');
            $('#input_nama_pelapor').val(d.nama || '');
        });
        $('#cari_pelapor').on('select2:clear', function () {
            $('#input_nik_pelapor, #input_nama_pelapor').val('');
        });

        // 4. SELECT2 CARI SAKSI 1
        $('#cari_saksi1').select2(buildSelect2Config('-- Cari NIK / Nama Saksi I --'));
        $('#cari_saksi1').on('select2:select', function (e) {
            var d = e.params.data;
            $('#input_nik_saksi1').val(d.nik || d.id || '');
            $('#input_nama_saksi1').val(d.nama || '');
        });
        $('#cari_saksi1').on('select2:clear', function () {
            $('#input_nik_saksi1, #input_nama_saksi1').val('');
        });

        // 5. SELECT2 CARI SAKSI 2
        $('#cari_saksi2').select2(buildSelect2Config('-- Cari NIK / Nama Saksi II --'));
        $('#cari_saksi2').on('select2:select', function (e) {
            var d = e.params.data;
            $('#input_nik_saksi2').val(d.nik || d.id || '');
            $('#input_nama_saksi2').val(d.nama || '');
        });
        $('#cari_saksi2').on('select2:clear', function () {
            $('#input_nik_saksi2, #input_nama_saksi2').val('');
        });

        // Event Listener: Hitung Umur Otomatis Saat Input Tanggal Lahir Diubah Manual
        $('#input_tanggal_lahir_ibu').on('change keyup', function () {
            var val = $(this).val();
            if (val) {
                $('#input_umur_ibu').val(hitungUmur(val));
            }
        });

        $('#input_tanggal_lahir_ayah').on('change keyup', function () {
            var val = $(this).val();
            if (val) {
                $('#input_umur_ayah').val(hitungUmur(val));
            }
        });

    });

    // Validasi Bootstrap
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>