<?php
include 'koneksi.php';

// Ambil data surat domisili beserta nama pejabat penandatangan
$query = mysqli_query($koneksi, "SELECT d.*, p.nama_pejabat 
                                 FROM tb_surat_domisili d 
                                 JOIN tb_pejabat p ON d.id_pejabat = p.id_pejabat 
                                 ORDER BY d.id_domisili DESC");
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
    color: #17a2b8;
    font-weight: 500;
}

.card-modern {
    border: none !important;
    border-radius: 12px !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
    overflow: hidden;
}

.card-header-modern {
    background-color: #ffffff !important;
    border-bottom: 1px solid #f1f3f5 !important;
    padding: 1rem 1.25rem !important;
}

.card-header-title {
    font-weight: 600;
    color: #495057;
    font-size: 1.05rem;
}

.btn-tambah-modern {
    background: linear-gradient(135deg, #0d6efd, #0056b3) !important;
    border: none !important;
    border-radius: 6px !important;
    padding: 7px 14px !important;
    font-weight: 600;
    font-size: 0.9rem;
    box-shadow: 0 3px 8px rgba(13, 110, 253, 0.15);
    transition: all 0.2s ease;
    text-decoration: none;
    color: #ffffff !important;
}

.btn-tambah-modern:hover {
    transform: translateY(-1px);
    box-shadow: 0 5px 12px rgba(13, 110, 253, 0.25);
}

/* --- GAYA TOMBOL AKSI GAMBAR 2 (KOTAK & ICON DI ATAS) --- */
.action-btn {
    display: inline-flex;
    flex-direction: column;
    /* Mengubah posisi ikon ke atas teks */
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    font-size: 10px !important;
    font-weight: 500;
    color: #ffffff !important;
    border-radius: 8px !important;
    text-decoration: none;
    border: none !important;
    transition: all 0.2s ease;
    line-height: 1.1;
    padding: 0;
}

.action-btn i {
    font-size: 14px;
    margin-bottom: 3px;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
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
    color: #ffffff !important;
    /* Teks dan ikon putih sesuai Gambar 2 */
}

.btn-update-modern:hover {
    background-color: #e0a800 !important;
    color: #ffffff !important;
}

.btn-delete-modern {
    background-color: #dc3545 !important;
}

.btn-delete-modern:hover {
    background-color: #bd2130 !important;
}

#dataTable td {
    vertical-align: middle !important;
    padding: 12px 10px;
}

#dataTable th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
    padding: 12px 10px;
}
</style>

<div class="container-fluid px-3 py-3">
    <!-- Header Halaman -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title-modern mb-1">Surat Keterangan Domisili</h3>
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Daftar Surat Domisili</li>
            </ol>
        </div>
    </div>

    <!-- Card Tabel Data -->
    <div class="card card-modern mb-4">
        <div class="card-header-modern d-flex justify-content-between align-items-center">
            <div class="card-header-title d-flex align-items-center">
                <i class="fas fa-table me-2 text-primary"></i> Data Surat Keterangan Domisili
            </div>
            <a href="index.php?page=surat-domisili-tambah" class="btn btn-primary btn-tambah-modern">
                <i class="fas fa-plus me-1"></i> Tambah Surat Baru
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0" id="dataTable" width="100%"
                    cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="15%">No. Surat</th>
                            <th width="20%">Nama Warga / NIK</th>
                            <th width="25%">Keperluan</th>
                            <th width="15%">Penandatangan</th>
                            <th width="20%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($row = mysqli_fetch_assoc($query)) {
                                ?>
                        <tr>
                            <td class="text-center fw-bold text-secondary"><?= $no++; ?></td>
                            <td class="fw-semibold text-dark"><?= htmlspecialchars($row['nomor_surat']); ?></td>
                            <td>
                                <strong><?= htmlspecialchars($row['nama_warga']); ?></strong><br>
                                <small class="text-muted">NIK: <?= htmlspecialchars($row['nik']); ?></small>
                            </td>
                            <td><?= htmlspecialchars($row['keperluan']); ?></td>
                            <td><?= htmlspecialchars($row['nama_pejabat']); ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <!-- Tombol Cetak -->
                                    <a href="pages/surat_domisili/cetak.php?id=<?= $row['id_domisili']; ?>"
                                        target="_blank" class="btn action-btn btn-cetak-modern" title="Cetak Surat">
                                        <i class="fas fa-print"></i>
                                        <span>Cetak</span>
                                    </a>
                                    <!-- Tombol Edit -->
                                    <a href="index.php?page=surat-domisili-edit&id=<?= $row['id_domisili']; ?>"
                                        class="btn action-btn btn-update-modern" title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </a>
                                    <!-- Tombol Delete -->
                                    <a href="index.php?page=surat-domisili-hapus&id=<?= $row['id_domisili']; ?>"
                                        class="btn action-btn btn-delete-modern"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus surat ini?');"
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
                            <td colspan="6" class="text-center text-muted py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <i class="fas fa-folder-open fa-3x mb-3 text-secondary" style="opacity: 0.4;"></i>
                                    <span>Belum ada arsip surat keterangan domisili.</span>
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