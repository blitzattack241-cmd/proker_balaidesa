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

// Target nama tabel dinamis (Sesuai skrip tambah data Anda)
$tableTarget = 'tb_surat_kematian';
$checkTable = mysqli_query($koneksi, "SHOW TABLES LIKE 'surat_kematian'");
if ($checkTable && mysqli_num_rows($checkTable) > 0) {
    $tableTarget = 'surat_kematian';
}

function getFormValue(array $data, string $key): string
{
    return array_key_exists($key, $data) && $data[$key] !== null ? (string) $data[$key] : '';
}

// Ambil daftar kolom aktual di tabel target (Untuk keperluan UPDATE nanti)
$existingColumns = [];
$columnResult = mysqli_query($koneksi, "SHOW COLUMNS FROM `$tableTarget`");
if ($columnResult) {
    while ($column = mysqli_fetch_assoc($columnResult)) {
        $existingColumns[] = $column['Field'];
    }
    mysqli_free_result($columnResult);
}

// PENTING: daftar field ini HARUS SAMA PERSIS dengan nama kolom di database
// (lihat tb_surat_kematian.sql / migrasi_surat_kematian.sql).
$fieldMap = [
    'nomor_surat',
    'tanggal_surat',
    'nama_kepala_keluarga',
    'no_kk',
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
    'hari_kematian',
    'tanggal_kematian',
    'jam_kematian',
    'sebab_kematian',
    'tempat_kematian',
    'penolong_kematian',
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
    'nik_saksi1',
    'nama_saksi1',
    'umur_saksi1',
    'pekerjaan_saksi1',
    'alamat_saksi1',
    'desa_saksi1',
    'kecamatan_saksi1',
    'kabupaten_saksi1',
    'provinsi_saksi1',
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

// ========================================================
// 1. AMBIL DATA LAMA BERDASARKAN ID
// ========================================================
$data = []; // Inisialisasi array penampung data
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $id_surat = intval($_GET['id']);

    // PENTING: Jika di database nama kolom Primary Key Anda BUKAN id_surat (misal: id), silakan sesuaikan bagian 'id_surat =' dibawah ini
    $queryGet = mysqli_query($koneksi, "SELECT * FROM `$tableTarget` WHERE id_surat = $id_surat");

    if ($queryGet && mysqli_num_rows($queryGet) > 0) {
        $data = mysqli_fetch_assoc($queryGet);
    } else {
        echo "<script>alert('Data tidak ditemukan di tabel $tableTarget!'); window.location.href = 'index.php?page=surat-kematian';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID Data tidak valid!'); window.location.href = 'index.php?page=surat-kematian';</script>";
    exit;
}

// ========================================================
// 2. PROSES UPDATE DATA KETIKA FORM DISUBMIT
// ========================================================
if (isset($_POST['update'])) {
    $updateParts = [];
    foreach ($fieldMap as $column) {
        if (!in_array($column, $existingColumns, true)) {
            continue;
        }
        $value = isset($_POST[$column]) ? mysqli_real_escape_string($koneksi, trim($_POST[$column])) : '';
        $updateParts[] = "`$column` = " . ($value === '' ? 'NULL' : "'" . $value . "'");
    }

    if (empty($updateParts)) {
        echo "<div class='alert alert-danger m-4'>Error: Tidak ada kolom valid yang dapat diperbarui.</div>";
    } else {
        $sqlUpdate = "UPDATE `$tableTarget` SET " . implode(', ', $updateParts) . " WHERE id_surat = $id_surat";
        if (mysqli_query($koneksi, $sqlUpdate)) {
            echo "<script>alert('Data Formulir F-2.29 Berhasil Diperbarui!'); window.location.href = 'index.php?page=surat-kematian';</script>";
        } else {
            echo "<div class='alert alert-danger m-4'>Error: " . mysqli_error($koneksi) . "</div>";
        }
    }

    // Refresh data tampilan setelah update supaya form menampilkan nilai terbaru
    $queryGet = mysqli_query($koneksi, "SELECT * FROM `$tableTarget` WHERE id_surat = $id_surat");
    if ($queryGet && mysqli_num_rows($queryGet) > 0) {
        $data = mysqli_fetch_assoc($queryGet);
    }
}

$jenisKelaminValue = strtolower(getFormValue($data, 'jenis_kelamin'));
$agamaValue = getFormValue($data, 'agama_jenazah');
$penolongValue = getFormValue($data, 'penolong_kematian');
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
            <h3 class="page-title-modern m-0">Edit Formulir Kematian (F-2.29)</h3>
            <p class="text-muted small m-0">Perbarui data registrasi keterangan kematian warga secara lengkap untuk
                arsip berkas</p>
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
                        value="<?= htmlspecialchars(getFormValue($data, 'nomor_surat')); ?>" placeholder="474.3/..."
                        required>
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-modern">Tanggal Surat Keluar</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_surat"
                        value="<?= htmlspecialchars(getFormValue($data, 'tanggal_surat')); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-modern">Nama Kepala Keluarga</label>
                    <input type="text" class="form-control form-control-modern text-uppercase"
                        name="nama_kepala_keluarga"
                        value="<?= htmlspecialchars(getFormValue($data, 'nama_kepala_keluarga')); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label form-label-modern">Nomor Kartu Keluarga (KK)</label>
                    <input type="text" class="form-control form-control-modern font-monospace" name="no_kk"
                        value="<?= htmlspecialchars(getFormValue($data, 'no_kk')); ?>" maxlength="20" required>
                </div>
            </div>

            <!-- SECTION 2: DATA JENAZAH -->
            <div class="section-form-title text-danger" style="border-color: #ffebee;"><i
                    class="fas fa-user-alt-slash me-2"></i>2. DATA JENAZAH</div>
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label form-label-modern">NIK Jenazah</label>
                    <input type="text" class="form-control form-control-modern font-monospace" name="nik_jenazah"
                        value="<?= htmlspecialchars(getFormValue($data, 'nik_jenazah')); ?>" maxlength="16" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label form-label-modern">Nama Lengkap Jenazah</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_jenazah"
                        value="<?= htmlspecialchars(getFormValue($data, 'nama_jenazah')); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Jenis Kelamin</label>
                    <select class="form-select form-control-modern" name="jenis_kelamin" required>
                        <option value="Laki-laki" <?= ($jenisKelaminValue === 'laki-laki') ? 'selected' : ''; ?>>
                            Laki-laki</option>
                        <option value="Perempuan" <?= ($jenisKelaminValue === 'perempuan') ? 'selected' : ''; ?>>
                            Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tempat Lahir</label>
                    <input type="text" class="form-control form-control-modern" name="tempat_lahir_jenazah"
                        value="<?= htmlspecialchars(getFormValue($data, 'tempat_lahir_jenazah')); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_jenazah"
                        value="<?= htmlspecialchars(getFormValue($data, 'tanggal_lahir_jenazah')); ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Umur (Tahun)</label>
                    <input type="number" class="form-control form-control-modern" name="umur"
                        value="<?= htmlspecialchars(getFormValue($data, 'umur')); ?>" min="0" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Anak ke</label>
                    <input type="number" class="form-control form-control-modern" name="anak_ke"
                        value="<?= htmlspecialchars(getFormValue($data, 'anak_ke')); ?>" min="1">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Agama</label>
                    <select class="form-select form-control-modern" name="agama_jenazah" required>
                        <?php
                        $agama_list = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'];
                        foreach ($agama_list as $ag) {
                            $selected = (strcasecmp($agamaValue, $ag) == 0) ? 'selected' : '';
                            echo "<option value='$ag' $selected>$ag</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_jenazah"
                        value="<?= htmlspecialchars(getFormValue($data, 'pekerjaan_jenazah')); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Alamat Jalan/Dukuh</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_jenazah"
                        value="<?= htmlspecialchars(getFormValue($data, 'alamat_jenazah')); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" name="desa_jenazah"
                        value="<?= htmlspecialchars(getFormValue($data, 'desa_jenazah')); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" name="kecamatan_jenazah"
                        value="<?= htmlspecialchars(getFormValue($data, 'kecamatan_jenazah')); ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" name="kabupaten_jenazah"
                        value="<?= htmlspecialchars(getFormValue($data, 'kabupaten_jenazah')); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" name="provinsi_jenazah"
                        value="<?= htmlspecialchars(getFormValue($data, 'provinsi_jenazah') ?: 'Jawa Tengah'); ?>"
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
                        value="<?= htmlspecialchars(getFormValue($data, 'hari_kematian')); ?>"
                        placeholder="Senin/Selasa..." required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tanggal Meninggal</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_kematian"
                        value="<?= htmlspecialchars(getFormValue($data, 'tanggal_kematian')); ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pukul / Jam</label>
                    <input type="time" class="form-control form-control-modern" name="jam_kematian"
                        value="<?= htmlspecialchars(getFormValue($data, 'jam_kematian')); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Tempat Kematian</label>
                    <input type="text" class="form-control form-control-modern" name="tempat_kematian"
                        value="<?= htmlspecialchars(getFormValue($data, 'tempat_kematian')); ?>"
                        placeholder="Rumah / RS / dll" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Sebab Kematian</label>
                    <input type="text" class="form-control form-control-modern" name="sebab_kematian"
                        value="<?= htmlspecialchars(getFormValue($data, 'sebab_kematian')); ?>"
                        placeholder="Sakit biasa/tua, wabah, kecelakaan, dll" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Yang Menerangkan</label>
                    <select class="form-select form-control-modern" name="penolong_kematian" required>
                        <?php
                        $penolong_list = ['Dokter', 'Tenaga Kesehatan', 'Kepolisian', 'Lainnya'];
                        foreach ($penolong_list as $pn) {
                            $selected = (strcasecmp($penolongValue, $pn) == 0) ? 'selected' : '';
                            echo "<option value='$pn' $selected>$pn</option>";
                        }
                        ?>
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
                        value="<?= htmlspecialchars(getFormValue($data, 'nik_ayah')); ?>" maxlength="16">
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Ayah</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_ayah"
                        value="<?= htmlspecialchars(getFormValue($data, 'nama_ayah')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_ayah"
                        value="<?= htmlspecialchars(getFormValue($data, 'tanggal_lahir_ayah')); ?>">
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur</label>
                    <input type="number" class="form-control form-control-modern" name="umur_ayah"
                        value="<?= htmlspecialchars(getFormValue($data, 'umur_ayah')); ?>" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_ayah"
                        value="<?= htmlspecialchars(getFormValue($data, 'pekerjaan_ayah')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Alamat</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_ayah"
                        value="<?= htmlspecialchars(getFormValue($data, 'alamat_ayah')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" name="desa_ayah"
                        value="<?= htmlspecialchars(getFormValue($data, 'desa_ayah')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" name="kecamatan_ayah"
                        value="<?= htmlspecialchars(getFormValue($data, 'kecamatan_ayah')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" name="kabupaten_ayah"
                        value="<?= htmlspecialchars(getFormValue($data, 'kabupaten_ayah')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" name="provinsi_ayah"
                        value="<?= htmlspecialchars(getFormValue($data, 'provinsi_ayah')); ?>">
                </div>
            </div>

            <!-- SECTION 5: DATA IBU -->
            <div class="section-form-title" style="color:#d63384; border-color:#fbe9f1;"><i
                    class="fas fa-female me-2"></i>5. DATA IBU</div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label form-label-modern">NIK Ibu</label>
                    <input type="text" class="form-control form-control-modern font-monospace" name="nik_ibu"
                        value="<?= htmlspecialchars(getFormValue($data, 'nik_ibu')); ?>" maxlength="16">
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Ibu</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_ibu"
                        value="<?= htmlspecialchars(getFormValue($data, 'nama_ibu')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_ibu"
                        value="<?= htmlspecialchars(getFormValue($data, 'tanggal_lahir_ibu')); ?>">
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur</label>
                    <input type="number" class="form-control form-control-modern" name="umur_ibu"
                        value="<?= htmlspecialchars(getFormValue($data, 'umur_ibu')); ?>" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_ibu"
                        value="<?= htmlspecialchars(getFormValue($data, 'pekerjaan_ibu')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Alamat</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_ibu"
                        value="<?= htmlspecialchars(getFormValue($data, 'alamat_ibu')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" name="desa_ibu"
                        value="<?= htmlspecialchars(getFormValue($data, 'desa_ibu')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" name="kecamatan_ibu"
                        value="<?= htmlspecialchars(getFormValue($data, 'kecamatan_ibu')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" name="kabupaten_ibu"
                        value="<?= htmlspecialchars(getFormValue($data, 'kabupaten_ibu')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" name="provinsi_ibu"
                        value="<?= htmlspecialchars(getFormValue($data, 'provinsi_ibu')); ?>">
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
                        value="<?= htmlspecialchars(getFormValue($data, 'nik_pelapor')); ?>" maxlength="16" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Pelapor</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_pelapor"
                        value="<?= htmlspecialchars(getFormValue($data, 'nama_pelapor')); ?>" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Tanggal Lahir</label>
                    <input type="date" class="form-control form-control-modern" name="tanggal_lahir_pelapor"
                        value="<?= htmlspecialchars(getFormValue($data, 'tanggal_lahir_pelapor')); ?>">
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur</label>
                    <input type="number" class="form-control form-control-modern" name="umur_pelapor"
                        value="<?= htmlspecialchars(getFormValue($data, 'umur_pelapor')); ?>" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Hubungan</label>
                    <input type="text" class="form-control form-control-modern" name="hubungan_pelapor"
                        value="<?= htmlspecialchars(getFormValue($data, 'hubungan_pelapor')); ?>"
                        placeholder="Anak/Suami/Istri/dll" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_pelapor"
                        value="<?= htmlspecialchars(getFormValue($data, 'pekerjaan_pelapor')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Alamat</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_pelapor"
                        value="<?= htmlspecialchars(getFormValue($data, 'alamat_pelapor')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" name="desa_pelapor"
                        value="<?= htmlspecialchars(getFormValue($data, 'desa_pelapor')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" name="kecamatan_pelapor"
                        value="<?= htmlspecialchars(getFormValue($data, 'kecamatan_pelapor')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" name="kabupaten_pelapor"
                        value="<?= htmlspecialchars(getFormValue($data, 'kabupaten_pelapor')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" name="provinsi_pelapor"
                        value="<?= htmlspecialchars(getFormValue($data, 'provinsi_pelapor')); ?>">
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
                        value="<?= htmlspecialchars(getFormValue($data, 'nik_saksi1')); ?>" maxlength="16" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Saksi I</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_saksi1"
                        value="<?= htmlspecialchars(getFormValue($data, 'nama_saksi1')); ?>" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur</label>
                    <input type="number" class="form-control form-control-modern" name="umur_saksi1"
                        value="<?= htmlspecialchars(getFormValue($data, 'umur_saksi1')); ?>" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_saksi1"
                        value="<?= htmlspecialchars(getFormValue($data, 'pekerjaan_saksi1')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Alamat</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_saksi1"
                        value="<?= htmlspecialchars(getFormValue($data, 'alamat_saksi1')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" name="desa_saksi1"
                        value="<?= htmlspecialchars(getFormValue($data, 'desa_saksi1')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" name="kecamatan_saksi1"
                        value="<?= htmlspecialchars(getFormValue($data, 'kecamatan_saksi1')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" name="kabupaten_saksi1"
                        value="<?= htmlspecialchars(getFormValue($data, 'kabupaten_saksi1')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" name="provinsi_saksi1"
                        value="<?= htmlspecialchars(getFormValue($data, 'provinsi_saksi1')); ?>">
                </div>

                <div class="col-12 fw-bold text-secondary mt-2" style="font-size:0.85rem;">Saksi II</div>
                <div class="col-md-3 offset-md-0">
                    <label class="form-label form-label-modern">NIK Saksi II</label>
                    <input type="text" class="form-control form-control-modern font-monospace" name="nik_saksi2"
                        value="<?= htmlspecialchars(getFormValue($data, 'nik_saksi2')); ?>" maxlength="16" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label form-label-modern">Nama Saksi II</label>
                    <input type="text" class="form-control form-control-modern text-uppercase" name="nama_saksi2"
                        value="<?= htmlspecialchars(getFormValue($data, 'nama_saksi2')); ?>" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label form-label-modern">Umur</label>
                    <input type="number" class="form-control form-control-modern" name="umur_saksi2"
                        value="<?= htmlspecialchars(getFormValue($data, 'umur_saksi2')); ?>" min="0">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Pekerjaan</label>
                    <input type="text" class="form-control form-control-modern" name="pekerjaan_saksi2"
                        value="<?= htmlspecialchars(getFormValue($data, 'pekerjaan_saksi2')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label form-label-modern">Alamat</label>
                    <input type="text" class="form-control form-control-modern" name="alamat_saksi2"
                        value="<?= htmlspecialchars(getFormValue($data, 'alamat_saksi2')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Desa</label>
                    <input type="text" class="form-control form-control-modern" name="desa_saksi2"
                        value="<?= htmlspecialchars(getFormValue($data, 'desa_saksi2')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kecamatan</label>
                    <input type="text" class="form-control form-control-modern" name="kecamatan_saksi2"
                        value="<?= htmlspecialchars(getFormValue($data, 'kecamatan_saksi2')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Kabupaten</label>
                    <input type="text" class="form-control form-control-modern" name="kabupaten_saksi2"
                        value="<?= htmlspecialchars(getFormValue($data, 'kabupaten_saksi2')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label form-label-modern">Provinsi</label>
                    <input type="text" class="form-control form-control-modern" name="provinsi_saksi2"
                        value="<?= htmlspecialchars(getFormValue($data, 'provinsi_saksi2')); ?>">
                </div>
            </div>

            <!-- FOOTER ACTION BUTTONS -->
            <div class="d-flex justify-content-end gap-3 mt-5 pt-3 border-top">
                <a href="index.php?page=surat-kematian" class="btn btn-secondary px-4 py-2" style="border-radius: 8px;">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" name="update" class="btn btn-warning px-4 py-2 text-dark"
                    style="border-radius: 8px; font-weight: 600;">
                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                </button>
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