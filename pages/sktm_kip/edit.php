<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Akses Admin
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak! Halaman ini hanya untuk Admin.');
            window.location.href = 'index.php?page=dashboard';
          </script>";
    exit;
}

$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");

// Pastikan ada parameter ID yang dikirim
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Surat tidak ditemukan!'); window.location.href='index.php?page=sktm-kip';</script>";
    exit;
}

$id_sktm = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil data surat berdasarkan ID
$query_data = mysqli_query($koneksi, "SELECT * FROM tb_sktm_kip WHERE id_sktm = '$id_sktm'");
if (mysqli_num_rows($query_data) == 0) {
    echo "<script>alert('Data surat tidak ditemukan di database!'); window.location.href='index.php?page=sktm-kip';</script>";
    exit;
}
$data = mysqli_fetch_assoc($query_data);

// Ambil list pejabat untuk dropdown TTD
$query_pejabat = mysqli_query($koneksi, "SELECT id_pejabat, nama_pejabat, jabatan FROM tb_pejabat ORDER BY nama_pejabat ASC");

// Proses Update Data
if (isset($_POST['update'])) {
    $nomor_surat       = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $nama_warga        = mysqli_real_escape_string($koneksi, $_POST['nama_warga']);
    $tempat_lahir      = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir     = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $jenis_kelamin     = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $agama             = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $kewarganegaraan   = mysqli_real_escape_string($koneksi, $_POST['kewarganegaraan']);
    $status_perkawinan = mysqli_real_escape_string($koneksi, $_POST['status_perkawinan']);
    $pekerjaan         = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $alamat_tinggal    = mysqli_real_escape_string($koneksi, $_POST['alamat_tinggal']);
    $no_ktp            = mysqli_real_escape_string($koneksi, $_POST['no_ktp']);
    $no_kk             = mysqli_real_escape_string($koneksi, $_POST['no_kk']);
    $keperluan         = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    $tanggal_surat     = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $id_pejabat        = mysqli_real_escape_string($koneksi, $_POST['id_pejabat']);

    $update_query = "UPDATE tb_sktm_kip SET 
                        nomor_surat = '$nomor_surat',
                        nama_warga = '$nama_warga',
                        tempat_lahir = '$tempat_lahir',
                        tanggal_lahir = '$tanggal_lahir',
                        jenis_kelamin = '$jenis_kelamin',
                        agama = '$agama',
                        kewarganegaraan = '$kewarganegaraan',
                        status_perkawinan = '$status_perkawinan',
                        pekerjaan = '$pekerjaan',
                        alamat_tinggal = '$alamat_tinggal',
                        no_ktp = '$no_ktp',
                        no_kk = '$no_kk',
                        keperluan = '$keperluan',
                        tanggal_surat = '$tanggal_surat',
                        id_pejabat = '$id_pejabat'
                     WHERE id_sktm = '$id_sktm'";

    $update = mysqli_query($koneksi, $update_query);

    if ($update) {
        echo "<script>
                alert('Data SKTM KIP berhasil diperbarui!');
                window.location.href = 'index.php?page=sktm-kip';
              </script>";
    } else {
        echo "<script>
                alert('Gagal memperbarui data: " . mysqli_error($koneksi) . "');
              </script>";
    }
}
?>

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
    font-size: 0.9rem;
}

.form-control,
.form-select {
    border-radius: 8px !important;
    padding: 0.6rem 1rem;
    border: 1px solid #ced4da;
}

.form-control:focus,
.form-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.2);
}

.btn-custom-update {
    background: linear-gradient(135deg, #ffc107, #e0a800) !important;
    border: none !important;
    border-radius: 8px !important;
    color: #212529;
    font-weight: 600;
    padding: 10px 24px;
    box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3);
    transition: all 0.2s ease;
}

.btn-custom-update:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(255, 193, 7, 0.4);
    color: #212529;
}

.btn-custom-back {
    border-radius: 8px !important;
    font-weight: 600;
    padding: 10px 20px;
}
</style>

<div class="container-fluid px-4 py-3">
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="page-title-modern mt-2 mb-1">Edit SKTM KIP</h3>
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=sktm-kip" class="text-decoration-none">Daftar SKTM
                        KIP</a></li>
                <li class="breadcrumb-item active">Edit Data</li>
            </ol>
        </div>
    </div>

    <div class="my-4"></div>

    <!-- Form Card -->
    <div class="card card-modern mb-4">
        <div class="card-header-modern">
            <span class="fs-5 fw-bold text-dark"><i class="fas fa-edit me-2 text-warning"></i> Perbarui Formulir
                Surat</span>
        </div>
        <div class="card-body p-4">
            <form action="" method="POST">

                <div class="row">
                    <!-- Bagian Kiri: Metadata Surat -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nomor Surat</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted">145 /</span>
                            <input type="text" name="nomor_surat" class="form-control"
                                value="<?= htmlspecialchars($data['nomor_surat']); ?>" required>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Surat dibuat</label>
                        <input type="date" name="tanggal_surat" class="form-control"
                            value="<?= htmlspecialchars($data['tanggal_surat']); ?>" required>
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <!-- Bagian Data Personal Warga -->
                <h5 class="mb-3 text-warning fw-bold"><i class="fas fa-user-circle me-1"></i> Identitas Pemohon
                    (Siswa/Mahasiswa)</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_warga" class="form-control text-uppercase"
                            value="<?= htmlspecialchars($data['nama_warga']); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="Laki-laki" <?= ($data['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>
                                Laki-laki</option>
                            <option value="Perempuan" <?= ($data['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>
                                Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control"
                            value="<?= htmlspecialchars($data['tempat_lahir']); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control"
                            value="<?= htmlspecialchars($data['tanggal_lahir']); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" name="no_ktp" class="form-control"
                            value="<?= htmlspecialchars($data['no_ktp']); ?>" maxlength="16" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">No. Kartu Keluarga (KK)</label>
                        <input type="text" name="no_kk" class="form-control"
                            value="<?= htmlspecialchars($data['no_kk']); ?>" maxlength="16" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Agama</label>
                        <select name="agama" class="form-select" required>
                            <?php 
                            $list_agama = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu'];
                            foreach($list_agama as $agm) {
                                $selected = ($data['agama'] == $agm) ? 'selected' : '';
                                echo "<option value='$agm' $selected>$agm</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kewarganegaraan</label>
                        <input type="text" name="kewarganegaraan" class="form-control"
                            value="<?= htmlspecialchars($data['kewarganegaraan']); ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status Pernikahan</label>
                        <select name="status_perkawinan" class="form-select" required>
                            <?php 
                            $list_status = ['Belum Kawin', 'Kawin', 'Cerai Hidup', 'Cerai Mati'];
                            foreach($list_status as $stts) {
                                $selected = ($data['status_perkawinan'] == $stts) ? 'selected' : '';
                                echo "<option value='$stts' $selected>$stts</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control"
                            value="<?= htmlspecialchars($data['pekerjaan']); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat Tinggal Lengkap</label>
                    <textarea name="alamat_tinggal" class="form-control" rows="2"
                        required><?= htmlspecialchars($data['alamat_tinggal']); ?></textarea>
                </div>

                <hr class="my-4 text-muted">

                <!-- Bagian Keperluan & Penandatangan -->
                <h5 class="mb-3 text-warning fw-bold"><i class="fas fa-info-circle"></i> Keperluan Surat & Validasi</h5>

                <div class="mb-3">
                    <label class="form-label">Tujuan / Keperluan Penulisan Surat</label>
                    <textarea name="keperluan" class="form-control" rows="3"
                        required><?= htmlspecialchars($data['keperluan']); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Pejabat Penandatangan (Pihak Balai Desa)</label>
                        <select name="id_pejabat" class="form-select" required>
                            <option value="">-- Pilih Penandatangan --</option>
                            <?php while ($pejabat = mysqli_fetch_assoc($query_pejabat)): ?>
                            <option value="<?= $pejabat['id_pejabat']; ?>"
                                <?= ($data['id_pejabat'] == $pejabat['id_pejabat']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($pejabat['nama_pejabat']); ?>
                                (<?= htmlspecialchars($pejabat['jabatan']); ?>)
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <!-- Bagian Tombol Aksi -->
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <a href="index.php?page=sktm-kip" class="btn btn-secondary btn-custom-back">Batal</a>
                    <button type="submit" name="update" class="btn btn-warning btn-custom-update">
                        <i class="fas fa-save me-1"></i> Perbarui Data
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>