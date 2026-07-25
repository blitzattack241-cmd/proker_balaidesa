<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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

$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
if (mysqli_connect_errno()) {
    echo "<div class='alert alert-danger m-4'>Koneksi database gagal: " . mysqli_connect_error() . "</div>";
    exit;
}

// 1. Validasi ID Undangan yang akan diedit
if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    echo "<script>
            alert('ID Undangan tidak valid!');
            window.location.href = 'index.php?page=surat-undangan';
          </script>";
    exit;
}

$id_undangan = (int)$_GET['id'];

// 2. Ambil Data Utama Surat Undangan
$query_surat = mysqli_query($koneksi, "SELECT * FROM `tb_surat_undangan` WHERE `id_undangan` = $id_undangan");
$data_surat = mysqli_fetch_assoc($query_surat);

if (!$data_surat) {
    echo "<script>
            alert('Data surat undangan tidak ditemukan!');
            window.location.href = 'index.php?page=surat-undangan';
          </script>";
    exit;
}

// 3. Ambil Data Penerima / Tujuan Undangan
$query_tujuan = mysqli_query($koneksi, "SELECT * FROM `tb_undangan_tujuan` WHERE `id_undangan` = $id_undangan ORDER BY `id_tujuan` ASC");

// 4. Ambil Data Pejabat untuk Dropdown
$query_pejabat = mysqli_query($koneksi, "SELECT * FROM `tb_pejabat` ORDER BY `jabatan` ASC");

// 5. Proses Update Data saat Tombol Disubmit
if (isset($_POST['update'])) {
    $nomor_surat    = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $sifat          = mysqli_real_escape_string($koneksi, $_POST['sifat']);
    $lampiran       = mysqli_real_escape_string($koneksi, $_POST['lampiran']);
    $perihal        = mysqli_real_escape_string($koneksi, $_POST['perihal']);
    $tempat_surat   = mysqli_real_escape_string($koneksi, $_POST['tempat_surat']);
    $tanggal_surat  = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $hari_acara     = mysqli_real_escape_string($koneksi, $_POST['hari_acara']);
    $tanggal_acara  = mysqli_real_escape_string($koneksi, $_POST['tanggal_acara']);
    $jam_acara      = mysqli_real_escape_string($koneksi, $_POST['jam_acara']);
    $tempat_acara   = mysqli_real_escape_string($koneksi, $_POST['tempat_acara']);
    $acara          = mysqli_real_escape_string($koneksi, $_POST['acara']);
    $keterangan     = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $id_pejabat     = (int)$_POST['id_pejabat'];

    // Aktifkan Transaksi Database
    mysqli_begin_transaction($koneksi);

    try {
        // A. Update Data Utama ke `tb_surat_undangan`
        $sql_update_surat = "UPDATE `tb_surat_undangan` SET 
            `nomor_surat` = '$nomor_surat', `sifat` = '$sifat', `lampiran` = '$lampiran', 
            `perihal` = '$perihal', `tempat_surat` = '$tempat_surat', `tanggal_surat` = '$tanggal_surat', 
            `hari_acara` = '$hari_acara', `tanggal_acara` = '$tanggal_acara', `jam_acara` = '$jam_acara', 
            `tempat_acara` = '$tempat_acara', `acara` = '$acara', `keterangan` = '$keterangan', `id_pejabat` = '$id_pejabat' 
            WHERE `id_undangan` = $id_undangan";
        
        if (!mysqli_query($koneksi, $sql_update_surat)) {
            throw new Exception("Gagal memperbarui data utama surat: " . mysqli_error($koneksi));
        }

        // B. Sinkronisasi Data Penerima (Cara terbersih: Hapus penerima lama, lalu insert ulang yang baru dari form)
        $sql_hapus_tujuan = "DELETE FROM `tb_undangan_tujuan` WHERE `id_undangan` = $id_undangan";
        if (!mysqli_query($koneksi, $sql_hapus_tujuan)) {
            throw new Exception("Gagal mereset data penerima lama: " . mysqli_error($koneksi));
        }

        $nama_tujuan    = $_POST['nama_tujuan'] ?? [];
        $jabatan_tujuan = $_POST['jabatan_tujuan'] ?? [];
        $alamat_tujuan  = $_POST['alamat_tujuan'] ?? [];

        if (empty($nama_tujuan) || empty(trim($nama_tujuan[0]))) {
            throw new Exception("Daftar Penerima Undangan tidak boleh kosong!");
        }

        for ($i = 0; $i < count($nama_tujuan); $i++) {
            if (!empty(trim($nama_tujuan[$i]))) {
                $nama    = mysqli_real_escape_string($koneksi, $nama_tujuan[$i]);
                $jabatan = mysqli_real_escape_string($koneksi, $jabatan_tujuan[$i]);
                $alamat  = mysqli_real_escape_string($koneksi, $alamat_tujuan[$i]);

                $sql_insert_tujuan = "INSERT INTO `tb_undangan_tujuan` (`id_undangan`, `nama_tujuan`, `jabatan_tujuan`, `alamat_tujuan`) 
                                      VALUES ('$id_undangan', '$nama', '$jabatan', '$alamat')";
                
                if (!mysqli_query($koneksi, $sql_insert_tujuan)) {
                    throw new Exception("Gagal menyimpan baris penerima ke-" . ($i + 1) . ": " . mysqli_error($koneksi));
                }
            }
        }

        // Commit jika semua proses berhasil tanpa kendala
        mysqli_commit($koneksi);

        echo "<script>
                alert('Data Surat Undangan Berhasil Diperbarui!');
                window.location.href = 'index.php?page=surat-undangan';
              </script>";
        exit;

    } catch (Exception $e) {
        // Rollback jika terjadi kegagalan sistem di tengah jalan
        mysqli_rollback($koneksi);
        echo "<div class='alert alert-danger m-4'><i class='fas fa-exclamation-triangle me-2'></i><strong>Gagal Mengubah Data:</strong> " . $e->getMessage() . "</div>";
    }
}
?>

<style>
.card-modern {
    border: none !important;
    border-radius: 15px !important;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05) !important;
}

.page-title-modern {
    font-weight: 700;
    color: #2c3e50;
}

.form-label-custom {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

.section-divider {
    border-top: 2px dashed #e9ecef;
    margin: 2rem 0;
}
</style>

<div class="container-fluid px-4 py-3">
    <!-- Header Halaman -->
    <div class="mb-4">
        <h3 class="page-title-modern mt-2 mb-1">Edit Surat Undangan</h3>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="index.php?page=dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="index.php?page=surat-undangan">Daftar Surat</a></li>
            <li class="breadcrumb-item active">Edit</li>
        </ol>
    </div>

    <!-- Form Utama -->
    <form action="" method="POST">
        <div class="row">

            <!-- SISI KIRI: DATA AGENDA & ATRIBUT SURAT -->
            <div class="col-lg-7 mb-4">
                <div class="card card-modern p-4">
                    <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-edit me-2"></i>Atribut & Detail Agenda</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Nomor Surat</label>
                            <input type="text" class="form-control" name="nomor_surat"
                                value="<?= htmlspecialchars($data_surat['nomor_surat']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Sifat</label>
                            <input type="text" class="form-control" name="sifat"
                                value="<?= htmlspecialchars($data_surat['sifat']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Lampiran</label>
                            <input type="text" class="form-control" name="lampiran"
                                value="<?= htmlspecialchars($data_surat['lampiran']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Perihal</label>
                            <input type="text" class="form-control" name="perihal"
                                value="<?= htmlspecialchars($data_surat['perihal']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Tempat Pembuatan</label>
                            <input type="text" class="form-control" name="tempat_surat"
                                value="<?= htmlspecialchars($data_surat['tempat_surat']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label form-label-custom">Tanggal Surat Dibuat</label>
                            <input type="date" class="form-control" name="tanggal_surat"
                                value="<?= $data_surat['tanggal_surat']; ?>" required>
                        </div>

                        <div class="col-12">
                            <div class="section-divider my-2"></div>
                        </div>

                        <!-- Waktu & Tempat Pelaksanaan -->
                        <div class="col-md-4">
                            <label class="form-label form-label-custom">Hari Acara</label>
                            <select class="form-select" name="hari_acara" required>
                                <?php 
                                $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                foreach ($hari_list as $h) {
                                    $selected = ($data_surat['hari_acara'] == $h) ? 'selected' : '';
                                    echo "<option value='$h' $selected>$h</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-custom">Tanggal Acara</label>
                            <input type="date" class="form-control" name="tanggal_acara"
                                value="<?= $data_surat['tanggal_acara']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label form-label-custom">Jam/Waktu</label>
                            <input type="text" class="form-control" name="jam_acara"
                                value="<?= htmlspecialchars($data_surat['jam_acara']); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label form-label-custom">Tempat Pelaksanaan Acara</label>
                            <input type="text" class="form-control" name="tempat_acara"
                                value="<?= htmlspecialchars($data_surat['tempat_acara']); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label form-label-custom">Nama Acara / Agenda</label>
                            <input type="text" class="form-control" name="acara"
                                value="<?= htmlspecialchars($data_surat['acara']); ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label form-label-custom">Keterangan Tambahan</label>
                            <textarea class="form-control" name="keterangan"
                                rows="2"><?= htmlspecialchars($data_surat['keterangan']); ?></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label form-label-custom">Pejabat Penandatangan (Ttd)</label>
                            <select class="form-select" name="id_pejabat" required>
                                <option value="">-- Pilih Pejabat Desa --</option>
                                <?php while($pejabat = mysqli_fetch_assoc($query_pejabat)): ?>
                                <option value="<?= $pejabat['id_pejabat']; ?>"
                                    <?= ($data_surat['id_pejabat'] == $pejabat['id_pejabat']) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($pejabat['nama_pejabat']) . " (" . htmlspecialchars($pejabat['jabatan']) . ")"; ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SISI KANAN: DAFTAR PENERIMA UNDANGAN (DINAMIS LOAD & MANIPULASI) -->
            <div class="col-lg-5 mb-4">
                <div class="card card-modern p-4 bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0 text-success"><i class="fas fa-users me-2"></i>Daftar Penerima</h5>
                        <button type="button" class="btn btn-sm btn-success" id="btn-tambah-penerima">
                            <i class="fas fa-plus me-1"></i> Tambah Baris
                        </button>
                    </div>

                    <div id="container-penerima">
                        <?php 
                        $index = 1;
                        if (mysqli_num_rows($query_tujuan) > 0) {
                            while ($tujuan = mysqli_fetch_assoc($query_tujuan)) {
                                ?>
                        <div class="card p-3 mb-3 item-penerima border-0 shadow-sm"
                            style="border-left: 4px solid #198754 !important;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-secondary badge bg-white border">Penerima
                                    #<?= $index; ?></span>
                                <?php if ($index > 1): ?>
                                <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-hapus-baris"
                                    style="padding: 2px 6px;">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </button>
                                <?php endif; ?>
                            </div>
                            <div class="mb-2">
                                <label class="small fw-semibold text-muted">Jabatan / Sapaan (Yth.)</label>
                                <input type="text" class="form-control form-control-sm" name="jabatan_tujuan[]"
                                    value="<?= htmlspecialchars($tujuan['jabatan_tujuan']); ?>">
                            </div>
                            <div class="mb-2">
                                <label class="small fw-semibold text-muted">Nama Penerima</label>
                                <input type="text" class="form-control form-control-sm" name="nama_tujuan[]"
                                    value="<?= htmlspecialchars($tujuan['nama_tujuan']); ?>" required>
                            </div>
                            <div>
                                <label class="small fw-semibold text-muted">Alamat Tujuan</label>
                                <input type="text" class="form-control form-control-sm" name="alamat_tujuan[]"
                                    value="<?= htmlspecialchars($tujuan['alamat_tujuan']); ?>">
                            </div>
                        </div>
                        <?php
                                $index++;
                            }
                        } else {
                            // Antisipasi fallback jika data kosong
                            ?>
                        <div class="card p-3 mb-3 item-penerima border-0 shadow-sm"
                            style="border-left: 4px solid #198754 !important;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-secondary badge bg-white border">Penerima #1</span>
                            </div>
                            <div class="mb-2">
                                <label class="small fw-semibold text-muted">Jabatan / Sapaan (Yth.)</label>
                                <input type="text" class="form-control form-control-sm" name="jabatan_tujuan[]"
                                    value="Bpk/Ibu/Sdr/I">
                            </div>
                            <div class="mb-2">
                                <label class="small fw-semibold text-muted">Nama Penerima</label>
                                <input type="text" class="form-control form-control-sm" name="nama_tujuan[]" required>
                            </div>
                            <div>
                                <label class="small fw-semibold text-muted">Alamat Tujuan</label>
                                <input type="text" class="form-control form-control-sm" name="alamat_tujuan[]"
                                    value="Tempat">
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Box Tombol Aksi Akhir -->
                <div class="card card-modern p-3 mt-3 text-end">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="index.php?page=surat-undangan" class="btn btn-light border px-4 fw-semibold">Batal</a>
                        <button type="submit" name="update" class="btn btn-warning px-4 fw-semibold text-dark">Perbarui
                            Surat</button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- JAVASCRIPT DINAMIS UNTUK FORM EDIT -->
<script>
document.getElementById('btn-tambah-penerima').addEventListener('click', function() {
    var container = document.getElementById('container-penerima');
    var jumlahBaris = container.getElementsByClassName('item-penerima').length + 1;

    var htmlBarisBaru = `
    <div class="card p-3 mb-3 item-penerima border-0 shadow-sm" style="border-left: 4px solid #198754 !important;">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-bold text-secondary badge bg-white border">Penerima #${jumlahBaris}</span>
            <button type="button" class="btn btn-sm btn-outline-danger border-0 btn-hapus-baris" style="padding: 2px 6px;">
                <i class="fas fa-trash-alt"></i> Hapus
            </button>
        </div>
        <div class="mb-2">
            <label class="small fw-semibold text-muted">Jabatan / Sapaan (Yth.)</label>
            <input type="text" class="form-control form-control-sm" name="jabatan_tujuan[]" value="Bpk/Ibu/Sdr/I">
        </div>
        <div class="mb-2">
            <label class="small fw-semibold text-muted">Nama Penerima</label>
            <input type="text" class="form-control form-control-sm" name="nama_tujuan[]" placeholder="Nama lengkap penerima" required>
        </div>
        <div>
            <label class="small fw-semibold text-muted">Alamat Tujuan</label>
            <input type="text" class="form-control form-control-sm" name="alamat_tujuan[]" value="Tempat">
        </div>
    </div>`;

    container.insertAdjacentHTML('beforeend', htmlBarisBaru);
});

document.getElementById('container-penerima').addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-hapus-baris') || e.target.closest('.btn-hapus-baris')) {
        var baris = e.target.closest('.item-penerima');
        baris.remove();

        // Re-index label angka penerima agar berurutan kembali
        var semuaBaris = document.getElementsByClassName('item-penerima');
        for (var i = 0; i < semuaBaris.length; i++) {
            semuaBaris[i].querySelector('.badge').innerText = 'Penerima #' + (i + 1);
        }
    }
});
</script>