<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Halaman: Hanya Admin yang boleh mengakses halaman ini
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
require_once __DIR__ . '/../../koneksi.php';
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    $query = false;
} else {
    $query = false;
    // Cek kandidat nama tabel untuk surat kematian
    $tableCandidates = ['tb_surat_kematian', 'surat_kematian'];
    foreach ($tableCandidates as $tableCandidate) {
        $check = mysqli_query($koneksi, "SHOW TABLES LIKE '$tableCandidate'");
        if ($check && mysqli_num_rows($check) > 0) {
            // Ambil data surat kematian diurutkan berdasarkan surat terbaru
            // Disesuaikan dengan entitas F-2.29 (Jenazah dan Pelapor)
            $sql = "SELECT id_surat, nomor_surat, nik_jenazah, nama_jenazah, nama_pelapor, tanggal_surat 
                    FROM `$tableCandidate` 
                    ORDER BY tanggal_surat DESC, id_surat DESC";
            $query = mysqli_query($koneksi, $sql);
            break;
        }
    }
}
?>

<!-- Style CSS Modern untuk Header, Card, dan Tombol -->
<style>
    /* Styling Header Halaman */
    .page-title-modern {
        font-weight: 700;
        color: #2c3e50;
        letter-spacing: -0.5px;
    }

    /* Breadcrumb Modern & Minimalis */
    .breadcrumb-modern {
        background: transparent;
        padding: 0;
        font-size: 0.9rem;
    }

    .breadcrumb-modern a {
        color: #17a2b8;
        font-weight: 500;
    }

    /* Card Utama Modern */
    .card-modern {
        border: none !important;
        border-radius: 15px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
    }

    /* Header Card Bergaya Bersih */
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

    /* Tombol Tambah Surat Baru Premium */
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
        box-shadow: 0 6px 15px rgba(220, 53, 69, 0.35);
    }

    /* Styling Dasar Tombol Aksi di Tabel */
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

    /* Warna Kustom Tombol Aksi */
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

    /* Merapikan isi sel tabel */
    #dataTable td {
        vertical-align: middle !important;
    }

    #dataTable th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
</style>

<div class="container-fluid px-4 py-3">
    <!-- Header Halaman Modern -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="page-title-modern mt-2 mb-1">Surat Kematian</h3>
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Daftar Surat Kematian</li>
            </ol>
        </div>
    </div>

    <!-- Spasi Pemisah -->
    <div class="my-4"></div>

    <!-- Card Utama Berisi Tabel -->
    <div class="card card-modern mb-4">
        <!-- Card Header Modern -->
        <div class="card-header-modern d-flex justify-content-between align-items-center">
            <div class="card-header-title d-flex align-items-center">
                <i class="fas fa-book-dead me-2 text-danger"></i> Data Surat Keterangan Kematian (F-2.29) Berugenjang
            </div>
            <!-- Tombol Tambah Halaman Baru Modern -->
            <a href="index.php?page=surat-kematian-tambah" class="btn btn-danger btn-tambah-modern">
                <i class="fas fa-plus me-1"></i> Tambah Surat Baru
            </a>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="20%">No. Surat / Tgl</th>
                            <th width="20%">NIK Jenazah</th>
                            <th width="20%">Nama Jenazah</th>
                            <th width="20%">Nama Pelapor</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($data = mysqli_fetch_assoc($query)) {
                                $tanggal_surat = date('d-m-Y', strtotime($data['tanggal_surat']));
                                ?>
                                <tr>
                                    <td class="text-center fw-bold text-secondary"><?= $no++; ?></td>
                                    <td class="fw-semibold text-dark">
                                        <?= htmlspecialchars($data['nomor_surat']); ?><br>
                                        <small class="text-muted"><i class="far fa-calendar-alt me-1"></i>
                                            <?= $tanggal_surat; ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark p-2 border font-monospace text-start w-100">
                                            <i class="fas fa-id-card me-1 text-secondary"></i>
                                            <?= htmlspecialchars($data['nik_jenazah']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-danger text-uppercase">
                                            <?= htmlspecialchars($data['nama_jenazah']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">
                                            <?= htmlspecialchars($data['nama_pelapor']); ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">

                                            <!-- Tombol Cetak -->
                                            <a href="pages/surat_kematian/cetak.php?id=<?= $data['id_surat']; ?>"
                                                target="_blank"
                                                class="btn btn-sm d-flex flex-column align-items-center justify-content-center action-btn btn-cetak-modern"
                                                title="Cetak Surat">
                                                <i class="fas fa-print"></i>
                                                <span>Cetak</span>
                                            </a>

                                            <!-- Tombol Update -->
                                            <a href="index.php?page=surat-kematian-edit&id=<?= $data['id_surat']; ?>"
                                                class="btn btn-sm d-flex flex-column align-items-center justify-content-center action-btn btn-update-modern"
                                                title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                                <span>Edit</span>
                                            </a>

                                            <!-- Tombol Delete -->
                                            <a href="pages/surat_kematian/proses_hapus.php?id=<?= $data['id_surat']; ?>"
                                                class="btn btn-sm d-flex flex-column align-items-center justify-content-center action-btn btn-delete-modern"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data surat kematian ini?');"
                                                title="Hapus">
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
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Belum ada data surat keterangan
                                    kematian.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>