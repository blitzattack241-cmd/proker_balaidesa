<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Halaman
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

// Target nama tabel
$tableTarget = 'tb_surat_kematian';
$checkTable = mysqli_query($koneksi, "SHOW TABLES LIKE 'surat_kematian'");
if ($checkTable && mysqli_num_rows($checkTable) > 0) {
    $tableTarget = 'surat_kematian';
}

require_once __DIR__ . '/../../includes/nomor_surat_helper.php';

// Nomor surat global otomatis untuk semua jenis surat
$nomor_surat_otomatis = generateNomorSuratGlobal($koneksi, false); // preview saja, tidak menambah nomor


// PROSES SIMPAN DATA FORM
if (isset($_POST['simpan'])) {
    // 1. Data Umum Registrasi
    // Reservasi nomor surat definitif di sini (saat benar-benar disimpan),
    // bukan saat halaman form dibuka, agar nomor tidak bertambah saat batal/reload.
    $nomor_surat = mysqli_real_escape_string($koneksi, generateNomorSuratGlobal($koneksi, true));
    $tanggal_surat = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);

    // 2. Data Jenazah (Sesuai Struktur Blangko F-2.29)
    $nik_jenazah = mysqli_real_escape_string($koneksi, $_POST['nik_jenazah']);
    $nama_jenazah = mysqli_real_escape_string($koneksi, $_POST['nama_jenazah']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $umur = mysqli_real_escape_string($koneksi, $_POST['umur']);
    $tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $agama = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $pekerjaan = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $alamat_jenazah = mysqli_real_escape_string($koneksi, $_POST['alamat_jenazah']);
    $desa_jenazah = mysqli_real_escape_string($koneksi, $_POST['desa_jenazah']);
    $kecamatan_jenazah = mysqli_real_escape_string($koneksi, $_POST['kecamatan_jenazah']);
    $kabupaten_jenazah = mysqli_real_escape_string($koneksi, $_POST['kabupaten_jenazah']);

    // 3. Data Kematian
    $hari_kematian = mysqli_real_escape_string($koneksi, $_POST['hari_kematian']);
    $tanggal_kematian = mysqli_real_escape_string($koneksi, $_POST['tanggal_kematian']);
    $jam_kematian = mysqli_real_escape_string($koneksi, $_POST['jam_kematian']);
    $tempat_kematian = mysqli_real_escape_string($koneksi, $_POST['tempat_kematian']);
    $penyebab_kematian = mysqli_real_escape_string($koneksi, $_POST['penyebab_kematian']);

    // 4. Data Pelapor & Saksi
    $nik_pelapor = mysqli_real_escape_string($koneksi, $_POST['nik_pelapor']);
    $nama_pelapor = mysqli_real_escape_string($koneksi, $_POST['nama_pelapor']);
    $nik_saksi1 = mysqli_real_escape_string($koneksi, $_POST['nik_saksi1']);
    $nama_saksi1 = mysqli_real_escape_string($koneksi, $_POST['nama_saksi1']);
    $nik_saksi2 = mysqli_real_escape_string($koneksi, $_POST['nik_saksi2']);
    $nama_saksi2 = mysqli_real_escape_string($koneksi, $_POST['nama_saksi2']);

    // Ambil daftar kolom aktual di tabel target
    $existingColumns = [];
    $columnResult = mysqli_query($koneksi, "SHOW COLUMNS FROM `$tableTarget`");
    if ($columnResult) {
        while ($column = mysqli_fetch_assoc($columnResult)) {
            $existingColumns[] = $column['Field'];
        }
        mysqli_free_result($columnResult);
    }

    // Mapping field form ke kolom DB yang ada
    $dataFields = [
        'nomor_surat' => $nomor_surat,
        'tanggal_surat' => $tanggal_surat,
        'nik_jenazah' => $nik_jenazah,
        'nama_jenazah' => $nama_jenazah,
        'jenis_kelamin' => $jenis_kelamin,
        'tanggal_lahir' => $tanggal_lahir,
        'tanggal_lahir_jenazah' => $tanggal_lahir,
        'umur' => $umur,
        'tempat_lahir' => $tempat_lahir,
        'tempat_lahir_jenazah' => $tempat_lahir,
        'agama' => $agama,
        'pekerjaan' => $pekerjaan,
        'alamat_jenazah' => $alamat_jenazah,
        'desa_jenazah' => $desa_jenazah,
        'kecamatan_jenazah' => $kecamatan_jenazah,
        'kabupaten_jenazah' => $kabupaten_jenazah,
        'hari_kematian' => $hari_kematian,
        'tanggal_kematian' => $tanggal_kematian,
        'jam_kematian' => $jam_kematian,
        'tempat_kematian' => $tempat_kematian,
        'penyebab_kematian' => $penyebab_kematian,
        'nik_pelapor' => $nik_pelapor,
        'nama_pelapor' => $nama_pelapor,
        'nik_saksi1' => $nik_saksi1,
        'nama_saksi1' => $nama_saksi1,
        'nik_saksi2' => $nik_saksi2,
        'nama_saksi2' => $nama_saksi2,
    ];

    $insertColumns = [];
    $insertValues = [];
    foreach ($dataFields as $column => $value) {
        if (in_array($column, $existingColumns, true)) {
            $insertColumns[] = "`$column`";
            $insertValues[] = "'" . $value . "'";
        }
    }

    if (empty($insertColumns)) {
        echo "<div class='alert alert-danger m-4'>Error: Tidak ada kolom valid yang ditemukan untuk tabel $tableTarget.</div>";
    } else {
        $sqlInsert = "INSERT INTO `$tableTarget` (" . implode(', ', $insertColumns) . ") VALUES (" . implode(', ', $insertValues) . ")";

        if (mysqli_query($koneksi, $sqlInsert)) {
            echo "<script>alert('Data Formulir F-2.29 Berhasil Disimpan!'); window.location.href = 'index.php?page=surat-kematian';</script>";
        } else {
            echo "<div class='alert alert-danger m-4'>Error: " . mysqli_error($koneksi) . "</div>";
        }
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
        color: #dc3545;
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
            <h3 class="page-title-modern m-0">Tambah Formulir Kematian (F-2.29)</h3>
            <p class="text-muted small m-0">Input data registrasi keterangan kematian warga secara lengkap untuk berkas
                arsip kependudukan</p>
        </div>
    </div>

    <form action="" method="POST" class="needs-validation" novalidate>
        <div class="card card-modern p-4 mb-4">

            <!-- SECTION 1: REGISTRASI SURAT -->
            <div class="section-form-title" style="color: #495057;"><i class="fas fa-folder me-2"></i>1. REGISTRASI
                SURAT ADMINISTRASI</div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label form-label-modern">Nomor Surat Kematian</label>
                    <!-- Terisi Otomatis -->
                    <input type="text" class="form-control form-control-modern" name="nomor_surat"
                        value="<?= htmlspecialchars($nomor_surat_otomatis); ?>" required>
                    <small class="text-muted" style="font-size: 0.75rem;">*Terisi otomatis dari urutan data
                        terakhir.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-modern">Tanggal Surat Keluar</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_surat"
                        value="<?= date('Y-m-d'); ?>" required>
                </div>
            </div>

            <!-- SECTION 2: DATA JENAZAH -->
            <div class="section-form-title text-danger" style="border-color: #ffebee;"><i
                    class="fas fa-user-alt-slash me-2"></i>2. DATA JENAZAH</div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label form-label-modern">NIK Jenazah</label>
                    <input type="text" class="form-control form-control-modern font-monospace" name="nik_jenazah"
                        maxlength="16" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label form-label-modern">Nama Lengkap Jenazah</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_jenazah"
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Jenis Kelamin</label>
                    <select class="form-select form-control-modern" name="jenis_kelamin" required>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tempat Lahir</label>
                    <input type="text" class="form-control form-control-modern" name="tempat_lahir" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Umur (Tahun)</label>
                    <input type="number" class="form-control form-control-modern" name="umur" min="0" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Agama</label>
                    <select class="form-select form-control-modern" name="agama" required>
                        <option value="Islam">Islam</option>
                        <option value="Kristen">Kristen</option>
                        <option value="Katolik">Katolik</option>
                        <option value="Hindu">Hindu</option>
                        <option value="Buddha">Buddha</option>
                        <option value="Khonghucu">Khonghucu</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Alamat Jalan/Dukuh</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_jenazah"
                        placeholder="Rt 01 Rw 02" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" name="desa_jenazah" value="Berugenjang"
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" name="kecamatan_jenazah" value="Undaan"
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" name="kabupaten_jenazah" value="Kudus"
                        required>
                </div>
            </div>

            <!-- SECTION 3: DATA KEMATIAN -->
            <div class="section-form-title text-warning" style="border-color: #fffde7;"><i
                    class="fas fa-calendar-times me-2"></i>3. DATA KEJADIAN KEMATIAN</div>
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Hari Kematian</label>
                    <input type="text" class="form-control form-control-modern" name="hari_kematian"
                        placeholder="Senin/Selasa..." required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tanggal Meninggal</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_kematian" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pukul / Jam</label>
                    <input type="time" class="form-control form-control-modern" name="jam_kematian" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tempat Kematian</label>
                    <input type="text" class="form-control form-control-modern" name="tempat_kematian"
                        placeholder="Rumah / RS / dll" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Penyebab Kematian</label>
                    <input type="text" class="form-control form-control-modern" name="penyebab_kematian"
                        placeholder="Sakit / Tua / dll" required>
                </div>
            </div>

            <!-- SECTION 4: PELAPOR & SAKSI -->
            <div class="section-form-title text-info" style="border-color: #e0f7fa;"><i class="fas fa-users me-2"></i>4.
                PELAPOR & SAKSI PENCATATAN</div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Pelapor</label>
                    <input type="text" class="form-control form-control-modern" name="nik_pelapor" maxlength="16"
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Pelapor</label>
                    <input type="text" class="form-control form-control-modern" name="nama_pelapor" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Saksi I</label>
                    <input type="text" class="form-control form-control-modern" name="nik_saksi1" maxlength="16"
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Saksi I</label>
                    <input type="text" class="form-control form-control-modern" name="nama_saksi1" required>
                </div>
                <div class="col-md-3 offset-md-6">
                    <label class="form-label form-label-modern">NIK Saksi II</label>
                    <input type="text" class="form-control form-control-modern" name="nik_saksi2" maxlength="16"
                        required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Nama Saksi II</label>
                    <input type="text" class="form-control form-control-modern" name="nama_saksi2" required>
                </div>
            </div>

            <!-- FOOTER ACTION BUTTONS -->
            <div class="d-flex justify-content-end gap-3 mt-5 pt-3 border-top">
                <a href="index.php?page=surat-kematian" class="btn btn-secondary px-4 py-2"
                    style="border-radius: 8px;"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
                <button type="submit" name="simpan" class="btn btn-success px-4 py-2"
                    style="border-radius: 8px; background: linear-gradient(135deg, #198754, #146c43);"><i
                        class="fas fa-save me-2"></i>Simpan Formulir</button>
            </div>

        </div>
    </form>
</div>

<script>
    // Validasi Interaktif Bootstrap Browser
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