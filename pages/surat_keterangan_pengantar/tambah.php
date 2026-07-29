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
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    exit;
}

// Ambil list pejabat untuk dropdown TTD secara dinamis
$query_pejabat = mysqli_query($koneksi, "SELECT id_pejabat, nama_pejabat, jabatan FROM tb_pejabat ORDER BY nama_pejabat ASC");

// Pengecekan nama tabel dinamis
$tableName = 'tb_surat_pengantar';
$check = mysqli_query($koneksi, "SHOW TABLES LIKE 'surat_pengantar'");
if ($check && mysqli_num_rows($check) > 0) {
    $tableName = 'surat_pengantar';
}

// ==========================================
// 2. PROSES SIMPAN DATA (POST SUBMISSION)
// ==========================================
if (isset($_POST['simpan'])) {
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

    // Menerima nilai id_pejabat yang dipilih dari dropdown form
    $id_pejabat = mysqli_real_escape_string($koneksi, $_POST['id_pejabat'] ?? '');

    // Ambil data pejabat penandatangan dari tabel tb_pejabat
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

    // QUERY UTAMA INSERT DATA
    $insert = mysqli_query($koneksi, "
        INSERT INTO `$tableName` (
            `nomor_surat`, `kode_surat`, `tanggal_surat`, `nik`, `nomor_kk`, `nama_penduduk`, 
            `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `kewenangnegaraan`, `agama`, 
            `status_perkawinan`, `pekerjaan`, `alamat_tinggal`, `keperluan`, `berlaku_mulai`, 
            `berlaku_sampai`, `keterangan_lain`, `nama_pemohon`, `nama_penandatanganan`, `jabatan_penandatanganan`
        ) VALUES (
            '$nomor_surat', '$kode_surat', '$tanggal_surat', '$nik', '$nomor_kk', '$nama_penduduk', 
            '$jenis_kelamin', '$tempat_lahir', '$tanggal_lahir', '$kewanegaraan', '$agama', 
            '$status_perkawinan', '$pekerjaan', '$alamat_tinggal', '$keperluan', '$berlaku_mulai', 
            '$berlaku_sampai', '$keterangan_lain', '$nama_pemohon', '$nama_penandatanganan', '$jabatan_penandatanganan'
        )
    ");

    if ($insert) {
        echo "<script>
                alert('Data Surat Pengantar berhasil ditambahkan!');
                window.location.href = 'index.php?page=surat-pengantar';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menambahkan data: " . mysqli_error($koneksi) . "');
              </script>";
    }
}

// ==========================================
// 3. GENERATE NOMOR SURAT OTOMATIS
// ==========================================
$tahun_sekarang = date('Y');

// Hitung surat di tahun ini
$query_max = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM `$tableName` WHERE YEAR(tanggal_surat) = '$tahun_sekarang'");
$data_max = mysqli_fetch_assoc($query_max);

// Urutan selanjutnya (+1)
$next_no = ($data_max['total'] ?? 0) + 1;

// Format 2 digit: 01, 02, dst (Ganti %02d menjadi %03d jika ingin 3 digit: 001, 002)
$no_urut_formatted = sprintf("%02d", $next_no);

// Hasil gabungan nomor surat otomatis
$no_surat_auto = '400.10.2.2/' . $no_urut_formatted . '/31.07.16/' . $tahun_sekarang;
?>

<!-- STYLING CSS MODERN -->
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
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.btn-custom-save {
    background: linear-gradient(135deg, #0d6efd, #0056b3) !important;
    border: none !important;
    border-radius: 8px !important;
    font-weight: 600;
    padding: 10px 24px;
    box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
    transition: all 0.2s ease;
}

.btn-custom-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(13, 110, 253, 0.35);
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
            <h3 class="page-title-modern mt-2 mb-1">Tambah Surat Pengantar</h3>
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="index.php?page=surat-pengantar" class="text-decoration-none">Daftar
                        Surat Pengantar</a></li>
                <li class="breadcrumb-item active">Tambah</li>
            </ol>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card card-modern my-4">
        <div class="card-header-modern">
            <span class="fs-5 fw-bold text-dark"><i class="fas fa-file-invoice me-2 text-primary"></i> Formulir
                Pembuatan Surat Keterangan Pengantar</span>
        </div>
        <div class="card-body p-4">
            <form action="" method="POST" autocomplete="off">

                <!-- BAGIAN 1: METADATA SURAT -->
                <h5 class="mb-3 text-primary fw-bold"><i class="fas fa-print me-1"></i> 1. Nomor &amp; Klasifikasi Surat
                </h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kode Desa (Kiri Atas)</label>
                        <input type="text" name="kode_surat" value="31.07.16" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nomor Surat Resmi (Tengah)</label>
                        <div class="input-group">
                            <input type="text" name="nomor_surat" class="form-control"
                                value="<?= htmlspecialchars($no_surat_auto); ?>"
                                placeholder="Contoh: 400.10.2.2/01/31.07.16/2026" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Keluar Surat</label>
                        <input type="date" name="tanggal_surat" class="form-control" value="<?= date('Y-m-d'); ?>"
                            required>
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <!-- BAGIAN 2: IDENTITAS WARGA -->
                <h5 class="mb-3 text-primary fw-bold"><i class="fas fa-user-circle me-1"></i> 2. Identitas Objek Warga
                </h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap Warga (Poin 1)</label>
                        <input type="text" name="nama_penduduk" class="form-control text-uppercase"
                            placeholder="Nama sesuai KTP..." required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kelamin (Poin 2)</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="Laki-Laki">Laki-Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tempat Lahir (Poin 3)</label>
                        <input type="text" name="tempat_lahir" class="form-control" placeholder="Tempat Lahir..."
                            required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Lahir (Poin 3)</label>
                        <input type="date" name="tanggal_lahir" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kewarganegaraan (Poin 4)</label>
                        <input type="text" name="kewanegaraan" class="form-control" value="Indonesia" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agama (Poin 5)</label>
                        <select name="agama" class="form-select" required>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Budha">Budha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status Perkawinan (Poin 6)</label>
                        <select name="status_perkawinan" class="form-select" required>
                            <option value="Belum Kawin">Belum Kawin</option>
                            <option value="Kawin">Kawin</option>
                            <option value="Cerai Hidup">Cerai Hidup</option>
                            <option value="Cerai Mati">Cerai Mati</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pekerjaan (Poin 7)</label>
                        <input type="text" name="pekerjaan" class="form-control"
                            placeholder="Contoh: Karyawan Swasta..." required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Tempat Tinggal / Alamat Lengkap (Poin 8)</label>
                        <textarea name="alamat_tinggal" class="form-control" rows="2"
                            placeholder="Desa berugenjang RT ... RW ... Kecamatan Undaan..." required></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Surat Bukti Diri: NIK KTP (Poin 9)</label>
                        <input type="text" name="nik" class="form-control" maxlength="16"
                            placeholder="Masukkan 16 digit NIK..." required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Surat Bukti Diri: No Kartu Keluarga (Poin 9)</label>
                        <input type="text" name="nomor_kk" class="form-control" maxlength="16" value="331904"
                            placeholder="Masukkan 16 digit Nomor KK..." required>
                    </div>
                </div>

                <hr class="my-4 text-muted">

                <!-- BAGIAN 3: KEPERLUAN & OBLIGASI TTD -->
                <h5 class="mb-3 text-primary fw-bold"><i class="fas fa-info-circle me-1"></i> 3. Keterangan Keperluan
                    &amp; Legalisasi</h5>
                <div class="mb-3">
                    <label class="form-label">Keperluan Surat (Poin 10)</label>
                    <textarea name="keperluan" class="form-control" rows="3"
                        placeholder="Persyaratan pengajuan Pasang Baru Listrik..." required></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Berlaku Mulai (Poin 11)</label>
                        <input type="date" name="berlaku_mulai" class="form-control" value="<?= date('Y-m-d'); ?>"
                            required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Berlaku Sampai (Poin 11)</label>
                        <input type="text" name="berlaku_sampai" class="form-control" value="Selesai" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan Lain-lain (Poin 12)</label>
                    <textarea name="keterangan_lain" class="form-control"
                        rows="2">Menerangkan Bahwa Orang tersebut diatas, benar-benar penduduk Desa Megawon dan bertingkah laku baik.</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Pemohon (Tanda Tangan Kanan)</label>
                        <input type="text" name="nama_pemohon" class="form-control" placeholder="Nama Pemohon..."
                            required>
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label">Pejabat Otoritas Penandatangan (Kiri Bawah)</label>
                        <select name="id_pejabat" class="form-select" required>
                            <option value="">-- Pilih Pejabat Mengetahui --</option>
                            <?php while ($pejabat = mysqli_fetch_assoc($query_pejabat)): ?>
                            <option value="<?= $pejabat['id_pejabat']; ?>">
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
                    <button type="submit" name="simpan" class="btn btn-primary btn-custom-save">
                        <i class="fas fa-save me-1"></i> Simpan &amp; Arsipkan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>