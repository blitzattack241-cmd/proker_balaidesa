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
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    exit;
}

// =========================================================================
// LOGIKA GENERATE NOMOR SURAT SKTM KIP OTOMATIS
// =========================================================================
$tahun_sekarang = date('Y');

// Query mengambil angka urut tertinggi pada kolom nomor_surat di tahun berjalan
// Mengambil bagian indeks ke-2 dari split '/' dan mengubahnya ke integer
$query_max_no = mysqli_query($koneksi, "
    SELECT MAX(CAST(TRIM(SUBSTRING_INDEX(SUBSTRING_INDEX(nomor_surat, '/', 2), '/', -1)) AS UNSIGNED)) as max_no 
    FROM tb_sktm_kip 
    WHERE nomor_surat LIKE '%/$tahun_sekarang'
");

$data_max = mysqli_fetch_assoc($query_max_no);
$no_urut_terakhir = $data_max['max_no'] ?? 0;

// Auto Increment
$nomor_urut_baru = $no_urut_terakhir + 1;

// Format menjadi 3 digit angka (001, 002, 010, dst)
$nomor_formatted = sprintf("%03d", $nomor_urut_baru);

// Susun String Nomor Surat Otomatis
$nomor_surat_otomatis = "474 / " . $nomor_formatted . " / 31.07.16 / " . $tahun_sekarang;


// Ambil list pejabat untuk dropdown TTD
$query_pejabat = mysqli_query($koneksi, "SELECT id_pejabat, nama_pejabat, jabatan FROM tb_pejabat ORDER BY nama_pejabat ASC");

// Proses Simpan Data
if (isset($_POST['simpan'])) {
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

    $insert = mysqli_query($koneksi, "INSERT INTO tb_sktm_kip (
        nomor_surat, nama_warga, tempat_lahir, tanggal_lahir, jenis_kelamin, 
        agama, kewarganegaraan, status_perkawinan, pekerjaan, alamat_tinggal, 
        no_ktp, no_kk, keperluan, tanggal_surat, id_pejabat
    ) VALUES (
        '$nomor_surat', '$nama_warga', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', 
        '$agama', '$kewarganegaraan', '$status_perkawinan', '$pekerjaan', '$alamat_tinggal', 
        '$no_ktp', '$no_kk', '$keperluan', '$tanggal_surat', '$id_pejabat'
    )");

    if ($insert) {
        echo "<script>
                alert('Data SKTM KIP berhasil ditambahkan!');
                window.location.href = 'index.php?page=sktm-kip';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan data: " . mysqli_error($koneksi) . "');
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
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
}

.btn-custom-save {
    background: linear-gradient(135deg, #28a745, #1e7e34) !important;
    border: none !important;
    border-radius: 8px !important;
    font-weight: 600;
    padding: 10px 24px;
    box-shadow: 0 4px 10px rgba(40, 167, 69, 0.2);
    transition: all 0.2s ease;
}

.btn-custom-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(40, 167, 69, 0.3);
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
            <h3 class="page-title-modern mt-2 mb-1">Tambah SKTM KIP</h3>
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=sktm-kip" class="text-decoration-none">Daftar SKTM
                        KIP</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
    </div>

    <div class="my-4"></div>

    <!-- Form Card -->
    <div class="card card-modern mb-4">
        <div class="card-header-modern">
            <span class="fs-5 fw-bold text-dark"><i class="fas fa-file-invoice me-2 text-primary"></i> Formulir
                Pembuatan Surat</span>
        </div>
        <div class="card-body p-4">
            <form action="" method="POST">

                <div class="row">
                    <!-- Bagian Kiri: Metadata Surat -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nomor Surat</label>
                        <!-- Input terisi otomatis via variable $nomor_surat_otomatis -->
                        <input type="text" name="nomor_surat" class="form-control"
                            value="<?= htmlspecialchars($nomor_surat_otomatis); ?>"
                            placeholder="Contoh: 474 / 001 / 31.07.16 / 2026" required>
                        <small class="text-muted">*Terisi otomatis sesuai penomoran registrasi desa (dapat disesuaikan
                            manual jika perlu).</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Surat dibuat</label>
                        <input type="date" name="tanggal_surat" class="form-control" value="<?= date('Y-m-d'); ?>"
                            required>
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <!-- Bagian Data Personal Warga -->
                <h5 class="mb-3 text-primary fw-bold"><i class="fas fa-user-circle me-1"></i> Identitas Pemohon
                    (Siswa/Mahasiswa)</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_warga" class="form-control text-uppercase"
                            placeholder="Nama lengkap warga" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" class="form-control" placeholder="Kabupaten/Kota lahir"
                            required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" name="no_ktp" class="form-control" placeholder="16 digit NIK" maxlength="16"
                            required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">No. Kartu Keluarga (KK)</label>
                        <input type="text" name="no_kk" class="form-control" placeholder="16 digit No KK" maxlength="16"
                            value="331904" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Agama</label>
                        <select name="agama" class="form-select" required>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Budha">Budha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kewarganegaraan</label>
                        <input type="text" name="kewarganegaraan" class="form-control" value="Indonesia" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status Pernikahan</label>
                        <select name="status_perkawinan" class="form-select" required>
                            <option value="Belum Kawin">Belum Kawin</option>
                            <option value="Kawin">Kawin</option>
                            <option value="Cerai Hidup">Cerai Hidup</option>
                            <option value="Cerai Mati">Cerai Mati</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Pekerjaan</label>
                        <input type="text" name="pekerjaan" class="form-control" value="Pelajar/Mahasiswa" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Alamat Tinggal Lengkap</label>
                    <textarea name="alamat_tinggal" class="form-control" rows="2"
                        placeholder="Contoh: Desa Berugenjang Rt 002 Rw 002 Kec. Undaan Kab. Kudus" required></textarea>
                </div>

                <hr class="my-4 text-muted">

                <!-- Bagian Keperluan & Penandatangan -->
                <h5 class="mb-3 text-primary fw-bold"><i class="fas fa-info-circle"></i> Keperluan Surat & Validasi</h5>

                <div class="mb-3">
                    <label class="form-label">Tujuan / Keperluan Penulisan Surat</label>
                    <textarea name="keperluan" class="form-control" rows="3"
                        placeholder="Contoh: Pendaftaran dan Pengajuan Keringanan biaya Kuliah di Universitas Islam Negeri Walisongo Semarang"
                        required></textarea>
                    <small class="text-muted">Masukkan instansi pendidikan tinggi atau sekolah tujuan pengajuan beasiswa
                        KIP.</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Pejabat Penandatangan (Pihak Balai Desa)</label>
                        <select name="id_pejabat" class="form-select" required>
                            <option value="">-- Pilih Penandatangan --</option>
                            <?php while ($pejabat = mysqli_fetch_assoc($query_pejabat)): ?>
                            <option value="<?= $pejabat['id_pejabat']; ?>">
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
                    <button type="submit" name="simpan" class="btn btn-success btn-custom-save">
                        <i class="fas fa-save me-1"></i> Simpan Surat
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>