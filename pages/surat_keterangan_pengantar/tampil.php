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

// ==========================================
// 2. KONEKSI & AMBIL DATA DARI DATABASE
// ==========================================
require_once __DIR__ . '/../../koneksi.php';
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    $query = false;
} else {
    $query = false;
    // Mencari tabel tb_surat_pengantar atau surat_pengantar secara dinamis
    $tableCandidates = ['tb_surat_pengantar', 'surat_pengantar'];
    foreach ($tableCandidates as $tableCandidate) {
        $check = mysqli_query($koneksi, "SHOW TABLES LIKE '$tableCandidate'");
        if ($check && mysqli_num_rows($check) > 0) {
            // Mengambil arsip surat pengantar diurutkan dari yang terbaru
            $query = mysqli_query($koneksi, "
                SELECT * FROM `$tableCandidate` 
                ORDER BY tanggal_surat DESC, id_surat DESC
            ");
            break;
        }
    }
}
?>

<!-- ==========================================
     3. STYLING CSS MODERN & PREMIUM UI
     ========================================== -->
<style>
    /* Styling Header Judul Halaman */
    .page-title-modern {
        font-weight: 700;
        color: #2c3e50;
        letter-spacing: -0.5px;
    }

    /* Breadcrumb Minimalis */
    .breadcrumb-modern {
        background: transparent;
        padding: 0;
        font-size: 0.9rem;
    }

    .breadcrumb-modern a {
        color: #17a2b8;
        font-weight: 500;
    }

    /* Card Utama Berwarna Bersih & Bayangan Halus */
    .card-modern {
        border: none !important;
        border-radius: 15px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    /* Header Card Putih Bersih */
    .card-header-modern {
        background-color: #ffffff !important;
        border-bottom: 1px solid #f1f3f5 !important;
        padding: 1.25rem 1.5rem !important;
    }

    .card-header-title {
        font-weight: 600;
        color: #495057;
        font-size: 1.1rem;
    }

    /* Tombol Tambah Surat Gradasi Biru */
    .btn-tambah-modern {
        background: linear-gradient(135deg, #0d6efd, #0056b3) !important;
        border: none !important;
        border-radius: 8px !important;
        padding: 8px 16px !important;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
        transition: all 0.25s ease;
    }

    .btn-tambah-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(13, 110, 253, 0.35);
    }

    /* Styling Tabel Modern */
    .table-modern {
        margin-bottom: 0 !important;
    }

    /* Mengubah warna hitam pekat standard (.table-dark) menjadi Slate Gelap Premium */
    .table-modern thead th {
        background-color: #1e293b !important;
        color: #ffffff !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.82rem;
        letter-spacing: 0.5px;
        padding: 14px 10px !important;
        border: none !important;
    }

    #dataTable td {
        vertical-align: middle !important;
        padding: 12px 10px !important;
    }

    /* Perbaikan Kotak Data Kosong (Presisi Tengah & Simetris) */
    .empty-state-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 50px 20px !important;
        color: #6c757d;
    }

    .empty-state-icon {
        font-size: 3.5rem;
        color: #dee2e6;
        margin-bottom: 15px;
    }

    .empty-state-text {
        font-size: 0.95rem;
        font-weight: 500;
        color: #868e96;
    }

    /* Kotak & Animasi Tombol Kendali Aksi */
    .action-btn {
        width: 68px;
        height: 52px;
        border-radius: 10px !important;
        border: none !important;
        font-weight: 600;
        font-size: 11px !important;
        transition: all 0.25s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        color: #ffffff !important;
        text-decoration: none;
    }

    .action-btn i {
        font-size: 16px;
        margin-bottom: 3px;
    }

    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        color: #ffffff !important;
    }

    /* Pewarnaan Tombol Aksi */
    .btn-cetak-modern {
        background-color: #17a2b8 !important;
    }

    .btn-cetak-modern:hover {
        background-color: #138496 !important;
    }

    .btn-update-modern {
        background-color: #ffc107 !important;
    }

    .btn-update-modern:hover {
        background-color: #e0a800 !important;
    }

    .btn-delete-modern {
        background-color: #dc3545 !important;
    }

    .btn-delete-modern:hover {
        background-color: #bd2130 !important;
    }
</style>

<!-- ==========================================
     4. STRUKTUR KONTEN STRUKTUR HTML INTERFACE
     ========================================== -->
<div class="container-fluid px-4 py-3">
    <!-- Row Bagian Judul Atas -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="page-title-modern mt-2 mb-1">Surat Keterangan Pengantar</h3>
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Daftar Surat Pengantar</li>
            </ol>
        </div>
    </div>

    <!-- Spasi Pemisah Jarak -->
    <div class="my-4"></div>

    <!-- Card Wadah Master Tabel -->
    <div class="card card-modern mb-4">
        <!-- Card Header Premium -->
        <div class="card-header-modern d-flex justify-content-between align-items-center">
            <div class="card-header-title d-flex align-items-center">
                <i class="fas fa-envelope-open-text me-2 text-primary"></i> Data Arsip Surat Keterangan / Pengantar Desa
            </div>
            <!-- Akses Navigasi Tambah Data Baru -->
            <a href="index.php?page=surat-pengantar-tambah" class="btn btn-primary btn-tambah-modern">
                <i class="fas fa-plus me-1"></i> Tambah Surat Baru
            </a>
        </div>

        <!-- Card Body Utama -->
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-striped table-modern align-middle" id="dataTable" width="100%"
                    cellspacing="0">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="18%">No. Surat</th>
                            <th width="12%">Tgl Keluar</th>
                            <th width="18%">Nama Warga (NIK)</th>
                            <th width="32%">Keperluan / Tujuan Pengantar</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($data = mysqli_fetch_assoc($query)) {
                                $tanggal = date('d-m-Y', strtotime($data['tanggal_surat']));
                                ?>
                                <tr>
                                    <!-- Nomor Baris -->
                                    <td class="text-center fw-bold text-secondary"><?= $no++; ?></td>

                                    <!-- Kolom Nomor Surat Resmi -->
                                    <td class="fw-semibold text-dark">
                                        <?= htmlspecialchars($data['nomor_surat']); ?>
                                    </td>

                                    <!-- Kolom Tanggal Pembuatan Surat -->
                                    <td><?= $tanggal; ?></td>

                                    <!-- Kolom Info Warga Terkait -->
                                    <td>
                                        <span class="fw-bold text-uppercase text-primary d-block">
                                            <?= htmlspecialchars($data['nama_penduduk']); ?>
                                        </span>
                                        <small class="text-muted font-monospace" style="font-size: 11px;">
                                            NIK: <?= htmlspecialchars($data['nik']); ?>
                                        </small>
                                    </td>

                                    <!-- Kolom Deskripsi Keperluan Pengantar -->
                                    <td>
                                        <small class="text-muted d-block" style="font-size: 11px; font-weight: 600;">TUJUAN /
                                            KEPERLUAN:</small>
                                        <span style="font-size: 0.9rem;">
                                            <?= nl2br(htmlspecialchars(substr($data['keperluan'], 0, 110))); ?>
                                            <?= strlen($data['keperluan']) > 110 ? '...' : ''; ?>
                                        </span>
                                    </td>

                                    <!-- Tombol Kendali Aksi Cetak, Edit, Hapus -->
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <!-- Cetak Dokumen PDF/Print -->
                                            <a href="pages/surat_keterangan_pengantar/cetak.php?id=<?= $data['id_surat']; ?>"
                                                target="_blank"
                                                class="btn btn-sm d-flex flex-column align-items-center justify-content-center action-btn btn-cetak-modern"
                                                title="Cetak Surat">
                                                <i class="fas fa-print"></i>
                                                <span>Cetak</span>
                                            </a>

                                            <!-- Edit/Update Data Dokumen -->
                                            <a href="index.php?page=surat-pengantar-edit&id=<?= $data['id_surat']; ?>"
                                                class="btn btn-sm d-flex flex-column align-items-center justify-content-center action-btn btn-update-modern"
                                                title="Edit Informasi">
                                                <i class="fas fa-edit"></i>
                                                <span>Edit</span>
                                            </a>

                                            <!-- Hapus Data Dokumen Permanen -->
                                            <a href="pages/surat_keterangan_pengantar/hapus.php?id=<?= $data['id_surat']; ?>"
                                                class="btn btn-sm d-flex flex-column align-items-center justify-content-center action-btn btn-delete-modern"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus arsip Surat Pengantar ini?');"
                                                title="Hapus Permanen">
                                                <i class="fas fa-trash"></i>
                                                <span>Delete</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <!-- TAMPILAN JIKA REKAMAN ARSIP KOSONG (PERBAIKAN TOTAL SIMETRIS TENGAH) -->
                            <tr>
                                <td colspan="6" class="text-center py-0" style="background: #f8f9fa !important;">
                                    <div class="empty-state-container">
                                        <i class="fas fa-folder-open empty-state-icon"></i>
                                        <div class="empty-state-text">Belum ada rekaman arsip data Surat Keterangan
                                            Pengantar.</div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>