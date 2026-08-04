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

require_once __DIR__ . '/../../includes/nomor_surat_helper.php';

// Ambil list pejabat untuk dropdown TTD secara dinamis
$query_pejabat = mysqli_query($koneksi, "SELECT id_pejabat, nama_pejabat, jabatan FROM tb_pejabat ORDER BY nama_pejabat ASC");

// ==========================================
// 2. PROSES SIMPAN DATA (POST SUBMISSION)
// ==========================================
if (isset($_POST['simpan'])) {
    $nomor_surat        = generateNomorSuratGlobal($koneksi, true);
    $kode_surat         = $_POST['kode_surat'] ?? '';
    $tanggal_surat      = $_POST['tanggal_surat'] ?? '';
    $nik                = $_POST['nik'] ?? '';
    $nomor_kk           = $_POST['nomor_kk'] ?? '';
    $nama_penduduk      = $_POST['nama_penduduk'] ?? '';
    $jenis_kelamin      = $_POST['jenis_kelamin'] ?? '';
    $tempat_lahir       = $_POST['tempat_lahir'] ?? '';
    $tanggal_lahir      = $_POST['tanggal_lahir'] ?? '';
    $kewarganegaraan    = $_POST['kewarganegaraan'] ?? 'Indonesia';
    $agama              = $_POST['agama'] ?? '';
    $status_perkawinan  = $_POST['status_perkawinan'] ?? '';
    $pekerjaan          = $_POST['pekerjaan'] ?? '';
    $alamat_tinggal     = $_POST['alamat_tinggal'] ?? '';
    $keperluan          = $_POST['keperluan'] ?? '';
    $berlaku_mulai      = $_POST['berlaku_mulai'] ?? '';
    $berlaku_sampai     = $_POST['berlaku_sampai'] ?? '';
    $keterangan_lain    = $_POST['keterangan_lain'] ?? '';
    $nama_pemohon       = $_POST['nama_pemohon'] ?? '';
    $id_pejabat         = $_POST['id_pejabat'] ?? '';

    // Ambil data pejabat penandatangan dari tabel tb_pejabat
    $nama_penandatanganan = '';
    $jabatan_penandatanganan = '';
    if (!empty($id_pejabat)) {
        $stmt_pejabat = mysqli_prepare($koneksi, "SELECT nama_pejabat, jabatan FROM tb_pejabat WHERE id_pejabat = ?");
        mysqli_stmt_bind_param($stmt_pejabat, "i", $id_pejabat);
        mysqli_stmt_execute($stmt_pejabat);
        $res_pej = mysqli_stmt_get_result($stmt_pejabat);
        if ($pej = mysqli_fetch_assoc($res_pej)) {
            $nama_penandatanganan    = $pej['nama_pejabat'];
            $jabatan_penandatanganan = $pej['jabatan'];
        }
        mysqli_stmt_close($stmt_pejabat);
    }

    // Pengecekan nama tabel dinamis
    $tableName = 'tb_surat_pengantar';
    $check = mysqli_query($koneksi, "SHOW TABLES LIKE 'surat_pengantar'");
    if ($check && mysqli_num_rows($check) > 0) {
        $tableName = 'surat_pengantar';
    }

    // QUERY UTAMA INSERT DATA (Menggunakan 'kewenangnegaraan' sesuai kolom database)
    $sql = "INSERT INTO `$tableName` (
                `nomor_surat`, `kode_surat`, `tanggal_surat`, `nik`, `nomor_kk`, `nama_penduduk`, 
                `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `kewenangnegaraan`, `agama`, 
                `status_perkawinan`, `pekerjaan`, `alamat_tinggal`, `keperluan`, `berlaku_mulai`, 
                `berlaku_sampai`, `keterangan_lain`, `nama_pemohon`, `nama_penandatanganan`, `jabatan_penandatanganan`
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($koneksi, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param(
            $stmt,
            "sssssssssssssssssssss",
            $nomor_surat, $kode_surat, $tanggal_surat, $nik, $nomor_kk, $nama_penduduk,
            $jenis_kelamin, $tempat_lahir, $tanggal_lahir, $kewarganegaraan, $agama,
            $status_perkawinan, $pekerjaan, $alamat_tinggal, $keperluan, $berlaku_mulai,
            $berlaku_sampai, $keterangan_lain, $nama_pemohon, $nama_penandatanganan, $jabatan_penandatanganan
        );

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    alert('Data Surat Pengantar berhasil ditambahkan!');
                    window.location.href = 'index.php?page=surat-pengantar';
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('Gagal menambahkan data: " . addslashes(mysqli_stmt_error($stmt)) . "');
                  </script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>
                alert('Gagal menyiapkan query: " . addslashes(mysqli_error($koneksi)) . "');
              </script>";
    }
}

// Nomor surat global otomatis untuk preview form
$no_surat_auto = generateNomorSuratGlobal($koneksi, false);
?>

<!-- Include Assets Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
    rel="stylesheet" />

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

.box-pencarian {
    background-color: #f8fafc;
    border: 1px dashed #0d6efd;
    border-radius: 12px;
    padding: 1.25rem;
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

                <!-- BAGIAN 0: PENCARIAN OTOMATIS PENDUDUK -->
                <div class="box-pencarian mb-4">
                    <label class="form-label text-primary fw-bold mb-2">
                        <i class="fas fa-search me-1"></i> Cari & Auto-fill Data Penduduk (Ketik No. KK / NIK / Nama)
                    </label>
                    <select id="cari_penduduk" class="form-select" style="width: 100%;">
                        <option value="">-- Ketik No. KK, NIK, atau Nama Penduduk... --</option>
                    </select>
                    <small class="text-muted mt-1 d-block">
                        <i class="fas fa-info-circle me-1"></i> Pilih nama warga yang muncul untuk mengisi otomatis
                        seluruh formulir identitas di bawah ini.
                    </small>
                </div>

                <!-- BAGIAN 1: METADATA SURAT -->
                <h5 class="mb-3 text-primary fw-bold"><i class="fas fa-print me-1"></i> 1. Nomor &amp; Klasifikasi Surat
                </h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kode Desa (Kiri Atas)</label>
                        <input type="text" name="kode_surat" value="31.07.16/2026" class="form-control" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Nomor Surat Resmi (Tengah)</label>
                        <div class="input-group">
                            <input type="text" name="nomor_surat" class="form-control"
                                value="<?= htmlspecialchars($no_surat_auto); ?>" placeholder="Contoh: 471 / 2026"
                                required>
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
                        <input type="text" id="input_nama" name="nama_penduduk" class="form-control text-uppercase"
                            placeholder="Nama sesuai KTP..." required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Jenis Kelamin (Poin 2)</label>
                        <select id="input_jenis_kelamin" name="jenis_kelamin" class="form-select" required>
                            <option value="Laki-Laki">Laki-Laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tempat Lahir (Poin 3)</label>
                        <input type="text" id="input_tempat_lahir" name="tempat_lahir" class="form-control"
                            placeholder="Tempat Lahir..." required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tanggal Lahir (Poin 3)</label>
                        <input type="date" id="input_tanggal_lahir" name="tanggal_lahir" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kewarganegaraan (Poin 4)</label>
                        <input type="text" id="input_kewarganegaraan" name="kewarganegaraan" class="form-control"
                            value="Indonesia" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Agama (Poin 5)</label>
                        <select id="input_agama" name="agama" class="form-select" required>
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
                        <select id="input_status_perkawinan" name="status_perkawinan" class="form-select" required>
                            <option value="Belum Kawin">Belum Kawin</option>
                            <option value="Kawin">Kawin</option>
                            <option value="Cerai Hidup">Cerai Hidup</option>
                            <option value="Cerai Mati">Cerai Mati</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pekerjaan (Poin 7)</label>
                        <input type="text" id="input_pekerjaan" name="pekerjaan" class="form-control"
                            placeholder="Contoh: Karyawan Swasta..." required>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Tempat Tinggal / Alamat Lengkap (Poin 8)</label>
                        <textarea id="input_alamat" name="alamat_tinggal" class="form-control" rows="2"
                            placeholder="Alamat RT/RW Desa..." required></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Surat Bukti Diri: NIK KTP (Poin 9)</label>
                        <input type="text" id="input_nik" name="nik" class="form-control" maxlength="16"
                            placeholder="Masukkan 16 digit NIK..." required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Surat Bukti Diri: No Kartu Keluarga (Poin 8)</label>
                        <input type="text" id="input_nomor_kk" name="nomor_kk" maxlength="16" class="form-control"
                            placeholder="Contoh: 33190..." required>
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
                        rows="2">Menerangkan Bahwa Orang tersebut diatas, benar-benar penduduk Desa dan bertingkah laku baik.</textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Pemohon (Tanda Tangan Kanan)</label>
                        <input type="text" id="input_nama_pemohon" name="nama_pemohon" class="form-control"
                            placeholder="Nama Pemohon..." required>
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

<!-- Script jQuery & Select2 Autocomplete -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Inisialisasi Dropdown Select2
    $('#cari_penduduk').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Ketik No. KK, NIK, atau Nama Penduduk... --',
        allowClear: true,
        ajax: {
            url: 'api/get_penduduk.php',
            dataType: 'json',
            delay: 300,
            data: function(params) {
                return {
                    search: params.term // Kirim kata kunci ke API
                };
            },
            processResults: function(data) {
                return {
                    results: data.results
                };
            },
            cache: true
        }
    });

    // Event ketika item dipilih dari dropdown
    $('#cari_penduduk').on('select2:select', function(e) {
        var data = e.params.data;

        // Isikan otomatis data ke input form
        $('#input_nama').val(data.nama);
        $('#input_nama_pemohon').val(data.nama);
        $('#input_nik').val(data.nik);
        $('#input_nomor_kk').val(data.no_kk);
        $('#input_pekerjaan').val(data.pekerjaan);
        $('#input_alamat').val(data.alamat_lengkap);

        // Pisahkan tempat lahir dan tanggal lahir jika ada
        if (data.tempat_tgl_lahir) {
            var ttl = data.tempat_tgl_lahir.split(',');
            if (ttl.length > 1) {
                $('#input_tempat_lahir').val(ttl[0].trim());
            } else {
                $('#input_tempat_lahir').val(data.tempat_tgl_lahir);
            }
        }

        // Auto Select Jenis Kelamin
        if (data.jenis_kelamin) {
            var jk = data.jenis_kelamin.toLowerCase();
            if (jk.includes('l')) {
                $('#input_jenis_kelamin').val('Laki-Laki');
            } else if (jk.includes('p')) {
                $('#input_jenis_kelamin').val('Perempuan');
            }
        }

        // Auto Select Agama
        if (data.agama) {
            $('#input_agama').val(data.agama);
        }
    });

    // Event ketika pilihan dibersihkan (Clear)
    $('#cari_penduduk').on('select2:clear', function(e) {
        $('#input_nama').val('');
        $('#input_nama_pemohon').val('');
        $('#input_nik').val('');
        $('#input_nomor_kk').val('');
        $('#input_tempat_lahir').val('');
        $('#input_tanggal_lahir').val('');
        $('#input_pekerjaan').val('');
        $('#input_alamat').val('');
    });
});
</script>