<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================
// 1. PROTEKSI HALAMAN ADMIN (SECURITY LOCK)
// ==========================================
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak! Halaman ini hanya untuk Admin.');
            window.location.href = 'index.php?page=dashboard';
          </script>";
    exit;
}

// Koneksi Database
require_once __DIR__ . '/../../koneksi.php';
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    exit;
}

// Deteksi nama tabel dinamis
$tableName = 'tb_surat_pengantar';
$check = mysqli_query($koneksi, "SHOW TABLES LIKE 'surat_pengantar'");
if ($check && mysqli_num_rows($check) > 0) {
    $tableName = 'surat_pengantar';
}

// Ambil ID data yang akan diedit
$id = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';

if (empty($id)) {
    echo "<script>
            alert('ID Data tidak ditemukan!');
            window.location.href = 'index.php?page=surat-pengantar';
          </script>";
    exit;
}

// Ambil data surat lama berdasarkan ID
$query_surat = mysqli_query($koneksi, "SELECT * FROM `$tableName` WHERE `id_surat` = '$id'");
$data = mysqli_fetch_assoc($query_surat);

$tahun_sekarang = date('Y');
$nomor_surat_default = '400.10.2.2/ /31.07.16/' . $tahun_sekarang;
$nomor_surat_value = !empty($data['nomor_surat']) ? $data['nomor_surat'] : $nomor_surat_default;

if (!$data) {
    echo "<script>
            alert('Data tidak ditemukan di database!');
            window.location.href = 'index.php?page=surat-pengantar';
          </script>";
    exit;
}

// Ambil list pejabat untuk dropdown TTD secara dinamis
$query_pejabat = mysqli_query($koneksi, "SELECT id_pejabat, nama_pejabat, jabatan FROM tb_pejabat ORDER BY nama_pejabat ASC");

// Cari ID pejabat yang cocok berdasarkan nama/jabatan jika data lama tidak memiliki id_pejabat
$selected_pejabat_id = '';
if (!empty($data['id_pejabat'])) {
    $selected_pejabat_id = $data['id_pejabat'];
} elseif (!empty($data['nama_penandatanganan']) || !empty($data['jabatan_penandatanganan'])) {
    $namaPT = mysqli_real_escape_string($koneksi, $data['nama_penandatanganan'] ?? '');
    $jabatanPT = mysqli_real_escape_string($koneksi, $data['jabatan_penandatanganan'] ?? '');
    $matchPejabat = mysqli_query($koneksi, "SELECT id_pejabat FROM tb_pejabat WHERE nama_pejabat = '$namaPT' AND jabatan = '$jabatanPT' LIMIT 1");
    if ($matchPejabat && mysqli_num_rows($matchPejabat) > 0) {
        $selectedPej = mysqli_fetch_assoc($matchPejabat);
        $selected_pejabat_id = $selectedPej['id_pejabat'];
    }
}

// ==========================================
// 2. PROSES UPDATE DATA (POST SUBMISSION)
// ==========================================
if (isset($_POST['update'])) {
    $nomor_surat = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $kode_surat = mysqli_real_escape_string($koneksi, $_POST['kode_surat']);
    $tanggal_surat = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $nik = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $nomor_kk = mysqli_real_escape_string($koneksi, $_POST['nomor_kk']);
    $nama_penduduk = mysqli_real_escape_string($koneksi, $_POST['nama_penduduk']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $kewanegaraan = mysqli_real_escape_string($koneksi, $_POST['kewanegaraan']);
    $agama = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $status_perkawinan = mysqli_real_escape_string($koneksi, $_POST['status_perkawinan']);
    $pekerjaan = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $alamat_tinggal = mysqli_real_escape_string($koneksi, $_POST['alamat_tinggal']);
    $keperluan = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    $berlaku_mulai = mysqli_real_escape_string($koneksi, $_POST['berlaku_mulai']);
    $berlaku_sampai = mysqli_real_escape_string($koneksi, $_POST['berlaku_sampai']);
    $keterangan_lain = mysqli_real_escape_string($koneksi, $_POST['keterangan_lain']);
    $nama_pemohon = mysqli_real_escape_string($koneksi, $_POST['nama_pemohon']);
    $id_pejabat = mysqli_real_escape_string($koneksi, $_POST['id_pejabat'] ?? '');
    $nama_penandatanganan = '';
    $jabatan_penandatanganan = '';
    if (!empty($id_pejabat)) {
        $pejabatData = mysqli_query($koneksi, "SELECT nama_pejabat, jabatan FROM tb_pejabat WHERE id_pejabat = '$id_pejabat'");
        if ($pejabatData && mysqli_num_rows($pejabatData) > 0) {
            $pej = mysqli_fetch_assoc($pejabatData);
            $nama_penandatanganan = mysqli_real_escape_string($koneksi, $pej['nama_pejabat']);
            $jabatan_penandatanganan = mysqli_real_escape_string($koneksi, $pej['jabatan']);
        }
    }

    // QUERY UPDATE DATA
    $update = mysqli_query($koneksi, "
        UPDATE `$tableName` SET 
            `nomor_surat` = '$nomor_surat',
            `kode_surat` = '$kode_surat',
            `tanggal_surat` = '$tanggal_surat',
            `nik` = '$nik',
            `nomor_kk` = '$nomor_kk',
            `nama_penduduk` = '$nama_penduduk',
            `jenis_kelamin` = '$jenis_kelamin',
            `tempat_lahir` = '$tempat_lahir',
            `tanggal_lahir` = '$tanggal_lahir',
            `kewenangnegaraan` = '$kewanegaraan',
            `agama` = '$agama',
            `status_perkawinan` = '$status_perkawinan',
            `pekerjaan` = '$pekerjaan',
            `alamat_tinggal` = '$alamat_tinggal',
            `keperluan` = '$keperluan',
            `berlaku_mulai` = '$berlaku_mulai',
            `berlaku_sampai` = '$berlaku_sampai',
            `keterangan_lain` = '$keterangan_lain',
            `nama_pemohon` = '$nama_pemohon',
            `nama_penandatanganan` = '$nama_penandatanganan',
            `jabatan_penandatanganan` = '$jabatan_penandatanganan'
        WHERE `id_surat` = '$id'
    ");

    if ($update) {
        echo "<script>
                alert('Data Surat Pengantar berhasil diperbarui!');
                window.location.href = 'index.php?page=surat-pengantar';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui data: " . mysqli_error($koneksi) . "');
              </script>";
    }
}
?>

<!-- ==========================================
     3. STYLING CSS MODERN & PREMIUM UI
     ========================================== -->
<style>
    .page-title-modern {
        font-weight: 700;
        color: #2c3e50;
        letter-spacing: -0.5px;
    }

    .breadcrumb-modern a {
        color: #17a2b8;
        font-weight: 500;
    }

    .card-modern {
        border: none !important;
        border-radius: 15px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05) !important;
    }

    .card-header-modern {
        background-color: #ffffff !important;
        border-bottom: 1px solid #f1f3f5 !important;
        padding: 1.25rem 1.5rem !important;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .form-control,
    .form-select {
        border-radius: 8px !important;
        padding: 0.6rem 1rem;
        border: 1.5px solid #e2e8f0;
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.15);
    }

    .btn-custom-update {
        background: linear-gradient(135deg, #28a745, #1e7e34) !important;
        border: none !important;
        border-radius: 8px !important;
        font-weight: 600;
        padding: 10px 24px;
        color: #fff;
        box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
        transition: all 0.2s ease;
    }

    .btn-custom-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(40, 167, 69, 0.35);
        color: #fff;
    }

    .btn-custom-back {
        border-radius: 8px !important;
        font-weight: 600;
        padding: 10px 20px;
        background-color: #f1f5f9 !important;
        border: 1.5px solid #cbd5e1 !important;
        color: #475569 !important;
    }

    .btn-custom-back:hover {
        background-color: #e2e8f0 !important;
        transform: translateY(-2px);
    }
</style>

<div class="container-fluid px-4 py-3">
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="page-title-modern mt-2 mb-1">Edit Surat Pengantar</h3>
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=surat-pengantar" class="text-decoration-none">Daftar
                        Surat Pengantar</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card card-modern my-4">
        <div class="card-header-modern">
            <span class="fs-5 fw-bold text-dark"><i class="fas fa-edit me-2 text-success"></i> Perbarui Formulir Surat
                Keterangan Pengantar</span>
        </div>
        <div class="card-body p-4">
            <form action="" method="POST" autocomplete="off">

                <!-- BAGIAN 1: METADATA SURAT -->
                <h5 class="mb-3 text-success fw-bold"><i class="fas fa-print me-1"></i> 1. Nomor &amp; Klasifikasi Surat
                </h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kode Desa (Kiri Atas)</label>
                        <input type="text" name="kode_surat" value="<?= htmlspecialchars($data['kode_surat']); ?>"
                            class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nomor Surat Resmi (Tengah)</label>
                        <div class="input-group">

                            <input type="text" name="nomor_surat" class="form-control"
                                value="<?= htmlspecialchars($nomor_surat_value); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Keluar Surat</label>
                        <input type="date" name="tanggal_surat" class="form-control"
                            value="<?= htmlspecialchars($data['tanggal_surat']); ?>" required>
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <!-- BAGIAN 2: IDENTITAS WARGA -->
                <h5 class="mb-3 text-success fw-bold"><i class="fas fa-user-circle me-1"></i> 2. Identitas Objek Warga
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap Warga (Poin 1)</label>
                        <input type="text" name="nama_penduduk" class="form-control text-uppercase"
                            value="<?= htmlspecialchars($data['nama_penduduk']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kelamin (Poin 2)</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="Laki-Laki" <?= ($data['jenis_kelamin'] == 'Laki-Laki') ? 'selected' : ''; ?>>
                                Laki-Laki</option>
                            <option value="Perempuan" <?= ($data['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>
                                Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tempat Lahir (Poin 3)</label>
                        <input type="text" name="tempat_lahir" class="form-control"
                            value="<?= htmlspecialchars($data['tempat_lahir']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Lahir (Poin 3)</label>
                        <input type="date" name="tanggal_lahir" class="form-control"
                            value="<?= htmlspecialchars($data['tanggal_lahir']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kewarganegaraan (Poin 4)</label>
                        <input type="text" name="kewanegaraan" class="form-control"
                            value="<?= htmlspecialchars($data['kewenangnegaraan'] ?? 'Indonesia'); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agama (Poin 5)</label>
                        <select name="agama" class="form-select" required>
                            <?php
                            $daftar_agama = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu'];
                            foreach ($daftar_agama as $ag) {
                                $selected = (strtolower($data['agama']) == strtolower($ag)) ? 'selected' : '';
                                echo "<option value='$ag' $selected>$ag</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status Perkawinan (Poin 6)</label>
                        <select name="status_perkawinan" class="form-select" required>
                            <option value="Belum Kawin" <?= ($data['status_perkawinan'] == 'Belum Kawin') ? 'selected' : ''; ?>>Belum Kawin
                            </option>
                            <option value="Kawin" <?= ($data['status_perkawinan'] == 'Kawin') ? 'selected' : ''; ?>>
                                Kawin</option>
                            <option value="Cerai Hidup" <?= ($data['status_perkawinan'] == 'Cerai Hidup') ? 'selected' : ''; ?>>Cerai Hidup
                            </option>
                            <option value="Cerai Mati" <?= ($data['status_perkawinan'] == 'Cerai Mati') ? 'selected' : ''; ?>>Cerai Mati
                            </option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pekerjaan (Poin 7)</label>
                        <input type="text" name="pekerjaan" class="form-control"
                            value="<?= htmlspecialchars($data['pekerjaan']); ?>" required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Tempat Tinggal / Alamat Lengkap (Poin 8)</label>
                        <textarea name="alamat_tinggal" class="form-control" rows="2"
                            required><?= htmlspecialchars($data['alamat_tinggal']); ?></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Surat Bukti Diri: NIK KTP (Poin 9)</label>
                        <input type="text" name="nik" class="form-control" maxlength="16"
                            value="<?= htmlspecialchars($data['nik']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Surat Bukti Diri: No Kartu Keluarga (Poin 9)</label>
                        <input type="text" name="nomor_kk" class="form-control" maxlength="16"
                            value="<?= htmlspecialchars($data['nomor_kk']); ?>" required>
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <!-- BAGIAN 3: KEPERLUAN & OBLIGASI TTD -->
                <h5 class="mb-3 text-success fw-bold"><i class="fas fa-info-circle me-1"></i> 3. Keterangan Keperluan
                    &amp; Legalisasi</h5>
                <div class="mb-3">
                    <label class="form-label">Keperluan Surat (Poin 10)</label>
                    <textarea name="keperluan" class="form-control" rows="3"
                        required><?= htmlspecialchars($data['keperluan']); ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Berlaku Mulai (Poin 11)</label>
                        <input type="date" name="berlaku_mulai" class="form-control"
                            value="<?= htmlspecialchars($data['berlaku_mulai']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Berlaku Sampai (Poin 11)</label>
                        <input type="text" name="berlaku_sampai" class="form-control"
                            value="<?= htmlspecialchars($data['berlaku_sampai']); ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan Lain-lain (Poin 12)</label>
                    <textarea name="keterangan_lain" class="form-control"
                        rows="2"><?= htmlspecialchars($data['keterangan_lain']); ?></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Pemohon (Tanda Tangan Kanan)</label>
                        <input type="text" name="nama_pemohon" class="form-control"
                            value="<?= htmlspecialchars($data['nama_pemohon']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Pejabat Otoritas Penandatangan (Kiri Bawah)</label>
                        <select name="id_pejabat" class="form-select" required>
                            <option value="">-- Pilih Pejabat Mengetahui --</option>
                            <?php while ($pejabat = mysqli_fetch_assoc($query_pejabat)): ?>
                                <?php
                                $selected = (($selected_pejabat_id ?? '') == $pejabat['id_pejabat']) ? 'selected' : '';
                                ?>
                                <option value="<?= $pejabat['id_pejabat']; ?>" <?= $selected; ?>>
                                    <?= htmlspecialchars($pejabat['nama_pejabat']); ?>
                                    (<?= htmlspecialchars($pejabat['jabatan']); ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <!-- Bagian Aksi Kontrol -->
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="index.php?page=surat-pengantar" class="btn btn-custom-back">Batal</a>
                    <button type="submit" name="update" class="btn btn-success btn-custom-update">
                        <i class="fas fa-save me-1"></i> Perbarui &amp; Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>