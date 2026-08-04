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

// Daftar kolom aktual di tabel target, dipakai untuk memastikan
// hanya kolom yang benar-benar ada di database yang di-insert.
$existingColumns = [];
$columnResult = mysqli_query($koneksi, "SHOW COLUMNS FROM `$tableTarget`");
if ($columnResult) {
    while ($column = mysqli_fetch_assoc($columnResult)) {
        $existingColumns[] = $column['Field'];
    }
    mysqli_free_result($columnResult);
}

// PENTING: key array di bawah ini HARUS SAMA PERSIS dengan nama kolom di
// database (lihat tb_surat_kematian.sql / migrasi_surat_kematian.sql).
// Ini yang membuat data tidak lagi hilang saat disimpan, diedit, atau dicetak.
$fieldMap = [
    'nomor_surat', 'tanggal_surat',
    'nama_kepala_keluarga', 'no_kk',
    // Jenazah
    'nik_jenazah', 'nama_jenazah', 'jenis_kelamin',
    'tanggal_lahir_jenazah', 'umur', 'tempat_lahir_jenazah',
    'agama_jenazah', 'pekerjaan_jenazah',
    'alamat_jenazah', 'desa_jenazah', 'kecamatan_jenazah', 'kabupaten_jenazah', 'provinsi_jenazah',
    'anak_ke',
    // Peristiwa Kematian
    'hari_kematian', 'tanggal_kematian', 'jam_kematian',
    'sebab_kematian', 'tempat_kematian', 'penolong_kematian',
    // Ayah
    'nik_ayah', 'nama_ayah', 'tanggal_lahir_ayah', 'umur_ayah', 'pekerjaan_ayah',
    'alamat_ayah', 'desa_ayah', 'kecamatan_ayah', 'kabupaten_ayah', 'provinsi_ayah',
    // Ibu
    'nik_ibu', 'nama_ibu', 'tanggal_lahir_ibu', 'umur_ibu', 'pekerjaan_ibu',
    'alamat_ibu', 'desa_ibu', 'kecamatan_ibu', 'kabupaten_ibu', 'provinsi_ibu',
    // Pelapor
    'nik_pelapor', 'nama_pelapor', 'hubungan_pelapor',
    'tanggal_lahir_pelapor', 'umur_pelapor', 'pekerjaan_pelapor',
    'alamat_pelapor', 'desa_pelapor', 'kecamatan_pelapor', 'kabupaten_pelapor', 'provinsi_pelapor',
    // Saksi I
    'nik_saksi1', 'nama_saksi1', 'umur_saksi1', 'pekerjaan_saksi1',
    'alamat_saksi1', 'desa_saksi1', 'kecamatan_saksi1', 'kabupaten_saksi1', 'provinsi_saksi1',
    // Saksi II
    'nik_saksi2', 'nama_saksi2', 'umur_saksi2', 'pekerjaan_saksi2',
    'alamat_saksi2', 'desa_saksi2', 'kecamatan_saksi2', 'kabupaten_saksi2', 'provinsi_saksi2',
];

// PROSES SIMPAN DATA FORM
if (isset($_POST['simpan'])) {
    // Reservasi nomor surat definitif di sini (saat benar-benar disimpan),
    // bukan saat halaman form dibuka, agar nomor tidak bertambah saat batal/reload.
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
                        value="<?= htmlspecialchars($nomor_surat_otomatis); ?>" readonly>
                    <small class="text-muted" style="font-size: 0.75rem;">*Terisi otomatis dari urutan data
                        terakhir.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-modern">Tanggal Surat Keluar</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_surat"
                        value="<?= date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-modern">Nama Kepala Keluarga</label>
                    <input type="text" class="form-control form-control-modern text-uppercase"
                        name="nama_kepala_keluarga" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nomor Kartu Keluarga (KK)</label>
                    <!-- PERBAIKAN: name="nomor_kk" -->
                    <input type="text" name="nomor_kk" maxlength="16" class="form-control" value="331904"
                        placeholder="Contoh: 33190..." required>
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
                    <input type="text" class="form-control form-control-modern" name="tempat_lahir_jenazah" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_jenazah" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Umur (Tahun)</label>
                    <input type="number" class="form-control form-control-modern" name="umur" min="0" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Anak ke</label>
                    <input type="number" class="form-control form-control-modern" name="anak_ke" min="1">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Agama</label>
                    <select class="form-select form-control-modern" name="agama_jenazah" required>
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
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_jenazah" required>
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
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" name="provinsi_jenazah"
                        value="Jawa Tengah" required>
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
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Ayah</label>
                    <input type="text" class="form-control form-control-modern font-monospace" name="nik_ayah"
                        maxlength="16">
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Ayah</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_ayah">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_ayah">
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur</label>
                    <input type="number" class="form-control form-control-modern" name="umur_ayah" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_ayah">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Alamat</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_ayah" value="Berugenjang">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" name="desa_ayah" value="Berugenjang">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" name="kecamatan_ayah" value="Undaan">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" name="kabupaten_ayah" value="Kudus">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" name="provinsi_ayah"
                        value="Jawa Tengah">
                </div>
            </div>

            <!-- SECTION 5: DATA IBU -->
            <div class="section-form-title" style="color:#d63384; border-color:#fbe9f1;"><i
                    class="fas fa-female me-2"></i>5. DATA IBU</div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Ibu</label>
                    <input type="text" class="form-control form-control-modern font-monospace" name="nik_ibu"
                        maxlength="16">
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Ibu</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_ibu">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_ibu">
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur</label>
                    <input type="number" class="form-control form-control-modern" name="umur_ibu" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_ibu">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Alamat</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_ibu" value="Berugenjang">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" name="desa_ibu" value="Berugenjang">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" name="kecamatan_ibu" value="Undaan">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" name="kabupaten_ibu" value="Kudus">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" name="provinsi_ibu" value="Jawa Tengah">
                </div>
            </div>

            <!-- SECTION 6: PELAPOR -->
            <div class="section-form-title text-info" style="border-color: #e0f7fa;"><i
                    class="fas fa-user-check me-2"></i>6.
                DATA PELAPOR</div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Pelapor</label>
                    <input type="text" class="form-control form-control-modern font-monospace" name="nik_pelapor"
                        maxlength="16" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Pelapor</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_pelapor"
                        required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_pelapor">
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur</label>
                    <input type="number" class="form-control form-control-modern" name="umur_pelapor" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Hubungan</label>
                    <input type="text" class="form-control form-control-modern" name="hubungan_pelapor"
                        placeholder="Anak/Suami/Istri/dll" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_pelapor">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Alamat</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_pelapor"
                        value="Berugenjang">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" name="desa_pelapor" value="Berugenjang">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" name="kecamatan_pelapor" value="Undaan">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" name="kabupaten_pelapor" value="Kudus">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" name="provinsi_pelapor"
                        value="Jawa Tengah">
                </div>
            </div>

            <!-- SECTION 7: SAKSI -->
            <div class="section-form-title" style="color:#20c997; border-color:#e6fbf5;"><i
                    class="fas fa-users me-2"></i>7.
                SAKSI PENCATATAN</div>
            <div class="row g-3">
                <div class="col-12 fw-bold text-secondary" style="font-size:0.85rem;">Saksi I</div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Saksi I</label>
                    <input type="text" class="form-control form-control-modern font-monospace" name="nik_saksi1"
                        maxlength="16" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Saksi I</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_saksi1"
                        required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur</label>
                    <input type="number" class="form-control form-control-modern" name="umur_saksi1" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_saksi1">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Alamat</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_saksi1"
                        value="Berugenjang">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" name="desa_saksi1" value="Berugenjang">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" name="kecamatan_saksi1" value="Undaan">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" name="kabupaten_saksi1" value="Kudus">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" name="provinsi_saksi1"
                        value="Jawa Tengah">
                </div>

                <div class="col-12 fw-bold text-secondary mt-2" style="font-size:0.85rem;">Saksi II</div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Saksi II</label>
                    <input type="text" class="form-control form-control-modern font-monospace" name="nik_saksi2"
                        maxlength="16" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Saksi II</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_saksi2"
                        required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur</label>
                    <input type="number" class="form-control form-control-modern" name="umur_saksi2" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_saksi2">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Alamat</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_saksi2"
                        value="Berugenjang">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" name="desa_saksi2" value="Berugenjang">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" name="kecamatan_saksi2" value="Undaan">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" name="kabupaten_saksi2" value="Kudus">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" name="provinsi_saksi2"
                        value="Jawa Tengah">
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