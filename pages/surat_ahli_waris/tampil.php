<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek hak akses admin
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<div class='alert alert-danger m-3'>Akses ditolak! Anda tidak memiliki hak akses ke halaman ini.</div>";
    exit;
}

$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");

// Ambil data surat utama serta hitung total anak (ahli waris) secara bersamaan
$query = mysqli_query($koneksi, "
    SELECT s.*, 
    (SELECT COUNT(id_detail_anak) FROM tb_waris_detail_anak WHERE id_waris = s.id_waris) AS jumlah_anak
    FROM tb_surat_waris s 
    ORDER BY s.id_waris DESC
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
            <h3 class="page-title-modern mt-2 mb-1">Surat Keterangan Ahli Waris</h3>
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Daftar Surat Waris</li>
            </ol>
        </div>
    </div>

    <div class="my-4"></div>

    <div class="card card-modern mb-4">
        <div class="card-header-modern d-flex justify-content-between align-items-center">
            <div class="card-header-title d-flex align-items-center">
                <i class="fas fa-users me-2 text-primary"></i> Data Ahli Waris Penduduk Desa
            </div>
            <a href="index.php?page=surat-waris-tambah" class="btn btn-primary btn-tambah-modern text-white">
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
                            <th width="23%">Identitas Almarhum</th>
                            <th width="20%">Istri / Suami</th>
                            <th width="17%" class="text-center">Jml Ahli Waris</th>
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
                            <td>
                                <span class="fw-semibold text-dark">
                                    <?= htmlspecialchars($data['nomor_surat']); ?></span>
                                <?php if (!empty($data['keperluan'])): ?>
                                <small class="text-muted d-block mt-1"><i
                                        class="fas fa-info-circle me-1 text-info"></i><?= htmlspecialchars($data['keperluan']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span
                                    class="fw-bold text-uppercase text-danger"><?= htmlspecialchars($data['nama_almarhum']); ?></span>
                                <?php if (!empty($data['bin_binti'])): ?>
                                <small class="text-muted d-block">Bin/Binti:
                                    <?= htmlspecialchars($data['bin_binti']); ?></small>
                                <?php endif; ?>
                                <small class="text-muted d-block mt-1">Wafat:
                                    <?= date('d-m-Y', strtotime($data['tanggal_meninggal'])); ?></small>
                            </td>
                            <td>
                                <?php if (!empty($data['nama_pasangan'])): ?>
                                <span class="fw-semibold"><?= htmlspecialchars($data['nama_pasangan']); ?></span>
                                <small
                                    class="badge <?= $data['status_pasangan'] == 'Alm' ? 'bg-secondary' : 'bg-success'; ?> d-block w-50 mt-1 text-center">
                                    <?= $data['status_pasangan'] == 'Alm' ? 'Almarhum/ah' : 'Hidup'; ?>
                                </small>
                                <?php else: ?>
                                <span class="text-muted-italic">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary p-2"><?= $data['jumlah_anak']; ?> Orang</span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <!-- Cetak -->
                                    <a href="pages/surat_ahli_waris/cetak.php?id=<?= $data['id_waris']; ?>"
                                        target="_blank"
                                        class="btn btn-sm d-flex flex-column align-items-center justify-content-center action-btn btn-cetak-modern"
                                        title="Cetak Surat">
                                        <i class="fas fa-print"></i>
                                        <span>Cetak</span>
                                    </a>
                                    <!-- Edit -->
                                    <a href="index.php?page=surat-waris-edit&id=<?= $data['id_waris']; ?>"
                                        class="btn btn-sm d-flex flex-column align-items-center justify-content-center action-btn btn-update-modern"
                                        title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </a>
                                    <!-- Delete -->
                                    <a href="pages/surat_ahli_waris/proses_hapus.php?id=<?= $data['id_waris']; ?>"
                                        class="btn btn-sm d-flex flex-column align-items-center justify-content-center action-btn btn-delete-modern"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data surat ahli waris ini beserta seluruh rincian anak & saksinya?');"
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
                            <td colspan="6" class="text-center text-muted py-4">Belum ada data surat keterangan ahli
                                waris.</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>