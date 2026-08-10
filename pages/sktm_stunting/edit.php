<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek hak akses admin
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak! Halaman ini hanya untuk Admin.');
            window.location.href = 'index.php?page=dashboard';
          </script>";
    exit;
}

require_once __DIR__ . '/../../koneksi.php';

// Validasi parameter ID Surat
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>
            alert('ID Surat tidak ditemukan!');
            window.location.href = 'index.php?page=sktm-stunting';
          </script>";
    exit;
}

$id_sktm = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil data lama SKTM Stunting
$query = mysqli_query($koneksi, "SELECT * FROM tb_sktm_stunting WHERE id_sktm = '$id_sktm'");
if (mysqli_num_rows($query) === 0) {
    echo "<script>
            alert('Data tidak ditemukan!');
            window.location.href = 'index.php?page=sktm-stunting';
          </script>";
    exit;
}

$data = mysqli_fetch_assoc($query);

// Ambil data pejabat untuk pilihan penandatangan surat
$pejabat_query = mysqli_query($koneksi, "SELECT id_pejabat, nama_pejabat, jabatan FROM tb_pejabat ORDER BY nama_pejabat ASC");
?>

<!-- Style Kustom untuk Formulir Modern -->
<style>
    .card-form {
        border: none !important;
        border-radius: 15px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05) !important;
    }

    .card-form-header {
        background-color: #ffffff !important;
        border-bottom: 1px solid #f1f3f5 !important;
        padding: 1.5rem !important;
    }

    .form-label-custom {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .form-control-custom {
        border-radius: 8px !important;
        padding: 0.6rem 1rem !important;
        border: 1px solid #ced4da !important;
        transition: all 0.2s ease;
    }

    .form-control-custom:focus {
        border-color: #0d6efd !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15) !important;
    }

    .section-divider {
        border-top: 2px dashed #e9ecef;
        margin: 2rem 0;
    }

    .section-title-custom {
        font-size: 1.1rem;
        font-weight: 700;
        color: #fd7e14;
        /* Warna orange tanda edit/update */
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-submit-custom {
        background: linear-gradient(135deg, #ffc107, #e0a800) !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 10px 24px !important;
        font-weight: 600;
        color: #000 !important;
        box-shadow: 0 4px 10px rgba(255, 193, 7, 0.2);
        transition: all 0.25s ease;
    }

    .btn-submit-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(255, 193, 7, 0.35);
    }

    .btn-batal-custom {
        border-radius: 8px !important;
        padding: 10px 24px !important;
        font-weight: 600;
    }
</style>

<div class="container-fluid px-4 py-3">
    <!-- Header Halaman -->
    <div class="mb-4">
        <h3 class="fw-bold text-dark mt-2 mb-1">Edit SKTM Stunting</h3>
        <ol class="breadcrumb mb-0" style="background: transparent; padding: 0; font-size: 0.9rem;">
            <li class="breadcrumb-item"><a href="index.php?page=dashboard" class="text-decoration-none">Dashboard</a>
            </li>
            <li class="breadcrumb-item"><a href="index.php?page=sktm-stunting" class="text-decoration-none">Daftar SKTM
                    Stunting</a></li>
            <li class="breadcrumb-item active">Ubah Data Surat</li>
        </ol>
    </div>

    <!-- Card Form -->
    <div class="card card-form mb-5">
        <div class="card-form-header">
            <h5 class="mb-0 fw-bold text-secondary"><i class="fas fa-edit me-2 text-warning"></i> Formulir Perubahan
                Data Surat</h5>
        </div>
        <div class="card-body p-4">
            <form action="pages/sktm_stunting/proses-edit.php" method="POST">

                <!-- ID Hidden untuk acuan klausa WHERE saat update -->
                <input type="hidden" name="id_sktm" value="<?= $data['id_sktm']; ?>">

                <!-- BAGIAN 1: INFORMASI SURAT -->
                <div class="section-title-custom">
                    <i class="fas fa-info-circle"></i> Detail Administrasi Surat
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Nomor Surat (Sufiks)</label>
                        <input type="text" name="nomor_surat" class="form-control form-control-custom"
                            placeholder="Contoh: 045.2/012/2026" required
                            value="<?= htmlspecialchars($data['nomor_surat']); ?>">
                        <small class="text-muted">Format standar: 145 / [Isian Anda]</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Tanggal Surat dibuat</label>
                        <input type="date" name="tanggal_surat" class="form-control form-control-custom" required
                            value="<?= $data['tanggal_surat']; ?>">
                    </div>
                </div>

                <div class="section-divider"></div>

                <!-- BAGIAN 2: DATA ORANG TUA / WALI -->
                <div class="section-title-custom">
                    <i class="fas fa-user-friends"></i> Identitas Orang Tua / Wali
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Nama Lengkap Orang Tua / Wali</label>
                        <input type="text" name="nama_warga" class="form-control form-control-custom"
                            placeholder="Masukkan nama lengkap orang tua" required
                            value="<?= htmlspecialchars($data['nama_warga']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Nomor Induk Kependudukan (No. KTP)</label>
                        <input type="text" name="no_ktp" class="form-control form-control-custom"
                            placeholder="Masukkan 16 digit NIK" maxlength="16" required
                            value="<?= htmlspecialchars($data['no_ktp']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Nomor Kartu Keluarga (No. KK)</label>
                        <input type="text" name="no_kk" class="form-control form-control-custom"
                            placeholder="Masukkan 16 digit Nomor KK" maxlength="16" required
                            value="<?= htmlspecialchars($data['no_kk']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label form-label-custom">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control form-control-custom"
                            placeholder="Contoh: Kudus" required
                            value="<?= htmlspecialchars($data['tempat_lahir']); ?>">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label form-label-custom">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control form-control-custom" required
                            value="<?= $data['tanggal_lahir']; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label form-label-custom">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select form-control-custom" required>
                            <option value="Laki-laki" <?= $data['jenis_kelamin'] === 'Laki-laki' ? 'selected' : ''; ?>>
                                Laki-laki</option>
                            <option value="Perempuan" <?= $data['jenis_kelamin'] === 'Perempuan' ? 'selected' : ''; ?>>
                                Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label form-label-custom">Agama</label>
                        <select name="agama" class="form-select form-control-custom" required>
                            <?php
                            $list_agama = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Khonghucu'];
                            foreach ($list_agama as $ag) {
                                $selected = ($data['agama'] === $ag) ? 'selected' : '';
                                echo "<option value='$ag' $selected>$ag</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label form-label-custom">Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control form-control-custom"
                            placeholder="Contoh: Buruh Harian Lepas" required
                            value="<?= htmlspecialchars($data['pekerjaan']); ?>">
                    </div>
                    <div class="col-12">
                        <label class="form-label form-label-custom">Alamat Tinggal Domisili</label>
                        <textarea name="alamat_tinggal" class="form-control form-control-custom" rows="2"
                            placeholder="Masukkan alamat lengkap rumah orang tua saat ini..."
                            required><?= htmlspecialchars($data['alamat_tinggal']); ?></textarea>
                    </div>
                </div>

                <div class="section-divider"></div>

                <!-- BAGIAN 3: DATA ANAK & TUJUAN -->
                <div class="section-title-custom">
                    <i class="fas fa-baby"></i> Identitas Anak Terindikasi Stunting & Keperluan
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Nama Anak</label>
                        <input type="text" name="nama_anak" class="form-control form-control-custom"
                            placeholder="Masukkan nama anak balita" required
                            value="<?= htmlspecialchars($data['nama_anak']); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Kewarganegaraan Orang Tua/Anak</label>
                        <input type="text" name="kewarganegaraan" class="form-control form-control-custom"
                            value="<?= htmlspecialchars($data['kewarganegaraan']); ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label form-label-custom">Keperluan Surat</label>
                        <textarea name="keperluan" class="form-control form-control-custom" rows="3"
                            placeholder="Masukkan keperluan..."
                            required><?= htmlspecialchars($data['keperluan']); ?></textarea>
                    </div>
                </div>

                <div class="section-divider"></div>

                <!-- BAGIAN 4: PENANDATANGAN -->
                <div class="section-title-custom">
                    <i class="fas fa-pen-nib"></i> Validasi Otoritas Perangkat Desa
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label form-label-custom">Pejabat Penandatangan</label>
                        <select name="id_pejabat" class="form-select form-control-custom" required>
                            <option value="" disabled>-- Pilih Pejabat yang Mengesahkan --</option>
                            <?php while ($pejabat = mysqli_fetch_assoc($pejabat_query)): ?>
                                <option value="<?= $pejabat['id_pejabat']; ?>"
                                    <?= $data['id_pejabat'] == $pejabat['id_pejabat'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($pejabat['nama_pejabat']); ?>
                                    (<?= htmlspecialchars($pejabat['jabatan']); ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <!-- TOMBOL AKSI -->
                <div class="mt-5 d-flex justify-content-end gap-3">
                    <a href="index.php?page=sktm-stunting" class="btn btn-outline-secondary btn-batal-custom">
                        <i class="fas fa-arrow-left me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-warning btn-submit-custom">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>