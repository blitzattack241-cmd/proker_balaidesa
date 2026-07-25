<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");

// Ambil data SKTM Bumil relasi dengan tabel pejabat penandatangan
$query = mysqli_query($koneksi, "
    SELECT s.*, p.nama_pejabat 
    FROM tb_sktm_bumil s 
    LEFT JOIN tb_pejabat p ON s.id_pejabat = p.id_pejabat 
    ORDER BY s.id_sktm DESC
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

.foto-badge-container {
    display: flex;
    gap: 4px;
    justify-content: center;
    flex-wrap: wrap;
    max-width: 150px;
    margin: 0 auto;
}

.badge-foto {
    font-size: 9px !important;
    padding: 4px 6px !important;
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
            <h3 class="page-title-modern mt-2 mb-1">SKTM Ibu Hamil (Bumil)</h3>
            <ol class="breadcrumb breadcrumb-modern mb-0">
                <li class="breadcrumb-item"><a href="index.php?page=dashboard"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Daftar SKTM Bumil</li>
            </ol>
        </div>
    </div>

    <div class="my-4"></div>

    <div class="card card-modern mb-4">
        <div class="card-header-modern d-flex justify-content-between align-items-center">
            <div class="card-header-title d-flex align-items-center">
                <i class="fas fa-file-medical me-2 text-primary"></i> Data Arsip SKTM Pembebasan Biaya Persalinan
            </div>
            <a href="index.php?page=sktm-bumil-tambah" class="btn btn-primary btn-tambah-modern text-white">
                <i class="fas fa-plus me-1"></i> Buat Surat Baru
            </a>
        </div>
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle" id="dataTable" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th width="18%">No. Surat</th>
                            <th width="22%">Nama Ibu Hamil</th>
                            <th width="15%">NIK / No. KK</th>
                            <th width="12%" class="text-center">Lampiran Foto</th>
                            <th width="13%" class="text-center">Tgl Surat</th>
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
                                <span
                                    class="fw-bold text-uppercase"><?= htmlspecialchars($data['nama_warga']); ?></span>
                                <small class="text-muted d-block"><?= htmlspecialchars($data['pekerjaan']); ?></small>
                            </td>
                            <td>
                                <small class="d-block"><strong>NIK:</strong>
                                    <?= htmlspecialchars($data['no_ktp']); ?></small>
                                <small class="d-block text-secondary"><strong>KK:</strong>
                                    <?= htmlspecialchars($data['no_kk']); ?></small>
                            </td>
                            <td class="text-center">
                                <div class="foto-badge-container">
                                    <span
                                        class="badge badge-foto <?= !empty($data['foto_depan']) ? 'bg-success' : 'bg-danger'; ?>">Depan</span>
                                    <span
                                        class="badge badge-foto <?= !empty($data['foto_ruang_tamu']) ? 'bg-success' : 'bg-danger'; ?>">Tamu</span>
                                    <span
                                        class="badge badge-foto <?= !empty($data['foto_kamar']) ? 'bg-success' : 'bg-danger'; ?>">Kamar</span>
                                    <span
                                        class="badge badge-foto <?= !empty($data['foto_dapur']) ? 'bg-success' : 'bg-danger'; ?>">Dapur</span>
                                    <span
                                        class="badge badge-foto <?= !empty($data['foto_toilet']) ? 'bg-success' : 'bg-danger'; ?>">WC</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border p-2">
                                    <?= date('d-m-Y', strtotime($data['tanggal_surat'])); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <!-- Cetak -->
                                    <a href="pages/sktm_bumil/cetak.php?id=<?= $data['id_sktm']; ?>" target="_blank"
                                        class="btn btn-sm d-flex flex-column align-items-center justify-content-center action-btn btn-cetak-modern"
                                        title="Cetak Surat">
                                        <i class="fas fa-print"></i>
                                        <span>Cetak</span>
                                    </a>
                                    <!-- Edit -->
                                    <a href="index.php?page=sktm-bumil-edit&id=<?= $data['id_sktm']; ?>"
                                        class="btn btn-sm d-flex flex-column align-items-center justify-content-center action-btn btn-update-modern"
                                        title="Edit Data">
                                        <i class="fas fa-edit"></i>
                                        <span>Edit</span>
                                    </a>
                                    <!-- Delete -->
                                    <a href="pages/sktm_bumil/proses_hapus.php?id=<?= $data['id_sktm']; ?>"
                                        class="btn btn-sm d-flex flex-column align-items-center justify-content-center action-btn btn-delete-modern"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus arsip SKTM Bumil ini beserta seluruh berkas foto rumahnya?');"
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
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data arsip Surat Keterangan
                                Tidak Mampu Bumil.</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>