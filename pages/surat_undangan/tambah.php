<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    exit;
}

require_once __DIR__ . '/../../includes/nomor_surat_helper.php';

// Nomor surat global otomatis untuk semua jenis surat
$nomor_surat_otomatis = generateNomorSuratGlobal($koneksi, false); // preview saja, tidak menambah nomor


// Ambil data pejabat untuk dropdown penandatangan surat
$query_pejabat = mysqli_query($koneksi, "SELECT * FROM `tb_pejabat` ORDER BY `jabatan` ASC");

// Proses Simpan Data Form
if (isset($_POST['simpan'])) {
    // Reservasi nomor surat definitif di sini (saat benar-benar disimpan),
    // bukan saat halaman form dibuka, agar nomor tidak bertambah saat batal/reload.
    $nomor_surat = mysqli_real_escape_string($koneksi, generateNomorSuratGlobal($koneksi, true));
    $sifat = mysqli_real_escape_string($koneksi, $_POST['sifat']);
    $lampiran = mysqli_real_escape_string($koneksi, $_POST['lampiran']);
    $perihal = mysqli_real_escape_string($koneksi, $_POST['perihal']);
    $tempat_surat = mysqli_real_escape_string($koneksi, $_POST['tempat_surat']);
    $tanggal_surat = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $hari_acara = mysqli_real_escape_string($koneksi, $_POST['hari_acara']);
    $tanggal_acara = mysqli_real_escape_string($koneksi, $_POST['tanggal_acara']);
    $jam_acara = mysqli_real_escape_string($koneksi, $_POST['jam_acara']);
    $tempat_acara = mysqli_real_escape_string($koneksi, $_POST['tempat_acara']);
    $acara = mysqli_real_escape_string($koneksi, $_POST['acara']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $id_pejabat = (int) $_POST['id_pejabat'];

    // Mengaktifkan fitur transaksi database demi keamanan data relasional
    mysqli_begin_transaction($koneksi);

    try {
        // 1. Insert ke tabel utama (tb_surat_undangan)
        $sql_surat = "INSERT INTO `tb_surat_undangan` 
        (`nomor_surat`, `sifat`, `lampiran`, `perihal`, `tempat_surat`, `tanggal_surat`, `hari_acara`, `tanggal_acara`, `jam_acara`, `tempat_acara`, `acara`, `keterangan`, `id_pejabat`) 
        VALUES 
        ('$nomor_surat', '$sifat', '$lampiran', '$perihal', '$tempat_surat', '$tanggal_surat', '$hari_acara', '$tanggal_acara', '$jam_acara', '$tempat_acara', '$acara', '$keterangan', '$id_pejabat')";

        if (!mysqli_query($koneksi, $sql_surat)) {
            throw new Exception("Gagal menyimpan data utama surat: " . mysqli_error($koneksi));
        }

        // Ambil ID utama surat yang baru saja di-insert
        $id_undangan_baru = mysqli_insert_id($koneksi);

        // 2. Ambil data array penerima dari form dinamis
        $nama_tujuan = $_POST['nama_tujuan'] ?? [];
        $jabatan_tujuan = $_POST['jabatan_tujuan'] ?? [];
        $nama_jabatan_tujuan = $_POST['nama_jabatan_tujuan'] ?? [];
        $alamat_tujuan = $_POST['alamat_tujuan'] ?? [];

        // Validasi minimal harus ada 1 nama penerima
        if (empty($nama_tujuan) || empty(trim($nama_tujuan[0]))) {
            throw new Exception("Daftar Penerima Undangan tidak boleh kosong! Mohon isi minimal satu nama penerima.");
        }

        // Loop untuk memasukkan semua baris penerima
        for ($i = 0; $i < count($nama_tujuan); $i++) {
            if (!empty(trim($nama_tujuan[$i]))) {
                $nama = mysqli_real_escape_string($koneksi, $nama_tujuan[$i]);
                $jabatan = mysqli_real_escape_string($koneksi, $jabatan_tujuan[$i]);
                $nama_jabatan = mysqli_real_escape_string($koneksi, $nama_jabatan_tujuan[$i] ?? '');
                $alamat = mysqli_real_escape_string($koneksi, $alamat_tujuan[$i]);

                $sql_tujuan = "INSERT INTO `tb_undangan_tujuan` (`id_undangan`, `nama_tujuan`, `jabatan_tujuan`, `nama_jabatan_tujuan`, `alamat_tujuan`) 
                               VALUES ('$id_undangan_baru', '$nama', '$jabatan', '$nama_jabatan', '$alamat')";

                if (!mysqli_query($koneksi, $sql_tujuan)) {
                    throw new Exception("Gagal menyimpan data penerima pada baris ke-" . ($i + 1) . ": " . mysqli_error($koneksi));
                }
            }
        }

        // Jika semua query berhasil tanpa error, terapkan ke database
        mysqli_commit($koneksi);

        echo "<script>
                alert('Surat Undangan dan Daftar Penerima Berhasil Ditambahkan!');
                window.location.href = 'index.php?page=surat-undangan';
              </script>";
        exit;

    } catch (Exception $e) {
        // Jika ada satu saja yang gagal, batalkan semua perubahan data
        mysqli_rollback($koneksi);
        echo "<div class='alert alert-danger m-4'><i class='fas fa-exclamation-triangle me-2'></i><strong>Sistem Gagal Menyimpan:</strong> " . $e->getMessage() . "</div>";
    }
}
?>

<style>
    .card-modern {
        border: none !important;
        border-radius: 15px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05) !important;
    }

    .page-title-modern {
        font-weight: 700;
        color: #2c3e50;
    }

    .form-label-custom {
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }

    .section-divider {
        border-top: 2px dashed #e9ecef;
        margin: 2rem 0;
    }
</style>

<div class="container-fluid px-4 py-3">
    <!-- Header Halaman -->
    <div class="mb-4">
        <h3 class="page-title-modern mt-2 mb-1">Tambah Surat Undangan</h3>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=surat-undangan">Daftar Surat</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </div>

    <!-- Form Utama -->
    <form action="" method="POST">
        <div class="row">

            <!-- SISI KIRI: ATRIBUT SURAT & DETAIL AGENDA -->
            <div class="col-lg-7 mb-4">
                <div class="card card-modern p-4">
                    <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-file-alt me-2"></i>Atribut & Detail Agenda
                    </h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Nomor Surat</label>
                            <!-- Input terisi otomatis via nilai $nomor_surat_otomatis -->
                            <input type="text" class="form-control" name="nomor_surat"
                                value="<?= $nomor_surat_otomatis; ?>" placeholder="Contoh: 005/79/31.07.16/2026"
                                required>
                            <small class="text-muted" style="font-size: 0.75rem;">*Terisi otomatis (dapat diubah
                                manual)</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Sifat</label>
                            <input type="text" class="form-control" name="sifat" value="Penting" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Lampiran</label>
                            <input type="text" class="form-control" name="lampiran" value="-" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Perihal</label>
                            <input type="text" class="form-control" name="perihal" value="UNDANGAN" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Tempat Pembuatan Surat</label>
                            <input type="text" class="form-control" name="tempat_surat" value="Berugenjang" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Tanggal Surat Dibuat</label>
                            <input type="date" class="form-control" name="tanggal_surat" value="<?= date('Y-m-d'); ?>"
                                required>
                        </div>

                        <div class="col-12">
                            <div class="section-divider my-2"></div>
                        </div>

                        <!-- Bagian Detail Acara Pelaksanaan -->
                        <div class="col-md-4">
                            <label class="form-label form-label-custom">Hari Acara</label>
                            <select class="form-select" name="hari_acara" required>
                                <option value="Senin">Senin</option>
                                <option value="Selasa">Selasa</option>
                                <option value="Rabu">Rabu</option>
                                <option value="Kamis">Kamis</option>
                                <option value="Jumat">Jumat</option>
                                <option value="Sabtu">Sabtu</option>
                                <option value="Minggu">Minggu</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-custom">Tanggal Acara</label>
                            <input type="date" class="form-control" name="tanggal_acara" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-custom">Jam/Waktu</label>
                            <input type="text" class="form-control" name="jam_acara"
                                placeholder="Contoh: 19.00 WIB s/d Selesai" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label form-label-custom">Tempat Pelaksanaan Acara</label>
                            <input type="text" class="form-control" name="tempat_acara"
                                placeholder="Contoh: Aula Balai Desa Berugenjang" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label form-label-custom">Nama Acara / Agenda</label>
                            <input type="text" class="form-control" name="acara"
                                placeholder="Contoh: Nyiwer / Sosialisasi Tanam" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label form-label-custom">Keterangan Tambahan</label>
                            <textarea class="form-control" name="keterangan" rows="2"
                                placeholder="Contoh: Mohon Hadir Tepat Waktu & Membawa Masker"></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label form-label-custom">Pejabat Penandatangan (Ttd)</label>
                            <select class="form-select" name="id_pejabat" required>
                                <option value="">-- Pilih Pejabat Desa --</option>
                                <?php while ($pejabat = mysqli_fetch_assoc($query_pejabat)): ?>
                                    <option value="<?= $pejabat['id_pejabat']; ?>">
                                        <?= htmlspecialchars($pejabat['nama_pejabat']) . " (" . htmlspecialchars($pejabat['jabatan']) . ")"; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SISI KANAN: DAFTAR PENERIMA UNDANGAN (DINAMIS JAVASCRIPT) -->
            <div class="col-lg-5 mb-4">
                <div class="card card-modern p-4 bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-success"><i class="fas fa-users me-2"></i>Daftar Penerima</h5>
                        <button type="button" class="btn btn-sm btn-success" id="btn-tambah-penerima">
                            <i class="fas fa-plus me-1"></i> Tambah Baris
                        </button>
                    </div>

                    <div id="container-penerima">
                        <!-- Baris Default Awal -->
                        <div class="card p-3 mb-3 item-penerima border-0 shadow-sm"
                            style="border-left: 4px solid #198754 !important;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-secondary badge bg-white border">Penerima #1</span>
                            </div>
                            <div class="mb-2">
                                <label class="small fw-semibold text-muted">Jabatan / Sapaan (Yth.)</label>
                                <input type="text" class="form-control form-control-sm" name="jabatan_tujuan[]"
                                    value="Bpk/Ibu/Sdr/I" placeholder="Contoh: Camat Undaan / Ketua RT">
                            </div>
                            <div class="mb-2">
                                <label class="small fw-semibold text-muted">Nama Penerima</label>
                                <input type="text" class="form-control form-control-sm" name="nama_tujuan[]"
                                    placeholder="Contoh: NASIMAN / BUDI" required>
                            </div>
                            <div class="mb-2">
                                <label class="small fw-semibold text-muted">Nama Jabatan</label>
                                <input type="text" class="form-control form-control-sm" name="nama_jabatan_tujuan[]"
                                    placeholder="Contoh: Ketua RT / Ketua RW">
                            </div>
                            <div>
                                <label class="small fw-semibold text-muted">Alamat Tujuan</label>
                                <input type="text" class="form-control form-control-sm" name="alamat_tujuan[]"
                                    value="Tempat" placeholder="Contoh: Tempat / Kediaman">
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Box Tombol Simpan / Batal -->
                <div class="card card-modern p-3 mt-3 text-end">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php?page=surat-undangan" class="btn btn-light border px-4 fw-semibold">Batal</a>
                        <button type="submit" name="simpan" class="btn btn-primary px-4 fw-semibold">Simpan
                            Surat</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- JAVASCRIPT LOGIC UNTUK MANIPULASI BARIS PENERIMA -->
<script>
    document.getElementById('btn-tambah-penerima').addEventListener('click', function () {
        var container = document.getElementById('container-penerima');
        var jumlahBaris = container.getElementsByClassName('item-penerima').length + 1;

        var htmlBarisBaru = `
    <div class="card p-3 mb-3 item-penerima border-0 shadow-sm animate__animated animate__fadeIn" style="border-left: 4px solid #198754 !important;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-bold text-secondary badge bg-white border">Penerima #${jumlahBaris}</span>
            <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-hapus-baris" style="padding: 2px 6px;">
                <i class="fas fa-trash-alt"></i> Hapus
            </button>
        </div>
        <div class="mb-2">
            <label class="small fw-semibold text-muted">Jabatan / Sapaan (Yth.)</label>
            <input type="text" class="form-control form-control-sm" name="jabatan_tujuan[]" value="Bpk/Ibu/Sdr/I">
        </div>
        <div class="mb-2">
            <label class="small fw-semibold text-muted">Nama Penerima</label>
            <input type="text" class="form-control form-control-sm" name="nama_tujuan[]" placeholder="Nama lengkap penerima" required>
        </div>
        <div class="mb-2">
            <label class="small fw-semibold text-muted">Nama Jabatan</label>
            <input type="text" class="form-control form-control-sm" name="nama_jabatan_tujuan[]" placeholder="Contoh: Ketua RT / Ketua RW">
        </div>
        <div>
            <label class="small fw-semibold text-muted">Alamat Tujuan</label>
            <input type="text" class="form-control form-control-sm" name="alamat_tujuan[]" value="Tempat">
        </div>
    </div>`;

        container.insertAdjacentHTML('beforeend', htmlBarisBaru);
    });

    // Event Handler dinamis untuk menghapus baris penerima tambahan
    document.getElementById('container-penerima').addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-hapus-baris') || e.target.closest('.btn-hapus-baris')) {
            var baris = e.target.closest('.item-penerima');
            baris.remove();

            // Mengurutkan ulang nomor label (Penerima #1, #2, dst) pasca penghapusan
            var semuaBaris = document.getElementsByClassName('item-penerima');
            for (var i = 0; i < semuaBaris.length; i++) {
                semuaBaris[i].querySelector('.badge').innerText = 'Penerima #' + (i + 1);
            }
        }
    });
</script>