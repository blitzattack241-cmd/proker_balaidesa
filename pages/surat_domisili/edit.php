<?php
include 'koneksi.php';

// Pastikan ID dikirim lewat parameter URL
if (!isset($_GET['id'])) {
    echo "<script>window.location='index.php?page=surat-domisili';</script>";
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);
$query_surat = mysqli_query($koneksi, "SELECT * FROM tb_surat_domisili WHERE id_domisili = '$id'");
$data = mysqli_fetch_assoc($query_surat);

// Jika data tidak ditemukan
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php?page=surat-domisili';</script>";
    exit;
}

// Ambil data pejabat untuk dropdown
$query_pejabat = mysqli_query($koneksi, "SELECT * FROM tb_pejabat");

if (isset($_POST['update'])) {
    // Sanitasi input
    $nomor_surat      = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $id_pejabat       = mysqli_real_escape_string($koneksi, $_POST['id_pejabat']);
    $nama_warga       = mysqli_real_escape_string($koneksi, $_POST['nama_warga']);
    $tempat_lahir     = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir    = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $jenis_kelamin    = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $agama            = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $nik              = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $alamat_jalan     = mysqli_real_escape_string($koneksi, $_POST['alamat_jalan']);
    $rt               = mysqli_real_escape_string($koneksi, $_POST['rt']);
    $rw               = mysqli_real_escape_string($koneksi, $_POST['rw']);
    $keperluan        = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    $berlaku_mulai    = mysqli_real_escape_string($koneksi, $_POST['berlaku_mulai']);
    $keterangan_lain  = mysqli_real_escape_string($koneksi, $_POST['keterangan_lain']);
    $tanggal_surat    = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);

    $update = mysqli_query($koneksi, "UPDATE tb_surat_domisili SET 
        nomor_surat = '$nomor_surat', 
        id_pejabat = '$id_pejabat', 
        nama_warga = '$nama_warga', 
        tempat_lahir = '$tempat_lahir', 
        tanggal_lahir = '$tanggal_lahir', 
        jenis_kelamin = '$jenis_kelamin', 
        agama = '$agama', 
        nik = '$nik', 
        alamat_jalan = '$alamat_jalan', 
        rt = '$rt', 
        rw = '$rw', 
        keperluan = '$keperluan', 
        berlaku_mulai = '$berlaku_mulai', 
        keterangan_lain = '$keterangan_lain', 
        tanggal_surat = '$tanggal_surat' 
        WHERE id_domisili = '$id'");

    if ($update) {
        echo "<script>alert('Surat berhasil diperbarui!'); window.location='index.php?page=surat-domisili';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui surat: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<style>
.page-title-modern {
    font-weight: 700;
    color: #2c3e50;
    letter-spacing: -0.5px;
}

.breadcrumb-modern {
    background: transparent;
    padding: 0;
    font-size: 0.9rem;
}

.breadcrumb-modern a {
    color: #0d6efd;
    font-weight: 500;
}

.card-modern {
    border: 1px solid #dee2e6 !important;
    border-radius: 8px !important;
    box-shadow: none !important;
}

.card-header-modern {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #dee2e6 !important;
    padding: 0.75rem 1.25rem !important;
}

.card-header-title {
    font-weight: 500;
    color: #333333;
    font-size: 0.95rem;
}

.form-label {
    font-weight: 500;
    color: #333333;
    font-size: 0.9rem;
    margin-bottom: 0.4rem;
}

.form-control,
.form-select {
    border: 1px solid #cccccc;
    border-radius: 4px;
    padding: 0.45rem 0.75rem;
    font-size: 0.9rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.section-divider {
    border-top: 1px dashed #dee2e6;
    margin: 1.5rem 0;
}

.btn-action {
    padding: 0.4rem 1.2rem;
    font-size: 0.9rem;
    border-radius: 4px;
    font-weight: 500;
}
</style>

<div class="container-fluid px-3 py-3">
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title-modern mb-1">Edit Surat Keterangan Domisili</h3>
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=surat-domisili" class="text-decoration-none">Surat
                        Domisili</a></li>
                <li class="breadcrumb-item active">Edit Surat</li>
            </ol>
        </div>
    </div>

    <!-- Card Form -->
    <div class="card card-modern mb-4">
        <div class="card-header-modern">
            <div class="card-header-title d-flex align-items-center">
                <i class="fas fa-edit me-2 text-secondary"></i> Form Edit Surat Keterangan Domisili
            </div>
        </div>
        <div class="card-body p-4">
            <form method="POST">
                <div class="row g-3">
                    <!-- Bagian Dokumen -->
                    <div class="col-md-6">
                        <label class="form-label">Nomor Surat</label>
                        <input type="text" name="nomor_surat" class="form-control"
                            value="<?= htmlspecialchars($data['nomor_surat']); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Penandatangan (Pejabat)</label>
                        <select name="id_pejabat" class="form-select" required>
                            <option value="">-- Pilih Pejabat --</option>
                            <?php while($p = mysqli_fetch_assoc($query_pejabat)) { ?>
                            <option value="<?= $p['id_pejabat']; ?>"
                                <?= ($p['id_pejabat'] == $data['id_pejabat']) ? 'selected' : ''; ?>>
                                <?= $p['nama_pejabat']; ?> (<?= $p['jabatan']; ?>)
                            </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="section-divider"></div>
                    </div>
                    <h6 class="text-primary fw-bold mb-1">Identitas Pemohon</h6>

                    <!-- Bagian Identitas -->
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_warga" class="form-control"
                            value="<?= htmlspecialchars($data['nama_warga']); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">NIK (16 Digit)</label>
                        <input type="text" name="nik" maxlength="16" class="form-control"
                            value="<?= htmlspecialchars($data['nik']); ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control"
                            value="<?= htmlspecialchars($data['tempat_lahir']); ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control"
                            value="<?= $data['tanggal_lahir']; ?>" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="Laki-Laki" <?= ($data['jenis_kelamin'] == 'Laki-Laki') ? 'selected' : ''; ?>>
                                Laki-Laki</option>
                            <option value="Perempuan" <?= ($data['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>
                                Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Agama</label>
                        <input type="text" name="agama" class="form-control"
                            value="<?= htmlspecialchars($data['agama']); ?>" required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Alamat Tinggal (Nama Jalan/Dukuh)</label>
                        <input type="text" name="alamat_jalan" class="form-control"
                            value="<?= htmlspecialchars($data['alamat_jalan']); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">RT</label>
                        <input type="text" name="rt" class="form-control" value="<?= htmlspecialchars($data['rt']); ?>"
                            required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">RW</label>
                        <input type="text" name="rw" class="form-control" value="<?= htmlspecialchars($data['rw']); ?>"
                            required>
                    </div>

                    <div class="col-12">
                        <div class="section-divider"></div>
                    </div>
                    <h6 class="text-primary fw-bold mb-1">Keperluan & Validitas</h6>

                    <!-- Bagian Keperluan -->
                    <div class="col-12">
                        <label class="form-label">Keperluan</label>
                        <textarea name="keperluan" class="form-control" rows="3"
                            required><?= htmlspecialchars($data['keperluan']); ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Berlaku Mulai</label>
                        <input type="date" name="berlaku_mulai" class="form-control"
                            value="<?= $data['berlaku_mulai']; ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Tanggal Cetak Surat</label>
                        <input type="date" name="tanggal_surat" class="form-control"
                            value="<?= $data['tanggal_surat']; ?>" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Keterangan Lain-Lain (Opsional)</label>
                        <textarea name="keterangan_lain" class="form-control"
                            rows="2"><?= htmlspecialchars($data['keterangan_lain']); ?></textarea>
                    </div>
                </div>

                <!-- Tombol Aksi di Kiri Bawah -->
                <div class="mt-4 d-flex justify-content-start gap-2">
                    <button type="submit" name="update" class="btn btn-primary btn-action">Perbarui Surat</button>
                    <a href="index.php?page=surat-domisili" class="btn btn-secondary btn-action">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>