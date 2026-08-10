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

require_once __DIR__ . '/../../koneksi.php';

// Ambil ID Data yang akan diedit
$id_waris = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query mengambil data utama surat
$query_surat = mysqli_query($koneksi, "SELECT * FROM tb_surat_waris WHERE id_waris = $id_waris");
$data = mysqli_fetch_assoc($query_surat);

if (!$data) {
    echo "<div class='alert alert-warning m-3'>Data arsip surat waris tidak ditemukan!</div>";
    exit;
}

// Ambil data detail anak
$query_anak = mysqli_query($koneksi, "SELECT * FROM tb_waris_detail_anak WHERE id_waris = $id_waris ORDER BY id_detail_anak ASC");

// Ambil data detail saksi
$query_saksi = mysqli_query($koneksi, "SELECT * FROM tb_waris_detail_saksi WHERE id_waris = $id_waris ORDER BY id_detail_saksi ASC");

// Ambil data pejabat untuk pilihan penandatangan
$query_pejabat = mysqli_query($koneksi, "SELECT id_pejabat, nama_pejabat, jabatan FROM tb_pejabat ORDER BY nama_pejabat ASC");
?>

<style>
    .page-title-modern {
        font-weight: 700;
        color: #2c3e50;
        letter-spacing: -0.5px;
    }

    .card-modern {
        border: none !important;
        border-radius: 15px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05) !important;
    }

    .card-header-modern {
        background-color: #ffffff !important;
        border-bottom: 1px solid #f1f3f5 !important;
        padding: 1.25rem 1.5rem !important;
        font-weight: 600;
        color: #495057;
        font-size: 1.1rem;
    }

    .section-title {
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #0d6efd;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 5px;
    }

    .btn-custom {
        border-radius: 8px !important;
        font-weight: 600;
        padding: 10px 20px;
        transition: all 0.25s ease;
    }

    .btn-custom:hover {
        transform: translateY(-2px);
    }

    .dynamic-row {
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container-fluid px-4 py-3">
    <div class="mb-4">
        <h3 class="page-title-modern mt-2 mb-1">Ubah Data Keterangan Ahli Waris</h3>
        <ol class="breadcrumb breadcrumb-modern mb-0">
            <li class="breadcrumb-item"><a href="index.php?page=dashboard" class="text-decoration-none">Dashboard</a>
            </li>
            <li class="breadcrumb-item"><a href="index.php?page=surat-waris" class="text-decoration-none">Daftar Surat
                    Waris</a></li>
            <li class="breadcrumb-item active">Ubah Data</li>
        </ol>
    </div>

    <div class="card card-modern mb-4">
        <div class="card-header-modern d-flex align-items-center">
            <i class="fas fa-edit me-2 text-warning"></i> Perbarui Formulir Surat Keterangan Waris
        </div>
        <div class="card-body p-4">
            <form action="pages/surat_ahli_waris/proses_edit.php" method="POST">
                <!-- ID Tersembunyi -->
                <input type="hidden" name="id_waris" value="<?= $data['id_waris']; ?>">

                <!-- 1. DATA ADMINISTRASI SURAT -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h5 class="section-title">1. Administrasi Surat</h5>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nomor Surat</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-secondary fw-bold">470 /</span>
                            <input type="text" name="nomor_surat" class="form-control"
                                placeholder="Contoh: 125 /31.07.16/2026"
                                value="<?= htmlspecialchars($data['nomor_surat']); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" class="form-control"
                            value="<?= $data['tanggal_surat']; ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Keperluan / Keterangan Tambahan <span
                                class="text-muted">(Opsional)</span></label>
                        <input type="text" name="keperluan" class="form-control"
                            placeholder="Contoh: Untuk Pengurus BPJS Ketenagakerjaan"
                            value="<?= htmlspecialchars($data['keperluan']); ?>">
                    </div>
                </div>

                <!-- 2. DATA ALMARHUM / PEWARIS -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h5 class="section-title">2. Data Mendiang (Almarhum/ah)</h5>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama Almarhum / Almarhumah</label>
                        <input type="text" name="nama_almarhum" class="form-control" placeholder="Nama Lengkap"
                            value="<?= htmlspecialchars($data['nama_almarhum']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Bin / Binti <span
                                class="text-muted">(Opsional)</span></label>
                        <input type="text" name="bin_binti" class="form-control" placeholder="Nama Ayah Kandung"
                            value="<?= htmlspecialchars($data['bin_binti']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Tanggal Meninggal</label>
                        <input type="date" name="tanggal_meninggal" class="form-control"
                            value="<?= $data['tanggal_meninggal']; ?>" required>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Tempat Meninggal</label>
                        <input type="text" name="tempat_meninggal" class="form-control"
                            placeholder="Contoh: desa Berugenjang Rt 001 Rw 001 Undaan Kudus"
                            value="<?= htmlspecialchars($data['tempat_meninggal']); ?>" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Alamat Tempat Tinggal Terakhir</label>
                        <textarea name="alamat_terakhir" class="form-control" rows="2"
                            placeholder="Alamat lengkap domisili terakhir mendiang"
                            required><?= htmlspecialchars($data['alamat_terakhir']); ?></textarea>
                    </div>
                </div>

                <!-- 3. DATA PASANGAN -->
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h5 class="section-title">3. Data Istri / Suami</h5>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Nama Istri / Suami <span class="text-muted">(Kosongkan
                                jika tidak ada)</span></label>
                        <input type="text" name="nama_pasangan" class="form-control" placeholder="Nama Lengkap Pasangan"
                            value="<?= htmlspecialchars($data['nama_pasangan']); ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status Keberadaan</label>
                        <select name="status_pasangan" class="form-select">
                            <option value="Hidup" <?= $data['status_pasangan'] == 'Hidup' ? 'selected' : ''; ?>>Masih
                                Hidup</option>
                            <option value="Alm" <?= $data['status_pasangan'] == 'Alm' ? 'selected' : ''; ?>>Sudah
                                Meninggal Dunia (Alm)</option>
                        </select>
                    </div>
                </div>

                <!-- 4. DATA AHLI WARIS (ANAK KANDUNG) DYNAMIC FORM -->
                <div class="row mb-4">
                    <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                        <h5 class="section-title m-0 flex-grow-1">4. Daftar Anak Kandung / Ahli Waris</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary fw-bold"
                            onclick="tambahBarisAnak()">
                            <i class="fas fa-plus me-1"></i> Tambah Baris Anak
                        </button>
                    </div>

                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="tabel_anak">
                                <thead class="table-light text-center" style="font-size:0.85rem;">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="35%">Nama Lengkap Anak</th>
                                        <th width="25%">Pekerjaan</th>
                                        <th width="25%">Tempat Tinggal / Berumah Di</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="container_anak">
                                    <?php
                                    $idx_anak = 1;
                                    if (mysqli_num_rows($query_anak) > 0) {
                                        while ($anak = mysqli_fetch_assoc($query_anak)) {
                                            ?>
                                            <tr class="dynamic-row">
                                                <td class="text-center fw-bold nomor-anak"><?= $idx_anak; ?></td>
                                                <td><input type="text" name="nama_anak[]" class="form-control form-control-sm"
                                                        value="<?= htmlspecialchars($anak['nama_anak']); ?>" required></td>
                                                <td><input type="text" name="pekerjaan_anak[]"
                                                        class="form-control form-control-sm"
                                                        value="<?= htmlspecialchars($anak['pekerjaan']); ?>" required></td>
                                                <td><input type="text" name="alamat_anak[]" class="form-control form-control-sm"
                                                        value="<?= htmlspecialchars($anak['alamat_tinggal']); ?>" required></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="hapusBarisAnak(this)"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            <?php
                                            $idx_anak++;
                                        }
                                    } else {
                                        ?>
                                        <!-- Jika data anak kosong secara tidak sengaja -->
                                        <tr class="dynamic-row">
                                            <td class="text-center fw-bold nomor-anak">1</td>
                                            <td><input type="text" name="nama_anak[]" class="form-control form-control-sm"
                                                    placeholder="Nama Anak ke-1" required></td>
                                            <td><input type="text" name="pekerjaan_anak[]"
                                                    class="form-control form-control-sm"
                                                    placeholder="Contoh: Petani/Perkebun" required></td>
                                            <td><input type="text" name="alamat_anak[]" class="form-control form-control-sm"
                                                    placeholder="Contoh: Berugenjang" required></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger disabled"><i
                                                        class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 5. DATA SAKSI-SAKSI DYNAMIC FORM -->
                <div class="row mb-4">
                    <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                        <h5 class="section-title m-0 flex-grow-1">5. Saksi-Saksi Waris</h5>
                        <button type="button" class="btn btn-sm btn-outline-secondary fw-bold"
                            onclick="tambahBarisSaksi()">
                            <i class="fas fa-plus me-1"></i> Tambah Baris Saksi
                        </button>
                    </div>

                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="tabel_saksi">
                                <thead class="table-light text-center" style="font-size:0.85rem;">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="35%">Nama Lengkap Saksi</th>
                                        <th width="25%">Pekerjaan</th>
                                        <th width="25%">Alamat Rumah Saksi</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="container_saksi">
                                    <?php
                                    $idx_saksi = 1;
                                    if (mysqli_num_rows($query_saksi) > 0) {
                                        while ($saksi = mysqli_fetch_assoc($query_saksi)) {
                                            ?>
                                            <tr class="dynamic-row">
                                                <td class="text-center fw-bold nomor-saksi"><?= $idx_saksi; ?></td>
                                                <td><input type="text" name="nama_saksi[]" class="form-control form-control-sm"
                                                        value="<?= htmlspecialchars($saksi['nama_saksi']); ?>" required></td>
                                                <td><input type="text" name="pekerjaan_saksi[]"
                                                        class="form-control form-control-sm"
                                                        value="<?= htmlspecialchars($saksi['pekerjaan']); ?>" required></td>
                                                <td><input type="text" name="alamat_saksi[]"
                                                        class="form-control form-control-sm"
                                                        value="<?= htmlspecialchars($saksi['alamat_saksi']); ?>" required></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="hapusBarisSaksi(this)"><i class="fas fa-trash"></i></button>
                                                </td>
                                            </tr>
                                            <?php
                                            $idx_saksi++;
                                        }
                                    } else {
                                        ?>
                                        <tr class="dynamic-row">
                                            <td class="text-center fw-bold nomor-saksi">1</td>
                                            <td><input type="text" name="nama_saksi[]" class="form-control form-control-sm"
                                                    placeholder="Nama Saksi ke-1" required></td>
                                            <td><input type="text" name="pekerjaan_saksi[]"
                                                    class="form-control form-control-sm"
                                                    placeholder="Contoh: Perangkat Desa" required></td>
                                            <td><input type="text" name="alamat_saksi[]"
                                                    class="form-control form-control-sm" placeholder="Alamat Saksi"
                                                    required></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger disabled"><i
                                                        class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 6. PENANDATANGAN (PEJABAT) -->
                <div class="row g-3 mb-5">
                    <div class="col-12">
                        <h5 class="section-title">6. Penandatanganan</h5>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Pemerintah Desa (Mengetahui/Dibenarkan)</label>
                        <select name="id_pejabat" class="form-select" required>
                            <option value="">-- Pilih Pejabat Desa --</option>
                            <?php while ($pejabat = mysqli_fetch_assoc($query_pejabat)): ?>
                                <option value="<?= $pejabat['id_pejabat']; ?>"
                                    <?= $pejabat['id_pejabat'] == $data['id_pejabat'] ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($pejabat['nama_pejabat']); ?>
                                    (<?= htmlspecialchars($pejabat['jabatan']); ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kecamatan (Dikuatkan Oleh)</label>
                        <input type="text" name="nama_camat" class="form-control"
                            value="<?= htmlspecialchars($data['nama_camat']); ?>">
                    </div>
                </div>

                <hr class="my-4">

                <!-- BUTTON AKSI -->
                <div class="d-flex justify-content-end gap-3">
                    <a href="index.php?page=surat-waris" class="btn btn-light btn-custom text-secondary border">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-warning btn-custom text-white shadow">
                        <i class="fas fa-save me-1"></i> Perbarui Data Arsip
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- JAVASCRIPT UNTUK FIELD DINAMIS -->
<script>
    // Fungsi Tambah Baris Anak
    function tambahBarisAnak() {
        const container = document.getElementById('container_anak');
        const index = container.children.length + 1;

        const row = document.createElement('tr');
        row.className = 'dynamic-row';
        row.innerHTML = `
        <td class="text-center fw-bold nomor-anak">${index}</td>
        <td><input type="text" name="nama_anak[]" class="form-control form-control-sm" placeholder="Nama Anak ke-${index}" required></td>
        <td><input type="text" name="pekerjaan_anak[]" class="form-control form-control-sm" placeholder="Contoh: Petani/Perkebun" required></td>
        <td><input type="text" name="alamat_anak[]" class="form-control form-control-sm" placeholder="Contoh: Berugenjang" required></td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger" onclick="hapusBarisAnak(this)"><i class="fas fa-trash"></i></button>
        </td>
    `;
        container.appendChild(row);
        urutkanNomorAnak();
    }

    function hapusBarisAnak(btn) {
        btn.closest('tr').remove();
        urutkanNomorAnak();
    }

    function urutkanNomorAnak() {
        const nomorElements = document.querySelectorAll('.nomor-anak');
        nomorElements.forEach((el, index) => {
            el.innerText = index + 1;
        });
    }

    // Fungsi Tambah Baris Saksi
    function tambahBarisSaksi() {
        const container = document.getElementById('container_saksi');
        const index = container.children.length + 1;

        const row = document.createElement('tr');
        row.className = 'dynamic-row';
        row.innerHTML = `
        <td class="text-center fw-bold nomor-saksi">${index}</td>
        <td><input type="text" name="nama_saksi[]" class="form-control form-control-sm" placeholder="Nama Saksi ke-${index}" required></td>
        <td><input type="text" name="pekerjaan_saksi[]" class="form-control form-control-sm" placeholder="Contoh: Perangkat Desa" required></td>
        <td><input type="text" name="alamat_saksi[]" class="form-control form-control-sm" placeholder="Alamat Saksi" required></td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-danger" onclick="hapusBarisSaksi(this)"><i class="fas fa-trash"></i></button>
        </td>
    `;
        container.appendChild(row);
        urutkanNomorSaksi();
    }

    function hapusBarisSaksi(btn) {
        btn.closest('tr').remove();
        urutkanNomorSaksi();
    }

    function urutkanNomorSaksi() {
        const nomorElements = document.querySelectorAll('.nomor-saksi');
        nomorElements.forEach((el, index) => {
            el.innerText = index + 1;
        });
    }
</script>