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
require_once __DIR__ . '/../../koneksi.php';
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

// Daftar kolom aktual di tabel target
$existingColumns = [];
$columnResult = mysqli_query($koneksi, "SHOW COLUMNS FROM `$tableTarget`");
if ($columnResult) {
    while ($column = mysqli_fetch_assoc($columnResult)) {
        $existingColumns[] = $column['Field'];
    }
    mysqli_free_result($columnResult);
}

// Mapping kolom database
$fieldMap = [
    'nomor_surat',
    'tanggal_surat',
    'nama_kepala_keluarga',
    'no_kk',
    // Jenazah
    'nik_jenazah',
    'nama_jenazah',
    'jenis_kelamin',
    'tanggal_lahir_jenazah',
    'umur',
    'tempat_lahir_jenazah',
    'agama_jenazah',
    'pekerjaan_jenazah',
    'alamat_jenazah',
    'desa_jenazah',
    'kecamatan_jenazah',
    'kabupaten_jenazah',
    'provinsi_jenazah',
    'anak_ke',
    // Peristiwa Kematian
    'hari_kematian',
    'tanggal_kematian',
    'jam_kematian',
    'sebab_kematian',
    'tempat_kematian',
    'penolong_kematian',
    // Ayah
    'nik_ayah',
    'nama_ayah',
    'tanggal_lahir_ayah',
    'umur_ayah',
    'pekerjaan_ayah',
    'alamat_ayah',
    'desa_ayah',
    'kecamatan_ayah',
    'kabupaten_ayah',
    'provinsi_ayah',
    // Ibu
    'nik_ibu',
    'nama_ibu',
    'tanggal_lahir_ibu',
    'umur_ibu',
    'pekerjaan_ibu',
    'alamat_ibu',
    'desa_ibu',
    'kecamatan_ibu',
    'kabupaten_ibu',
    'provinsi_ibu',
    // Pelapor
    'nik_pelapor',
    'nama_pelapor',
    'hubungan_pelapor',
    'tanggal_lahir_pelapor',
    'umur_pelapor',
    'pekerjaan_pelapor',
    'alamat_pelapor',
    'desa_pelapor',
    'kecamatan_pelapor',
    'kabupaten_pelapor',
    'provinsi_pelapor',
    // Saksi I
    'nik_saksi1',
    'nama_saksi1',
    'umur_saksi1',
    'pekerjaan_saksi1',
    'alamat_saksi1',
    'desa_saksi1',
    'kecamatan_saksi1',
    'kabupaten_saksi1',
    'provinsi_saksi1',
    // Saksi II
    'nik_saksi2',
    'nama_saksi2',
    'umur_saksi2',
    'pekerjaan_saksi2',
    'alamat_saksi2',
    'desa_saksi2',
    'kecamatan_saksi2',
    'kabupaten_saksi2',
    'provinsi_saksi2',
];

// PROSES SIMPAN DATA FORM
if (isset($_POST['simpan'])) {
    $nomor_surat_final = generateNomorSuratGlobal($koneksi, true);

    $dataFields = [];
    foreach ($fieldMap as $column) {
        if ($column === 'nomor_surat') {
            $dataFields[$column] = $nomor_surat_final;
            continue;
        }
        $dataFields[$column] = isset($_POST[$column]) ? mysqli_real_escape_string($koneksi, trim($_POST[$column])) : '';
    }

    $insertColumns = [];
    $insertValues = [];
    foreach ($dataFields as $column => $value) {
        if (in_array($column, $existingColumns, true)) {
            $insertColumns[] = "`$column`";
            $insertValues[] = ($value === '') ? 'NULL' : "'" . $value . "'";
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

<!-- Select2 CSS & Theme Bootstrap 5 -->
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

    .select2-container--bootstrap-5 .select2-selection {
        border-radius: 8px !important;
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
                    <input type="text" class="form-control form-control-modern" name="nomor_surat"
                        value="<?= htmlspecialchars($nomor_surat_otomatis); ?>" readonly>
                    <small class="text-muted" style="font-size: 0.75rem;">*Terisi otomatis (dapat diubah manual)</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-modern">Tanggal Surat Keluar</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_surat"
                        value="<?= date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-modern">Nama Kepala Keluarga</label>
                    <input type="text" class="form-control form-control-modern text-uppercase"
                        id="input_nama_kepala_keluarga" name="nama_kepala_keluarga" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label form-label-modern">Nomor Kartu Keluarga (KK)</label>
                    <input type="text" id="input_nomor_kk" name="no_kk" maxlength="16"
                        class="form-control form-control-modern" value="331904" placeholder="Contoh: 33190..." required>
                </div>
            </div>

            <!-- SECTION 2: DATA JENAZAH -->
            <div class="section-form-title text-danger" style="border-color: #ffebee;"><i
                    class="fas fa-user-alt-slash me-2"></i>2. DATA JENAZAH</div>
            <div class="row g-3">
                <div class="col-12 mb-2">
                    <label class="form-label form-label-modern text-danger"><i class="fas fa-search me-1"></i> Cari Data
                        Jenazah (Database)</label>
                    <select id="cari_jenazah" class="form-select form-control-modern"></select>
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">NIK Jenazah</label>
                    <input type="text" class="form-control form-control-modern font-monospace" id="input_nik_jenazah"
                        name="nik_jenazah" maxlength="16" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label form-label-modern">Nama Lengkap Jenazah</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" id="input_nama_jenazah"
                        name="nama_jenazah" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Jenis Kelamin</label>
                    <select class="form-select form-control-modern" id="input_jenis_kelamin_jenazah"
                        name="jenis_kelamin" required>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tempat Lahir</label>
                    <input type="text" class="form-control form-control-modern" id="input_tempat_lahir_jenazah"
                        name="tempat_lahir_jenazah" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" id="input_tanggal_lahir_jenazah"
                        name="tanggal_lahir_jenazah" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Umur (Tahun)</label>
                    <input type="number" class="form-control form-control-modern" id="input_umur_jenazah" name="umur"
                        min="0" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Anak ke</label>
                    <input type="number" class="form-control form-control-modern" name="anak_ke" min="1">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Agama</label>
                    <select class="form-select form-control-modern" id="input_agama_jenazah" name="agama_jenazah"
                        required>
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
                    <input type="text" class="form-control form-control-modern" id="input_pekerjaan_jenazah"
                        name="pekerjaan_jenazah" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Alamat Jalan/Dukuh</label>
                    <input type="text" class="form-control form-control-modern" id="input_alamat_jenazah"
                        name="alamat_jenazah" placeholder="Rt 01 Rw 02" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" id="input_desa_jenazah"
                        name="desa_jenazah" value="Berugenjang" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" id="input_kecamatan_jenazah"
                        name="kecamatan_jenazah" value="Undaan" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" id="input_kabupaten_jenazah"
                        name="kabupaten_jenazah" value="Kudus" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" id="input_provinsi_jenazah"
                        name="provinsi_jenazah" value="Jawa Tengah" required>
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
                <div class="col-md-2">
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
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Sebab Kematian</label>
                    <input type="text" class="form-control form-control-modern" name="sebab_kematian"
                        placeholder="Sakit biasa/tua, wabah, kecelakaan, dll" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Yang Menerangkan</label>
                    <select class="form-select form-control-modern" name="penolong_kematian" required>
                        <option value="Dokter">Dokter</option>
                        <option value="Tenaga Kesehatan">Tenaga Kesehatan</option>
                        <option value="Kepolisian">Kepolisian</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
            </div>

            <!-- SECTION 4: DATA AYAH -->
            <div class="section-form-title" style="color:#6f42c1; border-color:#f1e9fb;"><i
                    class="fas fa-male me-2"></i>4. DATA AYAH</div>
            <div class="row g-3">
                <div class="col-12 mb-2">
                    <label class="form-label form-label-modern" style="color:#6f42c1;"><i
                            class="fas fa-search me-1"></i> Cari Data Ayah (Database)</label>
                    <select id="cari_ayah" class="form-select form-control-modern"></select>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Ayah</label>
                    <input type="text" class="form-control form-control-modern font-monospace" id="input_nik_ayah"
                        name="nik_ayah" maxlength="16">
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Ayah</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" id="input_nama_ayah"
                        name="nama_ayah">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" id="input_tanggal_lahir_ayah"
                        name="tanggal_lahir_ayah">
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur</label>
                    <input type="number" class="form-control form-control-modern" id="input_umur_ayah" name="umur_ayah"
                        min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" id="input_pekerjaan_ayah"
                        name="pekerjaan_ayah">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Alamat</label>
                    <input type="text" class="form-control form-control-modern" id="input_alamat_ayah"
                        name="alamat_ayah" value="Berugenjang">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" id="input_desa_ayah" name="desa_ayah"
                        value="Berugenjang">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" id="input_kecamatan_ayah"
                        name="kecamatan_ayah" value="Undaan">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" id="input_kabupaten_ayah"
                        name="kabupaten_ayah" value="Kudus">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" id="input_provinsi_ayah"
                        name="provinsi_ayah" value="Jawa Tengah">
                </div>
            </div>

            <!-- SECTION 5: DATA IBU -->
            <div class="section-form-title" style="color:#d63384; border-color:#fbe9f1;"><i
                    class="fas fa-female me-2"></i>5. DATA IBU</div>
            <div class="row g-3">
                <div class="col-12 mb-2">
                    <label class="form-label form-label-modern" style="color:#d63384;"><i
                            class="fas fa-search me-1"></i> Cari Data Ibu (Database)</label>
                    <select id="cari_ibu" class="form-select form-control-modern"></select>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Ibu</label>
                    <input type="text" class="form-control form-control-modern font-monospace" id="input_nik_ibu"
                        name="nik_ibu" maxlength="16">
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Ibu</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" id="input_nama_ibu"
                        name="nama_ibu">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" id="input_tanggal_lahir_ibu"
                        name="tanggal_lahir_ibu">
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur</label>
                    <input type="number" class="form-control form-control-modern" id="input_umur_ibu" name="umur_ibu"
                        min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" id="input_pekerjaan_ibu"
                        name="pekerjaan_ibu">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Alamat</label>
                    <input type="text" class="form-control form-control-modern" id="input_alamat_ibu" name="alamat_ibu"
                        value="Berugenjang">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" id="input_desa_ibu" name="desa_ibu"
                        value="Berugenjang">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" id="input_kecamatan_ibu"
                        name="kecamatan_ibu" value="Undaan">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" id="input_kabupaten_ibu"
                        name="kabupaten_ibu" value="Kudus">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" id="input_provinsi_ibu"
                        name="provinsi_ibu" value="Jawa Tengah">
                </div>
            </div>

            <!-- SECTION 6: PELAPOR -->
            <div class="section-form-title text-info" style="border-color: #e0f7fa;"><i
                    class="fas fa-user-check me-2"></i>6. DATA PELAPOR</div>
            <div class="row g-3">
                <div class="col-12 mb-2">
                    <label class="form-label form-label-modern text-info"><i class="fas fa-search me-1"></i> Cari Data
                        Pelapor (Database)</label>
                    <select id="cari_pelapor" class="form-select form-control-modern"></select>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Pelapor</label>
                    <input type="text" class="form-control form-control-modern font-monospace" id="input_nik_pelapor"
                        name="nik_pelapor" maxlength="16" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Pelapor</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" id="input_nama_pelapor"
                        name="nama_pelapor" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" id="input_tanggal_lahir_pelapor"
                        name="tanggal_lahir_pelapor">
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur</label>
                    <input type="number" class="form-control form-control-modern" id="input_umur_pelapor"
                        name="umur_pelapor" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Hubungan</label>
                    <input type="text" class="form-control form-control-modern" name="hubungan_pelapor"
                        placeholder="Anak/Suami/Istri/dll" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" id="input_pekerjaan_pelapor"
                        name="pekerjaan_pelapor">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Alamat</label>
                    <input type="text" class="form-control form-control-modern" id="input_alamat_pelapor"
                        name="alamat_pelapor" value="Berugenjang">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" id="input_desa_pelapor"
                        name="desa_pelapor" value="Berugenjang">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" id="input_kecamatan_pelapor"
                        name="kecamatan_pelapor" value="Undaan">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" id="input_kabupaten_pelapor"
                        name="kabupaten_pelapor" value="Kudus">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" id="input_provinsi_pelapor"
                        name="provinsi_pelapor" value="Jawa Tengah">
                </div>
            </div>

            <!-- SECTION 7: SAKSI -->
            <div class="section-form-title" style="color:#20c997; border-color:#e6fbf5;"><i
                    class="fas fa-users me-2"></i>7. SAKSI PENCATATAN</div>
            <div class="row g-3">
                <!-- Saksi 1 -->
                <div class="col-12 fw-bold text-secondary" style="font-size:0.85rem;">Saksi I</div>
                <div class="col-12 mb-2">
                    <label class="form-label form-label-modern text-success"><i class="fas fa-search me-1"></i> Cari
                        Data Saksi I (Database)</label>
                    <select id="cari_saksi1" class="form-select form-control-modern"></select>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Saksi I</label>
                    <input type="text" class="form-control form-control-modern font-monospace" id="input_nik_saksi1"
                        name="nik_saksi1" maxlength="16" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Saksi I</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" id="input_nama_saksi1"
                        name="nama_saksi1" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur</label>
                    <input type="number" class="form-control form-control-modern" id="input_umur_saksi1"
                        name="umur_saksi1" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" id="input_pekerjaan_saksi1"
                        name="pekerjaan_saksi1">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Alamat</label>
                    <input type="text" class="form-control form-control-modern" id="input_alamat_saksi1"
                        name="alamat_saksi1" value="Berugenjang">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" id="input_desa_saksi1"
                        name="desa_saksi1" value="Berugenjang">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" id="input_kecamatan_saksi1"
                        name="kecamatan_saksi1" value="Undaan">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" id="input_kabupaten_saksi1"
                        name="kabupaten_saksi1" value="Kudus">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" id="input_provinsi_saksi1"
                        name="provinsi_saksi1" value="Jawa Tengah">
                </div>

                <!-- Saksi 2 -->
                <div class="col-12 fw-bold text-secondary mt-3" style="font-size:0.85rem;">Saksi II</div>
                <div class="col-12 mb-2">
                    <label class="form-label form-label-modern text-success"><i class="fas fa-search me-1"></i> Cari
                        Data Saksi II (Database)</label>
                    <select id="cari_saksi2" class="form-select form-control-modern"></select>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Saksi II</label>
                    <input type="text" class="form-control form-control-modern font-monospace" id="input_nik_saksi2"
                        name="nik_saksi2" maxlength="16" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Saksi II</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" id="input_nama_saksi2"
                        name="nama_saksi2" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur</label>
                    <input type="number" class="form-control form-control-modern" id="input_umur_saksi2"
                        name="umur_saksi2" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" id="input_pekerjaan_saksi2"
                        name="pekerjaan_saksi2">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Alamat</label>
                    <input type="text" class="form-control form-control-modern" id="input_alamat_saksi2"
                        name="alamat_saksi2" value="Berugenjang">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" id="input_desa_saksi2"
                        name="desa_saksi2" value="Berugenjang">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" id="input_kecamatan_saksi2"
                        name="kecamatan_saksi2" value="Undaan">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" id="input_kabupaten_saksi2"
                        name="kabupaten_saksi2" value="Kudus">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" id="input_provinsi_saksi2"
                        name="provinsi_saksi2" value="Jawa Tengah">
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

<!-- Library jQuery & Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    // Helper 1: Format Tanggal ke YYYY-MM-DD
    function formatDateForInput(dateString) {
        if (!dateString) return '';
        if (typeof dateString === 'string' && dateString.includes(' ')) {
            dateString = dateString.split(' ')[0];
        }
        var d = new Date(dateString);
        if (isNaN(d.getTime()) && typeof dateString === 'string') {
            var parts = dateString.split(/[\/\-]/);
            if (parts.length === 3 && parts[0].length === 2 && parts[2].length === 4) {
                return parts[2] + '-' + parts[1].padStart(2, '0') + '-' + parts[0].padStart(2, '0');
            }
            return '';
        }
        var month = '' + (d.getMonth() + 1);
        var day = '' + d.getDate();
        var year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }

    // Helper 1b: Parse tempat lahir dari tempat_tgl_lahir gabungan
    function parsePlaceFromCombined(value) {
        if (!value) return '';
        var parts = value.split(',');
        return parts.length > 0 ? parts[0].trim() : '';
    }

    // Helper 2: Hitung Umur Berdasarkan Tanggal Lahir
    function hitungUmur(dateString) {
        var formattedDate = formatDateForInput(dateString);
        if (!formattedDate) return '';

        var today = new Date();
        var birthDate = new Date(formattedDate);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();

        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age >= 0 ? age : '';
    }

    $(document).ready(function () {

        // Konfigurasi Ajax Select2
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

        // Generic Populate Handler
        function autofillPersonData(d, prefix) {
            $('#input_nik_' + prefix).val(d.nik || d.id || '');
            $('#input_nama_' + prefix).val(d.nama || '');

            var rawTgl = d.tgl_lahir || d.tanggal_lahir || '';
            var formattedTgl = formatDateForInput(rawTgl);

            if (formattedTgl) {
                $('#input_tanggal_lahir_' + prefix).val(formattedTgl);
                $('#input_umur_' + prefix).val(hitungUmur(formattedTgl));
            } else if (d.umur) {
                $('#input_umur_' + prefix).val(d.umur);
            }

            if (d.pekerjaan) $('#input_pekerjaan_' + prefix).val(d.pekerjaan);
            if (d.alamat_tinggal || d.alamat_jalan || d.alamat_lengkap || d.alamat) {
                $('#input_alamat_' + prefix).val(d.alamat_tinggal || d.alamat_jalan || d.alamat_lengkap || d
                    .alamat);
            }
            if (d.desa) $('#input_desa_' + prefix).val(d.desa);
            if (d.kecamatan) $('#input_kecamatan_' + prefix).val(d.kecamatan);
            if (d.kabupaten) $('#input_kabupaten_' + prefix).val(d.kabupaten);
            if (d.provinsi) $('#input_provinsi_' + prefix).val(d.provinsi);
        }

        // 1. CARI JENAZAH
        $('#cari_jenazah').select2(buildSelect2Config('-- Cari NIK / Nama Jenazah --'));
        $('#cari_jenazah').on('select2:select', function (e) {
            var d = e.params.data;
            autofillPersonData(d, 'jenazah');
            if (d.no_kk || d.nokk) $('#input_nomor_kk').val(d.no_kk || d.nokk);
            if (d.jenis_kelamin) {
                var jk = d.jenis_kelamin.toString().toLowerCase();
                if (jk.includes('l')) {
                    $('#input_jenis_kelamin_jenazah').val('Laki-laki');
                } else if (jk.includes('p')) {
                    $('#input_jenis_kelamin_jenazah').val('Perempuan');
                }
            }
            var place = d.tempat_lahir || parsePlaceFromCombined(d.tempat_tgl_lahir) || '';
            if (place) $('#input_tempat_lahir_jenazah').val(place);
            if (d.agama) $('#input_agama_jenazah').val(d.agama);
        });
        $('#cari_jenazah').on('select2:clear', function () {
            $('#input_nik_jenazah, #input_nama_jenazah, #input_tempat_lahir_jenazah, #input_tanggal_lahir_jenazah, #input_umur_jenazah, #input_pekerjaan_jenazah, #input_alamat_jenazah')
                .val('');
        });

        // 2. CARI AYAH
        $('#cari_ayah').select2(buildSelect2Config('-- Cari NIK / Nama Ayah --'));
        $('#cari_ayah').on('select2:select', function (e) {
            var d = e.params.data;
            autofillPersonData(d, 'ayah');
            if (!$('#input_nama_kepala_keluarga').val()) {
                $('#input_nama_kepala_keluarga').val(d.nama || '');
            }
            if (d.no_kk || d.nokk) $('#input_nomor_kk').val(d.no_kk || d.nokk);
        });
        $('#cari_ayah').on('select2:clear', function () {
            $('#input_nik_ayah, #input_nama_ayah, #input_tanggal_lahir_ayah, #input_umur_ayah, #input_pekerjaan_ayah, #input_alamat_ayah')
                .val('');
        });

        // 3. CARI IBU
        $('#cari_ibu').select2(buildSelect2Config('-- Cari NIK / Nama Ibu --'));
        $('#cari_ibu').on('select2:select', function (e) {
            var d = e.params.data;
            autofillPersonData(d, 'ibu');
            if (d.no_kk || d.nokk) $('#input_nomor_kk').val(d.no_kk || d.nokk);
        });
        $('#cari_ibu').on('select2:clear', function () {
            $('#input_nik_ibu, #input_nama_ibu, #input_tanggal_lahir_ibu, #input_umur_ibu, #input_pekerjaan_ibu, #input_alamat_ibu')
                .val('');
        });

        // 4. CARI PELAPOR
        $('#cari_pelapor').select2(buildSelect2Config('-- Cari NIK / Nama Pelapor --'));
        $('#cari_pelapor').on('select2:select', function (e) {
            var d = e.params.data;
            autofillPersonData(d, 'pelapor');
        });
        $('#cari_pelapor').on('select2:clear', function () {
            $('#input_nik_pelapor, #input_nama_pelapor, #input_tanggal_lahir_pelapor, #input_umur_pelapor, #input_pekerjaan_pelapor, #input_alamat_pelapor')
                .val('');
        });

        // 5. CARI SAKSI 1
        $('#cari_saksi1').select2(buildSelect2Config('-- Cari NIK / Nama Saksi I --'));
        $('#cari_saksi1').on('select2:select', function (e) {
            var d = e.params.data;
            autofillPersonData(d, 'saksi1');
        });
        $('#cari_saksi1').on('select2:clear', function () {
            $('#input_nik_saksi1, #input_nama_saksi1, #input_umur_saksi1, #input_pekerjaan_saksi1, #input_alamat_saksi1')
                .val('');
        });

        // 6. CARI SAKSI 2
        $('#cari_saksi2').select2(buildSelect2Config('-- Cari NIK / Nama Saksi II --'));
        $('#cari_saksi2').on('select2:select', function (e) {
            var d = e.params.data;
            autofillPersonData(d, 'saksi2');
        });
        $('#cari_saksi2').on('select2:clear', function () {
            $('#input_nik_saksi2, #input_nama_saksi2, #input_umur_saksi2, #input_pekerjaan_saksi2, #input_alamat_saksi2')
                .val('');
        });

        // Hitung Umur Real-time jika tanggal diubah manual
        $('#input_tanggal_lahir_jenazah').on('change keyup', function () {
            $('#input_umur_jenazah').val(hitungUmur($(this).val()));
        });
        $('#input_tanggal_lahir_ayah').on('change keyup', function () {
            $('#input_umur_ayah').val(hitungUmur($(this).val()));
        });
        $('#input_tanggal_lahir_ibu').on('change keyup', function () {
            $('#input_umur_ibu').val(hitungUmur($(this).val()));
        });
        $('#input_tanggal_lahir_pelapor').on('change keyup', function () {
            $('#input_umur_pelapor').val(hitungUmur($(this).val()));
        });

    });

    // Validasi Form Bootstrap
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