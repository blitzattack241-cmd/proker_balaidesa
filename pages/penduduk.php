<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4 shadow-sm rounded-3'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    return;
}

// Pastikan tabel tb_penduduk sudah ada
$createTableQuery = "CREATE TABLE IF NOT EXISTS `tb_penduduk` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nik` VARCHAR(20) NOT NULL UNIQUE,
  `no_kk` VARCHAR(20) DEFAULT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `jenis_kelamin` VARCHAR(20) DEFAULT NULL,
  `tempat_tgl_lahir` VARCHAR(100) DEFAULT NULL,
  `umur` INT DEFAULT NULL,
  `agama` VARCHAR(30) DEFAULT NULL,
  `pekerjaan` VARCHAR(100) DEFAULT NULL,
  `alamat` TEXT DEFAULT NULL,
  `rt` VARCHAR(5) DEFAULT NULL,
  `rw` VARCHAR(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
mysqli_query($koneksi, $createTableQuery);

// Logika Hapus Data Per Baris
if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    mysqli_query($koneksi, "DELETE FROM tb_penduduk WHERE id = $id");
    echo "<script>alert('Data berhasil dihapus!'); window.location='index.php?page=penduduk';</script>";
    exit();
}

// Logika Hapus SELURUH Data Penduduk (Reset Tabel)
if (isset($_GET['action']) && $_GET['action'] == 'hapus_semua') {
    mysqli_query($koneksi, "TRUNCATE TABLE tb_penduduk");
    echo "<script>alert('Seluruh data penduduk berhasil dikosongkan!'); window.location='index.php?page=penduduk';</script>";
    exit();
}

// Filter Pencarian
$search = isset($_GET['search']) ? mysqli_real_escape_string($koneksi, trim($_GET['search'])) : '';
$where = "";
if (!empty($search)) {
    $where = "WHERE nama LIKE '%$search%' OR nik LIKE '%$search%' OR no_kk LIKE '%$search%' OR pekerjaan LIKE '%$search%'";
}

// Query Ambil Data
$query = "SELECT * FROM tb_penduduk $where ORDER BY id DESC";
$result = mysqli_query($koneksi, $query);
$totalData = mysqli_num_rows($result);
?>

<style>
/* Modern UI Styles */
:root {
    --primary-color: #3b82f6;
    --primary-hover: #2563eb;
    --bg-card: #ffffff;
    --border-color: #e2e8f0;
    --text-main: #1e293b;
    --text-muted: #64748b;
}

body {
    background-color: #f8fafc;
}

.main-card {
    background: var(--bg-card);
    border-radius: 12px;
    border: 1px solid var(--border-color);
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    padding: 1.25rem;
}

/* Custom Table Styling */
.table-custom-wrapper {
    border-radius: 10px;
    overflow: hidden;
}

.table-custom {
    margin-bottom: 0;
}

.table-custom thead th {
    background-color: #f1f5f9;
    color: #475569;
    font-weight: 600;
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 12px 14px;
    border-bottom: 1px solid var(--border-color);
}

.table-custom tbody tr {
    transition: all 0.2s ease;
}

.table-custom tbody tr:hover {
    background-color: #f8fafc;
}

.table-custom tbody td {
    padding: 12px 14px;
    font-size: 0.875rem;
    color: var(--text-main);
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

/* Specific Field Formatting */
.font-mono {
    font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
    letter-spacing: -0.02em;
}

.badge-gender-l {
    background-color: #e0f2fe;
    color: #0369a1;
    font-weight: 600;
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 0.75rem;
}

.badge-gender-p {
    background-color: #fce7f3;
    color: #be185d;
    font-weight: 600;
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-size: 0.75rem;
}

/* Action Buttons */
.btn-action {
    width: 32px;
    height: 32px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.btn-action-edit {
    color: #d97706;
    background-color: #fef3c7;
    border: none;
}

.btn-action-edit:hover {
    background-color: #fde68a;
    color: #b45309;
}

.btn-action-delete {
    color: #dc2626;
    background-color: #fee2e2;
    border: none;
}

.btn-action-delete:hover {
    background-color: #fca5a5;
    color: #991b1b;
}

/* Stat & Search Input */
.search-input-group .input-group-text {
    background-color: #ffffff;
    border-color: var(--border-color);
    color: #94a3b8;
}

.search-input-group .form-control {
    border-color: var(--border-color);
}

.search-input-group .form-control:focus {
    box-shadow: none;
    border-color: var(--primary-color);
}

.total-badge {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 8px;
}
</style>

<div class="container-fluid px-4 py-4">

    <!-- Title Header & Actions -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">Data Penduduk</h3>
            <p class="text-muted mb-0 small">Manajemen & Klasifikasi Data Penduduk Desa Berugenjang</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <!-- Tombol Hapus Semua Data -->
            <a href="index.php?page=penduduk&action=hapus_semua"
                class="btn btn-outline-danger rounded-3 fw-semibold btn-sm px-3 py-2"
                onclick="return confirm('Apakah Anda YAKIN ingin menghapus SELURUH data penduduk? Tindakan ini tidak dapat dibatalkan!');">
                <i class="fas fa-trash-alt me-1"></i> Hapus Semua Data
            </a>

            <!-- Tombol Modal Import Excel -->
            <button class="btn btn-success rounded-3 fw-semibold btn-sm px-3 py-2" data-bs-toggle="modal"
                data-bs-target="#modalImport">
                <i class="fas fa-file-excel me-1"></i> Import Excel / CSV
            </button>

            <!-- Tombol Tambah Penduduk -->
            <a href="index.php?page=tambah-penduduk" class="btn btn-primary rounded-3 fw-semibold btn-sm px-3 py-2">
                <i class="fas fa-plus me-1"></i> Tambah Penduduk
            </a>
        </div>
    </div>

    <!-- Form Pencarian & Informasi Total -->
    <div class="main-card mb-4">
        <form method="GET" action="index.php" class="row g-3 align-items-center">
            <input type="hidden" name="page" value="penduduk">
            <div class="col-md-8 col-lg-9">
                <div class="input-group search-input-group">
                    <span class="input-group-text border-end-0"><i class="fas fa-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                        placeholder="Cari berdasarkan Nama, NIK, No. KK, atau Pekerjaan..."
                        value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-primary px-4 fw-semibold">Cari</button>
                    <?php if (!empty($search)): ?>
                    <a href="index.php?page=penduduk" class="btn btn-outline-secondary px-3" title="Reset"><i
                            class="fas fa-times"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-4 col-lg-3 text-md-end">
                <span class="total-badge d-inline-block">
                    <i class="fas fa-users me-1"></i> Total: <strong><?php echo number_format($totalData); ?></strong>
                    Penduduk
                </span>
            </div>
        </form>
    </div>

    <!-- Tabel Data Penduduk -->
    <div class="main-card p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-custom align-middle">
                <thead>
                    <tr>
                        <th style="width: 4%;" class="text-center">NO</th>
                        <th style="width: 15%;">NIK</th>
                        <th style="width: 14%;">NO. KK</th>
                        <th style="width: 18%;">NAMA LENGKAP</th>
                        <th style="width: 6%;" class="text-center">JK</th>
                        <th style="width: 18%;">TEMPAT, TGL LAHIR</th>
                        <th style="width: 13%;">PEKERJAAN</th>
                        <th style="width: 12%;" class="text-center">ALAMAT</th>
                        <th style="width: 10%;" class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($totalData > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td class="text-center text-muted small fw-semibold"><?php echo $no++; ?></td>
                        <td><span
                                class="fw-bold font-mono text-dark"><?php echo htmlspecialchars($row['nik']); ?></span>
                        </td>
                        <td><span
                                class="font-mono text-secondary"><?php echo htmlspecialchars($row['no_kk'] ?: '-'); ?></span>
                        </td>
                        <td><strong class="text-dark"><?php echo htmlspecialchars($row['nama']); ?></strong></td>
                        <td class="text-center">
                            <?php if (strtoupper(substr($row['jenis_kelamin'] ?? 'L', 0, 1)) === 'L'): ?>
                            <span class="badge-gender-l" title="Laki-laki">L</span>
                            <?php else: ?>
                            <span class="badge-gender-p" title="Perempuan">P</span>
                            <?php endif; ?>
                        </td>
                        <td><span
                                class="text-secondary"><?php echo htmlspecialchars($row['tempat_tgl_lahir'] ?: '-'); ?></span>
                        </td>
                        <td><span
                                class="text-secondary"><?php echo htmlspecialchars($row['pekerjaan'] ?: '-'); ?></span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border">
                                RT <?php echo htmlspecialchars($row['rt'] ?: '0'); ?> / RW
                                <?php echo htmlspecialchars($row['rw'] ?: '0'); ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-inline-flex gap-1">
                                <a href="index.php?page=edit-penduduk&id=<?php echo $row['id']; ?>"
                                    class="btn-action btn-action-edit" title="Edit Data">
                                    <i class="fas fa-pencil-alt fs-7"></i>
                                </a>
                                <a href="index.php?page=penduduk&action=hapus&id=<?php echo $row['id']; ?>"
                                    class="btn-action btn-action-delete"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');"
                                    title="Hapus Data">
                                    <i class="fas fa-trash-alt fs-7"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="py-3">
                                <i class="fas fa-folder-open text-muted opacity-50 mb-3" style="font-size: 3rem;"></i>
                                <h6 class="fw-semibold text-dark mb-1">Data Penduduk Tidak Ditemukan</h6>
                                <p class="text-muted small mb-0">Belum ada data yang tersimpan atau hasil pencarian
                                    tidak sesuai.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Import Data Excel -->
<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="pages/proses_import.php" method="POST" enctype="multipart/form-data">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="modalImportLabel">
                        <i class="fas fa-file-excel text-success me-2"></i>Import Data Penduduk
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <p class="text-muted small mb-3">
                        Pilih file Spreadsheet (<code>.xlsx</code> / <code>.xls</code>) atau File CSV
                        (<code>.csv</code>) yang berisi format data kependudukan desa.
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-dark">Pilih File Import</label>
                        <input type="file" name="file_excel" class="form-control" accept=".xlsx, .xls, .csv" required>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="import" class="btn btn-success px-4 fw-semibold">
                        <i class="fas fa-upload me-1"></i> Upload & Proses
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>