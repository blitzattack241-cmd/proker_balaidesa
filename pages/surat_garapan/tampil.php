<?php
require_once __DIR__ . '/../../koneksi.php';

// Ambil data surat utama serta hitung total rincian sawah (SUM) secara bersamaan
$query = mysqli_query($koneksi, "
    SELECT g.*, 
    (SELECT SUM(luas_m2) FROM tb_surat_garapan_detail WHERE id_garapan = g.id_garapan) AS total_luas,
    (SELECT COUNT(id_detail) FROM tb_surat_garapan_detail WHERE id_garapan = g.id_garapan) AS jumlah_sawah
    FROM tb_surat_garapan g 
    ORDER BY g.id_garapan DESC
");
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
        overflow: hidden;
    }

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
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h3 class="page-title-modern mt-2 mb-1">Surat Keterangan Garapan Sawah</h3>
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Daftar Surat Garapan</li>
            </ol>
        </div>
    </div>

    <div class="my-4"></div>

    <div class="card card-modern mb-4">
        <div class="card-header-modern d-flex justify-content-between align-items-center">
            <div class="card-header-title d-flex align-items-center">
                <i class="fas fa-table me-2 text-primary"></i> Data Penggarap Sawah Desa
            </div>
            <a href="index.php?page=surat-garapan-tambah" class="btn btn-primary btn-tambah-modern text-white">
                <i class="fas fa-plus me-1"></i> Buat Surat Baru
            </a>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="20%">No. Surat</th>
                            <th width="20%">Nama Penggarap</th>
                            <th width="15%">Pekerjaan</th>
                            <th width="12%" class="text-center">Jml Sawah</th>
                            <th width="13%" class="text-center">Total Luas</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($data = mysqli_fetch_assoc($query)) {
                                ?>
                                <tr>
                                    <td class="text-center fw-bold text-secondary"><?= $no++; ?></td>
                                    <td class="fw-semibold text-dark"><?= htmlspecialchars($data['nomor_surat']); ?></td>
                                    <td>
                                        <span class="fw-bold"><?= htmlspecialchars($data['nama_penggarap']); ?></span>
                                        <?php if (!empty($data['bin_binti_penggarap'])): ?>
                                            <small class="text-muted d-block">Bin
                                                <?= htmlspecialchars($data['bin_binti_penggarap']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($data['pekerjaan']); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary p-2"><?= $data['jumlah_sawah']; ?> Petak</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge bg-success p-2"><?= number_format($data['total_luas'], 0, ',', '.'); ?>
                                            M²</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <!-- Cetak -->
                                            <a href="pages/surat_garapan/cetak.php?id=<?= $data['id_garapan']; ?>"
                                                target="_blank"
                                                class="btn btn-sm d-flex flex-column align-items-center justify-content-center action-btn btn-cetak-modern"
                                                title="Cetak Surat">
                                                <i class="fas fa-print"></i>
                                                <span>Cetak</span>
                                            </a>
                                            <!-- Edit -->
                                            <a href="index.php?page=surat-garapan-edit&id=<?= $data['id_garapan']; ?>"
                                                class="btn btn-sm d-flex flex-column align-items-center justify-content-center action-btn btn-update-modern"
                                                title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                                <span>Edit</span>
                                            </a>
                                            <!-- Delete -->
                                            <a href="pages/surat_garapan/proses_hapus.php?id=<?= $data['id_garapan']; ?>"
                                                class="btn btn-sm d-flex flex-column align-items-center justify-content-center action-btn btn-delete-modern"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data surat garapan ini beserta seluruh rincian sawahnya?');"
                                                title="Hapus">
                                                <i class="fas fa-trash"></i>
                                                <span>Hapus</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada data surat garapan sawah.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>