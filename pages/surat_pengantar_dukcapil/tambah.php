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

// =========================================================================
// LOGIKA GENERATE NOMOR SURAT DUKCAPIL OTOMATIS
// Format Target: [NOMOR_URUT] / 31. 07.16 / [TAHUN]
// Contoh Output: 090 / 31. 07.16 / 2026
// =========================================================================
$tahun_sekarang = date('Y');

// Query nomor surat terakhir dari tb_surat_dukcapil berdasarkan id_surat
$query_no = "SELECT nomor_surat FROM `tb_surat_dukcapil` 
             WHERE nomor_surat LIKE '%/$tahun_sekarang' OR nomor_surat LIKE '%/ $tahun_sekarang' 
             ORDER BY id_surat DESC LIMIT 1"; 

$result_no = mysqli_query($koneksi, $query_no);

$nomor_urut_baru = 90; // Default awal jika database masih kosong

if ($result_no && mysqli_num_rows($result_no) > 0) {
    $row_no = mysqli_fetch_assoc($result_no);
    $nomor_terakhir = $row_no['nomor_surat']; 
    
    // Pecah string berdasarkan karakter slash (/)
    $bagian = explode('/', $nomor_terakhir);
    
    // Ambil bagian nomor urut depan (indeks 0)
    if (isset($bagian[0]) && is_numeric(trim($bagian[0]))) {
        $nomor_urut_baru = (int) trim($bagian[0]) + 1;
    } else {
        preg_match_all('/\d+/', $nomor_terakhir, $matches);
        if (!empty($matches[0])) {
            $nomor_urut_baru = (int)$matches[0][0] + 1;
        }
    }
}

// Format nomor urut menjadi 3 digit angka (contoh: 090, 091, dst)
$nomor_formatted = sprintf("%03d", $nomor_urut_baru);

// Gabungkan menjadi format presisi: "090 / 31. 07.16 / 2026"
$nomor_surat_otomatis = $nomor_formatted . " / 31. 07.16 / " . $tahun_sekarang;
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
                    <small class="text-muted" style="font-size: 0.75rem;">*Terisi otomatis dari nomor surat
                        terakhir.</small>
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