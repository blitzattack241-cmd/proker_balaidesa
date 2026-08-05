<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi ke database jika belum di-include global
if (!isset($koneksi)) {
    include 'koneksi.php';
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

require_once __DIR__ . '/../../includes/nomor_surat_helper.php';

// Nomor surat global otomatis untuk semua jenis surat
$nomor_surat_otomatis = generateNomorSuratGlobal($koneksi, false); // preview saja, tidak menambah nomor
?>

<div class="container-fluid px-4">
    <h3 class="mt-4">Tambah Surat Pengantar Dukcapil</h3>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">Tambah Surat</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-envelope me-1"></i> Form Surat Pengantar
        </div>
        <div class="card-body">
            <!-- Action form diarahkan langsung ke file proses -->
            <form action="pages/surat_pengantar_dukcapil/proses_tambah.php" method="POST">

                <div class="mb-3">
                    <label for="nomor_surat" class="form-label">Nomor Surat</label>
                    <input type="text" class="form-control" id="nomor_surat" name="nomor_surat"
                        value="<?= htmlspecialchars($nomor_surat_otomatis); ?>" required>
                    <small class="text-muted" style="font-size: 0.75rem;">*Terisi otomatis (dapat diubah manual)</small>
                </div>

                <div class="mb-3">
                    <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                    <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat"
                        value="<?= date('Y-m-d'); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="jenis_dikirim" class="form-label">Jenis Yang Dikirim (Fleksibel / Teks Bebas)</label>
                    <textarea class="form-control" id="jenis_dikirim" name="jenis_dikirim" rows="4"
                        placeholder="Contoh: Laporan Kependudukan Bulan Juni 2026 Desa Berugenjang..."
                        required></textarea>
                </div>

                <div class="mb-3">
                    <label for="banyaknya" class="form-label">Banyaknya</label>
                    <input type="text" class="form-control" id="banyaknya" name="banyaknya"
                        placeholder="Contoh: 1 Bendel / 1 Berkas" required>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                        placeholder="Contoh: Dikirim dengan hormat, untuk dipergunakan sebagaimana mestinya."
                        required>Dikirim dengan hormat, untuk di pergunakan sebagaimana mestinya</textarea>
                </div>

                <div class="mt-4 mb-0">
                    <button type="submit" name="simpan" class="btn btn-primary">Simpan & Cetak</button>
                    <a href="index.php?page=surat-pengantar-dukcapil" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>